<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BarangModel;

class DashboardController extends BaseController
{
    // Tampilkan dashboard sesuai peran user
    public function index()
    {
        // Ambil role dari session
        $role = session()->get('role');

        // Tampilkan dashboard sesuai role
        switch ($role) {
            case 'admin':
                return $this->adminDashboard();

            case 'petugas':
                return $this->petugasDashboard();

            case 'peminjam':
                return $this->peminjamDashboard();

            default:
                throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }
    }

    // Tampilkan dashboard untuk admin
    private function adminDashboard()
    {
        $barangModel = new BarangModel();
        $pinjamModel = new \App\Models\PinjamModel();
        $userModel = new \App\Models\UserModel();
        $userProfileModel = new \App\Models\UserProfileModel();

        // Ambil nama user dari profile
        $profile = $userProfileModel->where('id_user', session('id_user'))->first();
        $nama = $profile['nama'] ?? explode('@', session('email'))[0];

        // Statistik Barang
        $db = \Config\Database::connect();
        $totalBarang = $db->table('barang')->where('deleted_at IS NULL')->countAllResults(false);
        $barangTersedia = $db->table('barang')->where('deleted_at IS NULL')->where('status', 'tersedia')->countAllResults(false);
        $barangDipinjam = $db->table('barang')->where('deleted_at IS NULL')->where('status', 'dipinjam')->countAllResults(false);
        $barangTidakTersedia = $db->table('barang')->where('deleted_at IS NULL')->where('status', 'tidak tersedia')->countAllResults();

        // Statistik Peminjaman
        $db = \Config\Database::connect();
        $totalPeminjaman = $db->table('pinjam')->where('deleted_at IS NULL')->countAllResults(false);
        $peminjamanMenunggu = $db->table('pinjam')->where('deleted_at IS NULL')->where('status', 'menunggu')->countAllResults(false);
        $peminjamanDisetujui = $db->table('pinjam')->where('deleted_at IS NULL')->where('status', 'disetujui')->countAllResults(false);
        $peminjamanDikembalikan = $db->table('pinjam')->where('deleted_at IS NULL')->where('status', 'dikembalikan')->countAllResults();

        // Statistik User
        $totalUser = $db->table('user')->countAllResults();

        // Tampilkan view dengan data
        return view('dashboard/admin', [
            'nama' => $nama,
            'totalBarang' => $totalBarang,
            'barangTersedia' => $barangTersedia,
            'barangDipinjam' => $barangDipinjam,
            'barangTidakTersedia' => $barangTidakTersedia,
            'totalPeminjaman' => $totalPeminjaman,
            'peminjamanMenunggu' => $peminjamanMenunggu,
            'peminjamanDisetujui' => $peminjamanDisetujui,
            'peminjamanDikembalikan' => $peminjamanDikembalikan,
            'totalUser' => $totalUser
        ]);
    }

    // Tampilkan dashboard untuk petugas
    private function petugasDashboard()
    {
        $userProfileModel = new \App\Models\UserProfileModel();
        
        // Ambil nama user dari profile
        $profile = $userProfileModel->where('id_user', session('id_user'))->first();
        $nama = $profile['nama'] ?? explode('@', session('email'))[0];

        // Statistik untuk dashboard petugas
        $db = \Config\Database::connect();
        $totalPeminjaman = $db->table('pinjam')->where('deleted_at IS NULL')->countAllResults(false);
        $peminjamanMenunggu = $db->table('pinjam')->where('deleted_at IS NULL')->where('status', 'menunggu')->countAllResults(false);
        $peminjamanDisetujui = $db->table('pinjam')->where('deleted_at IS NULL')->where('status', 'disetujui')->countAllResults(false);
        $peminjamanDikembalikan = $db->table('pinjam')->where('deleted_at IS NULL')->where('status', 'dikembalikan')->countAllResults();

        return view('dashboard/petugas', [
            'nama' => $nama,
            'totalPeminjaman' => $totalPeminjaman,
            'peminjamanMenunggu' => $peminjamanMenunggu,
            'peminjamanDisetujui' => $peminjamanDisetujui,
            'peminjamanDikembalikan' => $peminjamanDikembalikan
        ]);
    }

    // Cetak laporan peminjaman untuk petugas
    public function cetakLaporan()
    {
        // Cek apakah user adalah petugas atau admin
        $role = session()->get('role');
        if (!in_array($role, ['petugas', 'admin'])) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        // Ambil parameter filter dari query string
        $status = $this->request->getGet('status');
        $tglMulai = $this->request->getGet('tgl_mulai');
        $tglSelesai = $this->request->getGet('tgl_selesai');

        // Query data peminjaman
        $db = \Config\Database::connect();
        $builder = $db->table('pinjam')
            ->select('
                pinjam.*,
                barang.jenis_barang,
                barang.merek_barang,
                barang.tipe_barang,
                barang.kode_barang,
                user.email,
                userprofile.nama
            ')
            ->join('barang', 'barang.id_barang = pinjam.id_barang', 'left')
            ->join('user', 'user.id_user = pinjam.id_user', 'left')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->where('pinjam.deleted_at IS NULL');

        // Terapkan filter
        if ($status) {
            $builder->where('pinjam.status', $status);
        }

        if ($tglMulai) {
            $builder->where('DATE(pinjam.tgl_pengajuan) >=', $tglMulai);
        }

        if ($tglSelesai) {
            $builder->where('DATE(pinjam.tgl_pengajuan) <=', $tglSelesai);
        }

        $dataPinjam = $builder->orderBy('pinjam.tgl_pengajuan', 'DESC')->get()->getResultArray();

        // Ambil data petugas
        $userProfileModel = new \App\Models\UserProfileModel();
        $profile = $userProfileModel->where('id_user', session('id_user'))->first();
        $namaPetugas = $profile['nama'] ?? explode('@', session('email'))[0];

        // Tampilkan view cetak
        return view('dashboard/cetak_laporan', [
            'dataPinjam' => $dataPinjam,
            'namaPetugas' => $namaPetugas,
            'status' => $status,
            'tglMulai' => $tglMulai,
            'tglSelesai' => $tglSelesai
        ]);
    }

    // Tampilkan dashboard untuk peminjam
    private function peminjamDashboard()
    {
        $userProfileModel = new \App\Models\UserProfileModel();
        
        // Ambil nama user dari profile
        $profile = $userProfileModel->where('id_user', session('id_user'))->first();
        $nama = $profile['nama'] ?? explode('@', session('email'))[0];

        return view('dashboard/peminjam', ['nama' => $nama]);
    }
}
