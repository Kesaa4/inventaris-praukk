<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PinjamModel;
use App\Models\BarangModel;

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

    // LIST PEMINJAMAN
    
    public function index()
    {
        $this->mustLogin();

        $pinjamModel = new PinjamModel();

        if (session('role') === 'peminjam') {
            $data['pinjam'] = $pinjamModel->getPinjamWithRelasi(session('id_user'));
        } else {
            $data['pinjam'] = $pinjamModel->getPinjamWithRelasi();
        }
        // dd($data['pinjam']);

        return view('pinjam/index', $data);
    }

    // FORM AJUKAN PINJAM (PEMINJAM)
    
    public function create()
    {
        if (session('role') !== 'peminjam') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $barangModel = new BarangModel();

        return view('pinjam/create', [
            'barang' => $barangModel
                ->where('status', 'tersedia')
                ->findAll()
        ]);
    }

    // SIMPAN PINJAMAN
    
    public function store()
    {
        if (session('role') !== 'peminjam') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $pinjamModel = new PinjamModel();
        $barangModel = new BarangModel();

        $idBarang = $this->request->getPost('id_barang');

        // SIMPAN PINJAM
        $pinjamModel->insert([
            'id_barang'   => $this->request->getPost('id_barang'),
            'id_user'     => session('id_user'),
            'tgl_pinjam'  => date('Y-m-d'),
            'status'      => 'menunggu'
        ]);

        // KUNCI BARANG
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
        }

        if ($status === 'ditolak') {
            $barangModel->update($pinjam['id_barang'], [
                'status' => 'tersedia'
            ]);
        }

        if ($status === 'dikembalikan') {
            $barangModel->update($pinjam['id_barang'], [
                'status' => 'tersedia'
            ]);
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
        ]);

        // update barang
        if ($status === 'dikembalikan') {
            $barangModel->update($pinjam['id_barang'], [
                'status' => 'tersedia'
            ]);
        }

        return redirect()->to('/pinjam')->with('success', 'Pengembalian diproses');
    }

    

}
