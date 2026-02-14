<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BarangModel;
use App\Models\PinjamModel;
use App\Models\UserModel;
use App\Models\UserProfileModel;
use App\Models\ActivityLogModel;

class DashboardController extends BaseController
{
    protected $db;
    protected $userProfileModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->userProfileModel = new UserProfileModel();
    }

    /**
     * Tampilkan dashboard sesuai peran user
     */
    public function index()
    {
        $role = session()->get('role');

        return match ($role) {
            'admin' => $this->adminDashboard(),
            'petugas' => $this->petugasDashboard(),
            'peminjam' => $this->peminjamDashboard(),
            default => throw new \CodeIgniter\Exceptions\PageNotFoundException()
        };
    }

    /**
     * Dashboard untuk admin dengan statistik lengkap
     */
    private function adminDashboard()
    {
        $data = [
            'nama' => $this->getUserName(),
            ...$this->getBarangStats(),
            ...$this->getPeminjamanStats(),
            'totalUser' => $this->getTotalUsers(),
            'totalKategori' => $this->getTotalKategori(),
            'peminjamanPerBulan' => $this->getPeminjamanPerBulan(),
            'barangPopuler' => $this->getBarangPopuler(),
            'userAktif' => $this->getUserAktif(),
            'peminjamanTerbaru' => $this->getPeminjamanTerbaru(5)
        ];

        return view('dashboard/admin', $data);
    }

    /**
     * Dashboard untuk petugas
     */
    private function petugasDashboard()
    {
        $data = [
            'nama' => $this->getUserName(),
            ...$this->getPeminjamanStats(),
            ...$this->getBarangStats(),
            'totalKategori' => $this->getTotalKategori(),
            'peminjamanMenungguList' => $this->getPeminjamanMenungguList(5),
            'peminjamanTerbaru' => $this->getPeminjamanTerbaru(10)
        ];

        return view('dashboard/petugas', $data);
    }

    /**
     * Dashboard untuk peminjam
     */
    private function peminjamDashboard()
    {
        $userId = session('id_user');
        
        $data = [
            'nama' => $this->getUserName(),
            'peminjamanSaya' => $this->getPeminjamanByUser($userId),
            'totalPeminjamanSaya' => $this->getTotalPeminjamanByUser($userId),
            'peminjamanAktif' => $this->getPeminjamanAktifByUser($userId),
            'riwayatTerbaru' => $this->getRiwayatPeminjamanByUser($userId, 5)
        ];

        return view('dashboard/peminjam', $data);
    }

    /**
     * Cetak laporan peminjaman
     */
    public function cetakLaporan()
    {
        $role = session()->get('role');
        if (!in_array($role, ['petugas', 'admin'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $filters = [
            'status' => $this->request->getGet('status'),
            'tglMulai' => $this->request->getGet('tgl_mulai'),
            'tglSelesai' => $this->request->getGet('tgl_selesai')
        ];

        $data = [
            'dataPinjam' => $this->getFilteredPeminjaman($filters),
            'namaPetugas' => $this->getUserName(),
            ...$filters
        ];

        return view('dashboard/cetak_laporan', $data);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Ambil nama user dari profile
     */
    private function getUserName(): string
    {
        $profile = $this->userProfileModel->where('id_user', session('id_user'))->first();
        return $profile['nama'] ?? explode('@', session('email'))[0];
    }

    /**
     * Statistik barang
     */
    private function getBarangStats(): array
    {
        $builder = $this->db->table('barang')->where('deleted_at IS NULL');
        
        return [
            'totalBarang' => (clone $builder)->countAllResults(),
            'barangTersedia' => (clone $builder)->where('status', 'tersedia')->countAllResults(),
            'barangDipinjam' => (clone $builder)->where('status', 'dipinjam')->countAllResults(),
            'barangTidakTersedia' => (clone $builder)->where('status', 'tidak tersedia')->countAllResults()
        ];
    }

    /**
     * Statistik peminjaman
     */
    private function getPeminjamanStats(): array
    {
        $builder = $this->db->table('pinjam')->where('deleted_at IS NULL');
        
        return [
            'totalPeminjaman' => (clone $builder)->countAllResults(),
            'peminjamanMenunggu' => (clone $builder)->where('status', 'menunggu')->countAllResults(),
            'peminjamanDisetujui' => (clone $builder)->where('status', 'disetujui')->countAllResults(),
            'peminjamanDikembalikan' => (clone $builder)->where('status', 'dikembalikan')->countAllResults(),
            'peminjamanDitolak' => (clone $builder)->where('status', 'ditolak')->countAllResults()
        ];
    }

    /**
     * Total user
     */
    private function getTotalUsers(): int
    {
        return $this->db->table('user')->countAllResults();
    }

    /**
     * Total kategori
     */
    private function getTotalKategori(): int
    {
        return $this->db->table('kategori')->countAllResults();
    }

    /**
     * Statistik peminjaman per bulan (6 bulan terakhir)
     */
    private function getPeminjamanPerBulan(int $months = 6): array
    {
        $result = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $bulan = date('Y-m', strtotime("-$i month"));
            $count = $this->db->table('pinjam')
                ->where('deleted_at IS NULL')
                ->where("DATE_FORMAT(tgl_pengajuan, '%Y-%m')", $bulan)
                ->countAllResults();
            
            $result[] = [
                'bulan' => date('M Y', strtotime($bulan . '-01')),
                'total' => $count
            ];
        }
        return $result;
    }

    /**
     * Barang paling populer (paling sering dipinjam)
     */
    private function getBarangPopuler(int $limit = 5): array
    {
        return $this->db->table('pinjam')
            ->select('kategori.nama_kategori, barang.merek_barang, barang.kode_barang, COUNT(pinjam.id_pinjam) as total')
            ->join('barang', 'barang.id_barang = pinjam.id_barang')
            ->join('kategori', 'kategori.id_kategori = barang.id_kategori', 'left')
            ->where('pinjam.deleted_at IS NULL')
            ->groupBy('pinjam.id_barang')
            ->orderBy('total', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * User paling aktif
     */
    private function getUserAktif(int $limit = 5): array
    {
        return $this->db->table('pinjam')
            ->select('user.email, userprofile.nama, COUNT(pinjam.id_pinjam) as total')
            ->join('user', 'user.id_user = pinjam.id_user')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->where('pinjam.deleted_at IS NULL')
            ->groupBy('pinjam.id_user')
            ->orderBy('total', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Aktivitas terbaru
     */
    private function getAktivitasTerbaru(int $limit = 10): array
    {
        return $this->db->table('activity_log')
            ->select('activity_log.*, userprofile.nama, user.email')
            ->join('user', 'user.id_user = activity_log.id_user')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->orderBy('activity_log.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Peminjaman terbaru
     */
    private function getPeminjamanTerbaru(int $limit = 5): array
    {
        return $this->db->table('pinjam')
            ->select('pinjam.*, barang.merek_barang, barang.kode_barang, kategori.nama_kategori, userprofile.nama, user.email')
            ->join('barang', 'barang.id_barang = pinjam.id_barang', 'left')
            ->join('kategori', 'kategori.id_kategori = barang.id_kategori', 'left')
            ->join('user', 'user.id_user = pinjam.id_user', 'left')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->where('pinjam.deleted_at IS NULL')
            ->orderBy('pinjam.tgl_pengajuan', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Peminjaman yang menunggu persetujuan
     */
    private function getPeminjamanMenungguList(int $limit = 5): array
    {
        return $this->db->table('pinjam')
            ->select('pinjam.*, barang.merek_barang, barang.kode_barang, kategori.nama_kategori, userprofile.nama, user.email')
            ->join('barang', 'barang.id_barang = pinjam.id_barang', 'left')
            ->join('kategori', 'kategori.id_kategori = barang.id_kategori', 'left')
            ->join('user', 'user.id_user = pinjam.id_user', 'left')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->where('pinjam.deleted_at IS NULL')
            ->where('pinjam.status', 'menunggu')
            ->orderBy('pinjam.tgl_pengajuan', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Peminjaman berdasarkan user
     */
    private function getPeminjamanByUser(int $userId): array
    {
        $builder = $this->db->table('pinjam')->where('id_user', $userId)->where('deleted_at IS NULL');
        
        return [
            'menunggu' => (clone $builder)->where('status', 'menunggu')->countAllResults(),
            'disetujui' => (clone $builder)->where('status', 'disetujui')->countAllResults(),
            'dikembalikan' => (clone $builder)->where('status', 'dikembalikan')->countAllResults(),
            'ditolak' => (clone $builder)->where('status', 'ditolak')->countAllResults()
        ];
    }

    /**
     * Total peminjaman by user
     */
    private function getTotalPeminjamanByUser(int $userId): int
    {
        return $this->db->table('pinjam')
            ->where('id_user', $userId)
            ->where('deleted_at IS NULL')
            ->countAllResults();
    }

    /**
     * Peminjaman aktif by user
     */
    private function getPeminjamanAktifByUser(int $userId): int
    {
        return $this->db->table('pinjam')
            ->where('id_user', $userId)
            ->where('deleted_at IS NULL')
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->countAllResults();
    }

    /**
     * Riwayat peminjaman by user
     */
    private function getRiwayatPeminjamanByUser(int $userId, int $limit = 5): array
    {
        return $this->db->table('pinjam')
            ->select('pinjam.*, barang.merek_barang, barang.kode_barang, kategori.nama_kategori')
            ->join('barang', 'barang.id_barang = pinjam.id_barang', 'left')
            ->join('kategori', 'kategori.id_kategori = barang.id_kategori', 'left')
            ->where('pinjam.id_user', $userId)
            ->where('pinjam.deleted_at IS NULL')
            ->orderBy('pinjam.tgl_pengajuan', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Get filtered peminjaman untuk laporan
     */
    private function getFilteredPeminjaman(array $filters): array
    {
        $builder = $this->db->table('pinjam')
            ->select('pinjam.*, kategori.nama_kategori, barang.merek_barang, barang.tipe_barang, barang.kode_barang, user.email, userprofile.nama')
            ->join('barang', 'barang.id_barang = pinjam.id_barang', 'left')
            ->join('kategori', 'kategori.id_kategori = barang.id_kategori', 'left')
            ->join('user', 'user.id_user = pinjam.id_user', 'left')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->where('pinjam.deleted_at IS NULL');

        if (!empty($filters['status'])) {
            $builder->where('pinjam.status', $filters['status']);
        }

        if (!empty($filters['tglMulai'])) {
            $builder->where('DATE(pinjam.tgl_pengajuan) >=', $filters['tglMulai']);
        }

        if (!empty($filters['tglSelesai'])) {
            $builder->where('DATE(pinjam.tgl_pengajuan) <=', $filters['tglSelesai']);
        }

        return $builder->orderBy('pinjam.tgl_pengajuan', 'DESC')->get()->getResultArray();
    }
}
