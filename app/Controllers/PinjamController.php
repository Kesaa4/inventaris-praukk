<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PinjamModel;
use App\Models\BarangModel;
use App\Models\UserProfileModel;
use App\Models\UserModel;

helper('user');

class PinjamController extends BaseController
{
    protected function mustLogin()
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/');
        }
    }

    protected function mustAdmin()
    {
        if (session('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }
    }

    protected function mustAdminOrPetugas()
    {
        if (!in_array(session('role'), ['admin', 'petugas'])) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }
    }
    
    protected function mustPeminjamOrPetugas()
    {
        if (!in_array(session('role'), ['peminjam', 'petugas'])) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }
    }

    public function index()
    {
        $this->mustLogin();

        $pinjamModel = new PinjamModel();

        $filters = [
            'keyword'               => $this->request->getGet('keyword'),
            'status'                => $this->request->getGet('status'),
            'tgl_pengajuan'         => $this->request->getGet('tgl_pengajuan'),
            'tgl_disetujui_kembali' => $this->request->getGet('tgl_disetujui_kembali'),
        ];

        $userId = (session('role') === 'peminjam') ? session('id_user') : null;

        if (array_filter($filters)) {
            $pinjamModel->filterPinjam($filters, $userId);
        } else {
            $pinjamModel->getPinjamWithRelasi($userId);
        }

        $data = [
            'pinjam'  => $pinjamModel->paginate(10, 'pinjam'),
            'pager'   => $pinjamModel->pager,
            'filters' => $filters,
        ];

        return view('pinjam/index', $data);
    }

    // FORM AJUKAN PINJAM
    public function create()
    {
        $this->mustLogin();

        $barangModel = new BarangModel();
        $userModel   = new UserModel();

        // Siapkan data barang yang tersedia (case-insensitive untuk status)
        $barang = $barangModel
            ->select('barang.id_barang, barang.merek_barang, barang.tipe_barang, barang.kode_barang, barang.status, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = barang.id_kategori', 'left')
            ->where('LOWER(barang.status)', 'tersedia')
            ->orderBy('kategori.nama_kategori', 'ASC')
            ->orderBy('barang.merek_barang', 'ASC')
            ->findAll();

        $data = ['barang' => $barang];

        // Kalau petugas/admin, kirim data user dengan nama
        if (in_array(session('role'), ['admin', 'petugas'])) {
            $data['users'] = $userModel
                ->select('user.id_user, user.email, userprofile.nama')
                ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
                ->where('user.role', 'peminjam')
                ->orderBy('userprofile.nama', 'ASC')
                ->findAll();
        }

        return view('pinjam/create', $data);
    }

    // SIMPAN PINJAMAN
    public function store()
    {
        $this->mustLogin();

        $pinjamModel = new PinjamModel();
        $barangModel = new BarangModel();

        // Tentukan user yang meminjam
        if (in_array(session('role'), ['admin', 'petugas'])) {
            $idUserPeminjam = $this->request->getPost('id_user');

            // Validasi: tidak boleh meminjamkan ke diri sendiri
            if (!$idUserPeminjam) {
                return redirect()->back()->with('error', 'Peminjam harus dipilih')->withInput();
            }

            if ($idUserPeminjam == session('id_user')) {
                return redirect()->back()->with('error', 'Tidak boleh meminjamkan ke diri sendiri')->withInput();
            }
        } else {
            $idUserPeminjam = session('id_user');
        }

        // Validasi barang
        $idBarang = $this->request->getPost('id_barang');
        if (!$idBarang) {
            return redirect()->back()->with('error', 'Barang harus dipilih')->withInput();
        }

        // Validasi durasi peminjaman
        $durasi = $this->request->getPost('durasi_pinjam');
        if (!$durasi || $durasi < 1) {
            return redirect()->back()->with('error', 'Durasi peminjaman minimal 1 hari')->withInput();
        }
        $durasi = (int)$durasi;

        // Ambil data barang (termasuk yang soft deleted untuk validasi lengkap)
        $barang = $barangModel->withDeleted()->find($idBarang);
        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan')->withInput();
        }

        // Cek apakah barang sudah dihapus
        if (!empty($barang['deleted_at'])) {
            return redirect()->back()->with('error', 'Barang sudah tidak tersedia (telah dihapus)')->withInput();
        }

        // Cek status barang (case-insensitive)
        if (strtolower($barang['status']) !== 'tersedia') {
            return redirect()->back()->with('error', 'Barang tidak tersedia untuk dipinjam (Status: ' . ucfirst($barang['status']) . ')')->withInput();
        }

        // Simpan data peminjaman dengan durasi
        $id = $pinjamModel->insert([
            'id_barang'     => $idBarang,
            'id_user'       => $idUserPeminjam,
            'created_by'    => session('id_user'),
            'tgl_pengajuan' => date('Y-m-d H:i:s'),
            'durasi_pinjam' => $durasi,
            'status'        => 'menunggu'
        ]);

        // Update status barang
        $barangModel->update($idBarang, ['status' => 'dibooking']);

        // Log activity
        $namaPeminjam = getNamaUser($idUserPeminjam);
        $kodeBarang = $barang['kode_barang'] ?? $idBarang;
        log_activity(
            'Menambahkan peminjaman untuk ' . $namaPeminjam . ' - ' . $kodeBarang . 
            '||Durasi: ' . $durasi . ' hari',
            'pinjam',
            $id
        );

        return redirect()->to('/pinjam')->with('success', 'Pengajuan peminjaman berhasil (Durasi: ' . $durasi . ' hari)');
    }

    // EDIT STATUS (ADMIN / PETUGAS)
    public function edit($id)
    {
        // Pastikan admin atau petugas
        $this->mustAdminOrPetugas();

        $pinjamModel = new PinjamModel();

        // Ambil data peminjaman dengan relasi
        $pinjam = $pinjamModel->getPinjamWithRelasiById($id);

        // Cek data ada tidak
        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data peminjaman tidak ditemukan');
        }

        // Tampilkan view dengan data
        return view('pinjam/edit', [
            'pinjam' => $pinjam
        ]);
    }

    // UPDATE STATUS PEMINJAMAN
    public function update($id)
    {
        $this->mustAdminOrPetugas();

        $pinjamModel = new PinjamModel();
        $barangModel = new BarangModel();

        // Ambil data peminjaman
        $pinjam = $pinjamModel->find($id);
        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data peminjaman tidak ditemukan');
        }

        // Ambil data pendukung
        $namaPeminjam = getNamaUser($pinjam['id_user']);
        $barang = $barangModel->find($pinjam['id_barang']);
        $kodeBarang = $barang['kode_barang'] ?? $pinjam['id_barang'];

        // Ambil status dari form
        $status = $this->request->getPost('status');
        if (!$status) {
            return redirect()->back()->with('error', 'Status harus dipilih');
        }

        $data = [
            'status'         => $status,
            'alasan_ditolak' => null
        ];

        // Proses berdasarkan status
        switch ($status) {
            case 'disetujui':
                // Gunakan durasi yang sudah diinput saat pengajuan, atau default 7 hari
                $durasi = $pinjam['durasi_pinjam'] ?? 7;
                
                // Hitung tanggal jatuh tempo
                $tglJatuhTempo = date('Y-m-d H:i:s', strtotime("+{$durasi} days"));
                
                $data['tgl_disetujui']  = date('Y-m-d H:i:s');
                $data['tgl_jatuh_tempo'] = $tglJatuhTempo;
                $data['durasi_pinjam']  = $durasi;
                $data['approved_at']    = date('Y-m-d H:i:s');
                $data['approved_by']    = session('id_user');

                // Update status barang
                $barangModel->update($pinjam['id_barang'], ['status' => 'dipinjam']);

                log_activity(
                    'Menyetujui peminjaman ' . $namaPeminjam . ' - ' . $kodeBarang . 
                    '||Durasi: ' . $durasi . ' hari;Jatuh tempo: ' . date('d-m-Y', strtotime($tglJatuhTempo)),
                    'pinjam',
                    $id
                );
                break;

            case 'ditolak':
                // Validasi alasan
                $alasan = $this->request->getPost('alasan_ditolak');
                if (!$alasan) {
                    return redirect()->back()->with('error', 'Alasan penolakan harus diisi');
                }

                $data['alasan_ditolak'] = $alasan;
                
                // Update status barang
                $barangModel->update($pinjam['id_barang'], ['status' => 'tersedia']);
                
                log_activity(
                    'Menolak peminjaman ' . $namaPeminjam . 
                    '||Barang: ' . $kodeBarang . ';Alasan: ' . $alasan,
                    'pinjam',
                    $id
                );
                break;

            case 'dikembalikan':
                // Update status barang
                $barangModel->update($pinjam['id_barang'], ['status' => 'tersedia']);

                log_activity(
                    'Menyetujui pengembalian barang ' . $namaPeminjam . ' - ' . $kodeBarang,
                    'pinjam',
                    $id
                );
                break;
        }

        // Update data peminjaman
        $pinjamModel->update($id, $data);

        return redirect()->to('/pinjam')->with('success', 'Status peminjaman berhasil diubah');
    }

    // DELETE (ADMIN ONLY)
    public function delete($id)
    {
        $this->mustAdmin();

        $pinjamModel = new PinjamModel();
        $barangModel = new BarangModel();

        // Ambil data peminjaman
        $pinjam = $pinjamModel->find($id);
        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data peminjaman tidak ditemukan');
        }

        // Data pendukung log
        $namaPeminjam = getNamaUser($pinjam['id_user']);
        $barang = $barangModel->find($pinjam['id_barang']);
        $kodeBarang = $barang['kode_barang'] ?? $pinjam['id_barang'];

        // Soft delete
        $pinjamModel->delete($id);

        log_activity(
            'Menghapus peminjaman ' . $namaPeminjam . ' - ' . $kodeBarang,
            'pinjam',
            $id
        );

        return redirect()->to('/pinjam')->with('success', 'Data peminjaman berhasil dihapus');
    }

    // Ajukan pengembalian barang oleh peminjam
    public function requestReturn($id)
    {
        if (session('role') !== 'peminjam') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException('Hanya peminjam yang dapat mengajukan pengembalian');
        }

        $pinjamModel = new PinjamModel();
        $barangModel = new BarangModel();

        // Ambil data peminjaman
        $pinjam = $pinjamModel->find($id);
        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data peminjaman tidak ditemukan');
        }

        // Validasi: hanya peminjam yang bersangkutan yang bisa mengajukan
        if ($pinjam['id_user'] != session('id_user')) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException('Anda tidak memiliki akses');
        }

        // Validasi status
        if ($pinjam['status'] !== 'disetujui') {
            return redirect()->back()->with('error', 'Hanya peminjaman yang disetujui yang dapat dikembalikan');
        }

        // Data pendukung log
        $barang = $barangModel->find($pinjam['id_barang']);
        $kodeBarang = $barang['kode_barang'] ?? $pinjam['id_barang'];

        // Update status peminjaman
        $pinjamModel->update($id, [
            'status'                => 'pengembalian',
            'tgl_pengajuan_kembali' => date('Y-m-d H:i:s')
        ]);

        log_activity(
            'Mengajukan pengembalian barang ' . $kodeBarang,
            'pinjam',
            $id
        );

        return redirect()->to('/pinjam')->with('success', 'Pengembalian berhasil diajukan');
    }

    // View pengecekan pengembalian oleh petugas dan admin
    public function returnCheck($id)
    {
        $this->mustAdminOrPetugas();

        $pinjamModel = new PinjamModel();

        // Ambil data peminjaman
        $pinjam = $pinjamModel->getPinjamWithRelasiById($id);
        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data peminjaman tidak ditemukan');
        }

        // Validasi status
        if ($pinjam['status'] !== 'pengembalian') {
            return redirect()->back()->with('error', 'Status peminjaman tidak valid untuk pengecekan pengembalian');
        }

        return view('pinjam/return_check', ['pinjam' => $pinjam]);
    }

    // Update status pengembalian
    public function returnUpdate($id)
    {
        $this->mustAdminOrPetugas();

        $pinjamModel = new PinjamModel();
        $barangModel = new BarangModel();

        // Ambil data peminjaman
        $pinjam = $pinjamModel->find($id);
        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data peminjaman tidak ditemukan');
        }

        // Validasi status peminjaman
        if ($pinjam['status'] !== 'pengembalian') {
            return redirect()->back()->with('error', 'Status peminjaman tidak valid untuk pengembalian');
        }

        // Ambil data dari form
        $status = $this->request->getPost('status');
        $kondisiBarang = $this->request->getPost('kondisi_barang');
        $keteranganRusak = $this->request->getPost('keterangan_rusak');

        // Validasi input
        if (!$status || $status !== 'dikembalikan') {
            return redirect()->back()->with('error', 'Status harus dikembalikan');
        }

        if (!$kondisiBarang || !in_array($kondisiBarang, ['baik', 'rusak'])) {
            return redirect()->back()->with('error', 'Kondisi barang harus dipilih');
        }

        if ($kondisiBarang === 'rusak' && !$keteranganRusak) {
            return redirect()->back()->with('error', 'Keterangan kerusakan harus diisi');
        }

        // Data pendukung log
        $namaPeminjam = getNamaUser($pinjam['id_user']);
        $barang = $barangModel->find($pinjam['id_barang']);
        $kodeBarang = $barang['kode_barang'] ?? $pinjam['id_barang'];

        // Update data peminjaman
        $pinjamModel->update($id, [
            'status'                 => $status,
            'tgl_disetujui_kembali'  => date('Y-m-d H:i:s'),
            'kondisi_barang'         => $kondisiBarang,
            'keterangan_kondisi'     => $kondisiBarang === 'rusak' ? $keteranganRusak : null
        ]);

        // Update status barang berdasarkan kondisi
        if ($kondisiBarang === 'rusak') {
            $barangModel->update($pinjam['id_barang'], [
                'status'     => 'tidak tersedia',
                'keterangan' => $keteranganRusak
            ]);

            log_activity(
                'Menyetujui pengembalian barang RUSAK ' . $namaPeminjam . ' - ' . $kodeBarang . 
                '||Keterangan: ' . $keteranganRusak,
                'pinjam',
                $id
            );
        } else {
            $barangModel->update($pinjam['id_barang'], ['status' => 'tersedia']);

            log_activity(
                'Menyetujui pengembalian barang ' . $namaPeminjam . ' - ' . $kodeBarang,
                'pinjam',
                $id
            );
        }

        return redirect()->to('/pinjam')->with('success', 'Pengembalian berhasil diproses');
    }

    // TRASH MANAGEMENT (ADMIN ONLY)
    public function trash()
    {   
        $this->mustAdmin();

        $pinjamModel = new PinjamModel();

        // Ambil filter dari query string
        $filters = [
            'keyword'               => $this->request->getGet('keyword'),
            'status'                => $this->request->getGet('status'),
            'tgl_pengajuan'         => $this->request->getGet('tgl_pengajuan'),
            'tgl_disetujui_kembali' => $this->request->getGet('tgl_disetujui_kembali'),
        ];

        // Ambil data soft deleted
        $pinjamModel->onlyDeleted();

        // Terapkan filter jika ada
        if (array_filter($filters)) {
            $pinjamModel->filterPinjam($filters);
        } else {
            $pinjamModel->orderBy('pinjam.deleted_at', 'DESC');
        }

        // Siapkan data untuk view
        $data = [
            'pinjam'  => $pinjamModel->paginate(10, 'trash'),
            'pager'   => $pinjamModel->pager,
            'filters' => $filters,
        ];

        return view('pinjam/trash', $data);
    }

    // RESTORE PEMINJAMAN


    // FORCE DELETE PEMINJAMAN

    // CETAK DETAIL PEMINJAMAN (PETUGAS ONLY)
    public function cetakDetail($id)
    {
        // Pastikan hanya petugas yang bisa cetak
        if (session('role') !== 'petugas') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $pinjamModel = new PinjamModel();

        // Ambil data peminjaman dengan relasi
        $pinjam = $pinjamModel->getPinjamWithRelasiById($id);

        // Cek data ada tidak
        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data peminjaman tidak ditemukan');
        }

        // Log activity
        log_activity(
            'Mencetak detail peminjaman ' . $pinjam['kode_barang'],
            'pinjam',
            $id
        );

        // Tampilkan view cetak
        return view('pinjam/cetak_detail', [
            'pinjam' => $pinjam
        ]);
    }

    // Export data peminjaman ke Excel
    public function exportExcel()
    {
        // Pastikan admin atau petugas
        $this->mustAdminOrPetugas();

        $pinjamModel = new PinjamModel();

        // Ambil parameter filter
        $filters = [
            'keyword' => $this->request->getGet('keyword'),
            'status' => $this->request->getGet('status'),
            'tgl_pengajuan' => $this->request->getGet('tgl_pengajuan'),
            'tgl_disetujui_kembali' => $this->request->getGet('tgl_disetujui_kembali')
        ];

        // Ambil data peminjaman dengan filter (sudah include join dari initialize())
        $pinjam = $pinjamModel->filterPinjam($filters)->findAll();

        // Load helper
        helper(['excel', 'pinjam']);

        // Siapkan headers
        $headers = [
            'No',
            'Peminjam',
            'Email',
            'Barang',
            'Kode Barang',
            'Tgl Pengajuan',
            'Tgl Disetujui',
            'Durasi (Hari)',
            'Jatuh Tempo',
            'Tgl Dikembalikan',
            'Status',
            'Keterlambatan'
        ];

        // Siapkan data
        $data = [];
        $no = 1;
        foreach ($pinjam as $p) {
            $status = strtolower(trim($p['status']));
            
            // Hitung keterlambatan
            $keterlambatan = '-';
            if ($status === 'dikembalikan' && $p['tgl_jatuh_tempo']) {
                if (isLate($p['tgl_jatuh_tempo'], $status, $p['tgl_disetujui_kembali'])) {
                    $hari = hitungHariTerlambat($p['tgl_jatuh_tempo'], $status, $p['tgl_disetujui_kembali']);
                    $keterlambatan = "Terlambat {$hari} hari";
                } else {
                    $keterlambatan = 'Tepat Waktu';
                }
            } elseif ($status === 'disetujui' && $p['tgl_jatuh_tempo']) {
                if (isLate($p['tgl_jatuh_tempo'], $status)) {
                    $hari = hitungHariTerlambat($p['tgl_jatuh_tempo'], $status);
                    $keterlambatan = "Terlambat {$hari} hari";
                } else {
                    $keterlambatan = sisaWaktu($p['tgl_jatuh_tempo'], $status);
                }
            }

            $data[] = [
                $no++,
                $p['nama'] ?? explode('@', $p['email'])[0],
                $p['email'],
                $p['nama_kategori'] . ' - ' . $p['merek_barang'] . ' - ' . $p['tipe_barang'],
                $p['kode_barang'],
                $p['tgl_pengajuan'] ? date('d-m-Y H:i', strtotime($p['tgl_pengajuan'])) : '-',
                $p['tgl_disetujui'] ? date('d-m-Y H:i', strtotime($p['tgl_disetujui'])) : '-',
                $p['durasi_pinjam'] ?? '-',
                $p['tgl_jatuh_tempo'] ? date('d-m-Y H:i', strtotime($p['tgl_jatuh_tempo'])) : '-',
                $p['tgl_disetujui_kembali'] ? date('d-m-Y H:i', strtotime($p['tgl_disetujui_kembali'])) : '-',
                ucfirst($p['status']),
                $keterlambatan
            ];
        }

        // Generate filename
        $filename = 'Data_Peminjaman_' . date('Y-m-d_His') . '.xlsx';
        $title = 'DATA PEMINJAMAN BARANG';

        // Log activity
        log_activity('Export data peminjaman ke Excel', 'pinjam', 0);

        // Export
        exportToExcel($data, $headers, $filename, $title);
    }


}
