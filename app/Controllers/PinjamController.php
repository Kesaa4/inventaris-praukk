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
        // Cek apakah user sudah login
        if (!session('isLoggedIn')) {
            return redirect()->to('/');
        }
    }

    // ADMIN ONLY
    protected function mustAdmin()
    {
        if (session('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }
    }

    // ADMIN ATAU PETUGAS ONLY
    protected function mustAdminOrPetugas()
    {
        if (!in_array(session('role'), ['admin', 'petugas'])) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }
    }
    
    // PEMINJAM ATAU PETUGAS ONLY
    protected function mustPeminjamOrPetugas()
    {
        if (!in_array(session('role'), ['peminjam', 'petugas'])) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }
    }

    // LIST PEMINJAMAN
    public function index()
    {
        // Pastikan user sudah login
        $this->mustLogin();

        // Inisialisasi model
        $pinjamModel = new PinjamModel();

        // Ambil filter dari query string
        $filters = [
            'keyword'     => $this->request->getGet('keyword'),
            'status'      => $this->request->getGet('status'),
            'tgl_pengajuan'  => $this->request->getGet('tgl_pengajuan'),
            'tgl_disetujui_kembali' => $this->request->getGet('tgl_disetujui_kembali'),
        ];

        // Cek ada tidaknya filter
        $isFilter = array_filter($filters);

        // PANGGIL FILTER DARI MODEL JIKA ADA
        if ($isFilter) {
            // Kalau peminjam, batasi hanya miliknya
            if (session('role') === 'peminjam') {
                $pinjamModel->filterPinjam($filters, session('id_user'));
            } // Kalau admin/petugas, semua data
            else {
                $pinjamModel->filterPinjam($filters);
            }
        // Kalau tidak ada filter
        } else {
            // Kalau peminjam, batasi hanya miliknya
            if (session('role') === 'peminjam') {
                $pinjamModel->getPinjamWithRelasi(session('id_user'));
            } // Kalau admin/petugas, semua data
            else {
                $pinjamModel->getPinjamWithRelasi();
            }
        }

        // Siapkan data untuk view
        $data = [
            'pinjam' => $pinjamModel->paginate(10, 'pinjam'),
            'pager'  => $pinjamModel->pager,
            'filters'=> $filters,
        ];

        // Tampilkan view dengan data
        return view('pinjam/index', $data);
    }

    // FORM AJUKAN PINJAM
    public function create()
    {
        $this->mustLogin();

        $barangModel = new BarangModel();
        $userModel   = new UserModel();

        // Siapkan data untuk view
        $data = [
            'barang' => $barangModel
                ->where('status', 'tersedia')
                ->findAll()
        ];

        // kalau petugas/admin, kirim data user
        if (in_array(session('role'), ['admin', 'petugas'])) {
            $data['users'] = $userModel
                ->where('role', 'peminjam')
                ->findAll();
        }

        // Tampilkan view dengan data
        return view('pinjam/create', $data);
    }

    // SIMPAN PINJAMAN
    public function store()
    {
        $this->mustLogin();

        $pinjamModel        = new PinjamModel();
        $barangModel        = new BarangModel();
        $userProfileModel   = new UserProfileModel();

        // USER YANG MEMINJAM
        if (in_array(session('role'), ['admin', 'petugas'])) {
            $idUserPeminjam = $this->request->getPost('id_user');

            // CEGAH PINJAM KE DIRI SENDIRI
            if ($idUserPeminjam == session('id_user')) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak boleh meminjamkan ke diri sendiri');
            } // Hanya boleh pinjam ke peminjam
        } else {
            $idUserPeminjam = session('id_user');
        }

        // BARANG YANG DIPINJAM
        $idBarang = $this->request->getPost('id_barang');

        // Ambil kode barang untuk log
        $barang = $barangModel->find($idBarang);
        $kodeBarang = $barang['kode_barang'] ?? $idBarang;

        // Simpan data peminjaman status jadi menunggu
        $id = $pinjamModel->insert([
            'id_barang'  => $idBarang,
            'id_user'    => $idUserPeminjam,      // PEMINJAM
            'created_by' => session('id_user'),   // YANG INPUT
            'tgl_pengajuan' => date('Y-m-d H:i:s'),
            'status'     => 'menunggu'
        ]);

        // Log detail
        $namaPeminjam = getNamaUser($idUserPeminjam);

        log_activity(
            'Menambahkan peminjaman untuk ' . $namaPeminjam . ' - ' . $kodeBarang,
            'pinjam',
            $id
        );

        // Update status barang jadi dibooking jika sudah mengajukan pinjam
        $barangModel->update($idBarang, [
            'status' => 'dibooking'
        ]);

        // Redirect dengan pesan sukses
        return redirect()->to('/pinjam')->with('success', 'Pengajuan berhasil');
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
        // Pastikan admin atau petugas
        $this->mustAdminOrPetugas();

        $pinjamModel = new PinjamModel();
        $barangModel = new BarangModel();

        // Ambil data peminjaman dulu
        $pinjam = $pinjamModel->find($id);

        // Cek data ada tidak
        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Ambil data pendukung untuk log
        $namaPeminjam = getNamaUser($pinjam['id_user']);
        $barang = $barangModel->find($pinjam['id_barang']);
        $kodeBarang = $barang['kode_barang'] ?? $pinjam['id_barang'];

        // Ambil status dari form
        $status = $this->request->getPost('status');
        $data = [
            'status' => $status,
            'alasan_ditolak' => null
        ];

        // Jika disetujui maka 
        if ($status === 'disetujui') {
            // Ambil durasi dari form atau default 7 hari
            $durasi = $this->request->getPost('durasi_pinjam') ?: 7;
            $durasi = max(1, min(30, (int)$durasi)); // Batasi 1-30 hari
            
            // Hitung tanggal jatuh tempo
            $tglJatuhTempo = date('Y-m-d H:i:s', strtotime("+{$durasi} days"));
            
            $data['tgl_disetujui'] = date('Y-m-d H:i:s');
            $data['tgl_jatuh_tempo'] = $tglJatuhTempo;
            $data['durasi_pinjam'] = $durasi;
            $data['approved_at'] = date('Y-m-d H:i:s');
            $data['approved_by'] = session('id_user');

            // Update status barang jadi dipinjam
            $barangModel->update($pinjam['id_barang'], [
                'status' => 'dipinjam'
            ]);

            log_activity(
                'Menyetujui peminjaman ' . $namaPeminjam . ' - ' . $kodeBarang . 
                '||Durasi: ' . $durasi . ' hari;Jatuh tempo: ' . date('d-m-Y', strtotime($tglJatuhTempo)),
                'pinjam',
                $id
            );
        }

        // Jika ditolak
        elseif ($status === 'ditolak') {
            // Tambah alasan ditolak
            $data['alasan_ditolak'] = $this->request->getPost('alasan_ditolak');
            // Update status barang jadi tersedia lagi jika ditolak
            $barangModel->update($pinjam['id_barang'], [
                'status' => 'tersedia'
            ]);
            
            log_activity(
                'Menolak peminjaman ' . $namaPeminjam . 
                '||Barang: ' . $kodeBarang .
                ';Alasan: ' . $data['alasan_ditolak'],
                'pinjam',
                $id
            );
        }
        // Jika dikembalikan status menjadi tersedia lagi
        elseif ($status === 'dikembalikan') {
            $barangModel->update($pinjam['id_barang'], [
                'status' => 'tersedia'
            ]);

            log_activity(
                'Menyetujui pengembalian barang ' . $namaPeminjam . ' - ' . $kodeBarang,
                'pinjam',
                $id
            );
        }

        // Update data peminjaman
        $pinjamModel->update($id, $data);

        return redirect()->to('/pinjam')->with('success', 'Status berhasil diubah');
    }

    // DELETE (ADMIN ONLY)
    public function delete($id)
    {
        // Pastikan admin
        $this->mustAdmin();

        $pinjamModel = new PinjamModel();
        $barangModel = new BarangModel();

        // Ambil data pinjam dulu
        $pinjam = $pinjamModel->find($id);
        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Data pendukung log
        $namaPeminjam = getNamaUser($pinjam['id_user']);

        // Ambil kode barang
        $barang = $barangModel->find($pinjam['id_barang']);
        $kodeBarang = $barang['kode_barang'] ?? $pinjam['id_barang'];

        // Hapus data
        $pinjamModel->delete($id);

        // Log detail
        log_activity(
            'Menghapus peminjaman ' . $namaPeminjam . ' - ' . $kodeBarang,
            'pinjam',
            $id
        );

        return redirect()->to('/pinjam');
    }

    //pengembalian barang oleh peminjam
    public function requestReturn($id)
    {
        // Pastikan peminjam
        if (session('role') !== 'peminjam') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $pinjamModel = new PinjamModel();
        $barangModel = new BarangModel();

        // Ambil data pinjam dulu
        $pinjam = $pinjamModel->find($id);
        // Cek data ada tidak
        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Data pendukung log
        $namaPeminjam = getNamaUser($pinjam['id_user']);
        $barang = $barangModel->find($pinjam['id_barang']);
        $kodeBarang = $barang['kode_barang'] ?? $pinjam['id_barang'];

        // update status pinjam jadi pengembalian jika peminjam mengajukan
        $pinjamModel->update($id, [
            'status' => 'pengembalian',
            'tgl_pengajuan_kembali' => date('Y-m-d H:i:s')
        ]);

        log_activity(
            'Mengajukan pengembalian barang ' . $kodeBarang,
            'pinjam',
            $id
        );

        return redirect()->to('/pinjam')->with('success', 'Pengembalian diajukan');
    }

    //view pengecekan oleh petugas dan admin
    public function returnCheck($id)
    {
        // Pastikan admin atau petugas
        $this->mustAdminOrPetugas();

        $pinjamModel = new PinjamModel();

        // Tampilkan view dengan data
        return view('pinjam/return_check', [
            'pinjam' => $pinjamModel->getPinjamWithRelasiById($id)
        ]);
    }

    //update status + barang jadi tersedia
    public function returnUpdate($id)
    {
        // Pastikan admin atau petugas
        $this->mustAdminOrPetugas();

        $pinjamModel = new PinjamModel();
        $barangModel = new BarangModel();

        // Ambil data pinjam dulu
        $pinjam = $pinjamModel->find($id);
        // Cek data ada tidak
        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Data pendukung log
        $namaPeminjam = getNamaUser($pinjam['id_user']);
        $barang = $barangModel->find($pinjam['id_barang']);
        $kodeBarang = $barang['kode_barang'] ?? $pinjam['id_barang'];

        // Ambil data dari form
        $status = $this->request->getPost('status');
        $kondisiBarang = $this->request->getPost('kondisi_barang');
        $keteranganRusak = $this->request->getPost('keterangan_rusak');

        // update tanggal disetujui kembali, status pinjam, dan kondisi barang
        $pinjamModel->update($id, [
            'status' => $status,
            'tgl_disetujui_kembali' => date('Y-m-d H:i:s'),
            'kondisi_barang' => $kondisiBarang,
            'keterangan_kondisi' => $kondisiBarang === 'rusak' ? $keteranganRusak : null
        ]);

        // jika dikembalikan
        if ($status === 'dikembalikan') {
            // Cek kondisi barang
            if ($kondisiBarang === 'rusak') {
                // Jika rusak, status barang jadi tidak tersedia
                $barangModel->update($pinjam['id_barang'], [
                    'status' => 'tidak tersedia',
                    'keterangan' => $keteranganRusak
                ]);

                log_activity(
                    'Menyetujui pengembalian barang RUSAK ' . $namaPeminjam . ' - ' . $kodeBarang . 
                    '||Keterangan: ' . $keteranganRusak,
                    'pinjam',
                    $id
                );
            } else {
                // Jika baik, status barang jadi tersedia
                $barangModel->update($pinjam['id_barang'], [
                    'status' => 'tersedia'
                ]);

                log_activity(
                    'Menyetujui pengembalian barang ' . $namaPeminjam . ' - ' . $kodeBarang,
                    'pinjam',
                    $id
                );
            }
        }

        return redirect()->to('/pinjam')->with('success', 'Pengembalian diproses');
    }

    // TRASH MANAGEMENT (ADMIN ONLY)
    public function trash()
    {   
        $this->mustAdmin();

        $pinjamModel = new PinjamModel();

        // Ambil filter dari query string
        $filters = [
            'keyword'     => $this->request->getGet('keyword'),
            'status'      => $this->request->getGet('status'),
            'tgl_pengajuan'  => $this->request->getGet('tgl_pengajuan'),
            'tgl_disetujui_kembali' => $this->request->getGet('tgl_disetujui_kembali'),
        ];

        // Ambil data soft deleted
        $pinjamModel->onlyDeleted();

        // pakai logic filter YANG SUDAH ADA
        if (array_filter($filters)) {
            $pinjamModel->filterPinjam($filters);
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
    public function restore($id)
    {
        $this->mustAdmin();

        $pinjamModel  = new PinjamModel();
        $barangModel  = new BarangModel();

        // Ambil data dulu (termasuk soft deleted)
        $pinjam = $pinjamModel->withDeleted()->find($id);
        // Cek data ada tidak
        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Data pendukung log
        $namaPeminjam = getNamaUser($pinjam['id_user']);

        // Ambil kode barang
        $barang = $barangModel->find($pinjam['id_barang']);
        $kodeBarang = $barang['kode_barang'] ?? $pinjam['id_barang'];

        // Restore data
        $pinjamModel->withDeleted()
            ->where('id_pinjam', $id)
            ->update(null, [
                'deleted_at' => null
            ]);

        log_activity(
            'Restore peminjaman ' . $namaPeminjam . ' - ' . $kodeBarang,
            'pinjam',
            $id
        );

        return redirect()->back()->with('success', 'Data peminjaman berhasil direstore');
    }


    // FORCE DELETE PEMINJAMAN
    public function forceDelete($id)
    {
        $this->mustAdmin();

        $pinjamModel = new PinjamModel();
        $barangModel = new BarangModel();

        // Ambil data dulu sebelum dihapus permanen
        $pinjam = $pinjamModel->withDeleted()->find($id);
        // Cek data ada tidak
        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Data pendukung log
        $namaPeminjam = getNamaUser($pinjam['id_user']);

        // Ambil kode barang
        $barang = $barangModel->find($pinjam['id_barang']);
        $kodeBarang = $barang['kode_barang'] ?? $pinjam['id_barang'];

        // Hapus permanen
        $pinjamModel->delete($id, true);

        log_activity(
            'Hapus permanen peminjaman ' . $namaPeminjam . ' - ' . $kodeBarang,
            'pinjam',
            $id
        );

        return redirect()->back()->with('success', 'Data peminjaman dihapus permanen');
    }

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
                $p['jenis_barang'] . ' - ' . $p['merek_barang'] . ' - ' . $p['tipe_barang'],
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
