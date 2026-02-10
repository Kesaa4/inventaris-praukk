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
            $data['tgl_disetujui'] = date('Y-m-d H:i:s');
            $data['approved_at'] = date('Y-m-d H:i:s');
            $data['approved_by'] = session('id_user');

            // Update status barang jadi dipinjam
            $barangModel->update($pinjam['id_barang'], [
                'status' => 'dipinjam'
            ]);

            log_activity(
                'Menyetujui peminjaman ' . $namaPeminjam . ' - ' . $kodeBarang,
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

        // Ambil status dari form
        $status = $this->request->getPost('status');

        // update tanggal disetujui kembali dan status pinjam
        $pinjamModel->update($id, [
            'status' => $status,
            'tgl_disetujui_kembali' => date('Y-m-d H:i:s')
        ]);

        // jika dikembalikan status menjadi tersedia lagi
        if ($status === 'dikembalikan') {
            $barangModel->update($pinjam['id_barang'], [
                'status' => 'tersedia'
            ]);

            log_activity(
                'Menyetujui pengembalian barang ' . $namaPeminjam . ' - ' . $kodeBarang,
                'pinjam',
                $id
            );
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

}
