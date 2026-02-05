<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PinjamModel;
use App\Models\BarangModel;
use App\Models\UserProfileModel;

helper('user');

class PinjamController extends BaseController
{
    protected function mustLogin()
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/');
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

    protected function mustAdminPetugasPeminjam()
    {
        if (!in_array(session('role'), ['admin','petugas','peminjam'])) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }
    }

    // LIST PEMINJAMAN
    
    public function index()
    {
        $this->mustLogin();

        $pinjamModel = new PinjamModel();

        $filters = [
            'keyword'     => $this->request->getGet('keyword'),
            'status'      => $this->request->getGet('status'),
            'tgl_pinjam'  => $this->request->getGet('tgl_pinjam'),
            'tgl_kembali' => $this->request->getGet('tgl_kembali'),
        ];

        $isFilter = array_filter($filters);

        if ($isFilter) {

            if (session('role') === 'peminjam') {
                $pinjamModel->filterPinjam($filters, session('id_user'));
            } else {
                $pinjamModel->filterPinjam($filters);
            }

        } else {

            if (session('role') === 'peminjam') {
                $pinjamModel->getPinjamWithRelasi(session('id_user'));
            } else {
                $pinjamModel->getPinjamWithRelasi();
            }
        }

        $data = [
            'pinjam' => $pinjamModel->paginate(10, 'pinjam'),
            'pager'  => $pinjamModel->pager,
            'filters'=> $filters,
        ];

        return view('pinjam/index', $data);
    }

    // FORM AJUKAN PINJAM
    
    public function create()
    {
        $this->mustAdminPetugasPeminjam();

        $barangModel = new BarangModel();
        $userModel   = new \App\Models\UserModel();

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

        return view('pinjam/create', $data);
    }

    // SIMPN PINJAMAN
    
    public function store()
    {
        $this->mustAdminPetugasPeminjam();

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
            }
        } else {
            $idUserPeminjam = session('id_user');
        }

        $idBarang = $this->request->getPost('id_barang');

        $id = $pinjamModel->insert([
            'id_barang'  => $idBarang,
            'id_user'    => $idUserPeminjam,      // PEMINJAM
            'created_by' => session('id_user'),   // YANG INPUT
            'tgl_pinjam' => date('Y-m-d'),
            'status'     => 'menunggu'
        ]);

        $namaPeminjam = getNamaUser($idUserPeminjam);

        log_activity(
            'Menambahkan peminjaman untuk ' .$namaPeminjam,
            'pinjam',
            $id
        );

        $barangModel->update($idBarang, [
            'status' => 'dibooking'
        ]);

        return redirect()->to('/pinjam')->with('success', 'Pengajuan berhasil');
    }

    // EDIT STATUS (ADMIN / PETUGAS)

    public function edit($id)
    {
        $this->mustAdminOrPetugas();

        $pinjamModel = new PinjamModel();

        $pinjam = $pinjamModel->getPinjamWithRelasiById($id);

        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data peminjaman tidak ditemukan');
        }

        return view('pinjam/edit', [
            'pinjam' => $pinjam
        ]);
    }

    public function update($id)
    {
        $this->mustAdminOrPetugas();

        $pinjamModel = new PinjamModel();
        $barangModel = new BarangModel();

        $pinjam = $pinjamModel->find($id);
        if (!$pinjam) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $status = $this->request->getPost('status');
        $data = ['status' => $status];

        if ($status === 'disetujui') {
            $data['approved_at'] = date('Y-m-d H:i:s');
            $data['approved_by'] = session('id_user');

            $barangModel->update($pinjam['id_barang'], [
                'status' => 'dipinjam'
            ]);

            $namaPeminjam = getNamaUser($pinjam['id_user']);

            log_activity(
                'Menyetujui peminjaman' .$namaPeminjam,
                'pinjam',
                $id
            );
        }

        elseif ($status === 'ditolak') {
            $barangModel->update($pinjam['id_barang'], [
                'status' => 'tersedia'
            ]);

            log_activity(
                'Menolak peminjaman' .$namaPeminjam,
                'pinjam',
                $id
            );
        }

        elseif ($status === 'dikembalikan') {
            $barangModel->update($pinjam['id_barang'], [
                'status' => 'tersedia'
            ]);

            log_activity(
                'Menyetujui pengembalian barang' .$namaPeminjam,
                'pinjam',
                $id
            );
        }

        $pinjamModel->update($id, $data);

        return redirect()->to('/pinjam')->with('success', 'Status berhasil diubah');
    }

    // DELETE (ADMIN ONLY)

    public function delete($id)
    {
        if (session('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $pinjamModel = new PinjamModel();
        $pinjamModel->delete($id);

        log_activity(
            'Menghapus data peminjaman',
            'pinjam',
            $id
        );


        return redirect()->to('/pinjam');
    }

    //pengembalian barang oleh peminjam
    public function requestReturn($id)
    {
        if (session('role') !== 'peminjam') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $pinjamModel = new PinjamModel();

        $pinjamModel->update($id, [
            'status' => 'pengembalian'
        ]);

        log_activity(
            'Mengajukan pengembalian barang',
            'pinjam',
            $id
        );

        return redirect()->to('/pinjam')->with('success', 'Pengembalian diajukan');
    }

    //view pengecekan oleh petugas dan admin
    public function returnCheck($id)
    {
        $this->mustAdminOrPetugas();

        $pinjamModel = new PinjamModel();

        return view('pinjam/return_check', [
            'pinjam' => $pinjamModel->getPinjamWithRelasiById($id)
        ]);
    }

    //update status + barang jadi tersedia
    public function returnUpdate($id)
    {
        $this->mustAdminOrPetugas();

        $pinjamModel = new PinjamModel();
        $barangModel = new BarangModel();

        $pinjam = $pinjamModel->find($id);
        $status = $this->request->getPost('status');

        // update pinjam
        $pinjamModel->update($id, [
            'status' => $status,
            'tgl_kembali' => date('Y-m-d')
        ]);

        // update barang
        if ($status === 'dikembalikan') {
            $barangModel->update($pinjam['id_barang'], [
                'status' => 'tersedia'
            ]);

            log_activity(
                'Menyetujui pengembalian barang' .$namaPeminjam,
                'pinjam',
                $id
            );
        }

        return redirect()->to('/pinjam')->with('success', 'Pengembalian diproses');
    }

}
