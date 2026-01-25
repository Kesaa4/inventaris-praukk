<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BarangModel;
use App\Models\KategoriModel;

class BarangController extends BaseController
{
    public function index()
    {
        $barangModel   = new BarangModel();
        $kategoriModel = new KategoriModel();

        $keyword  = $this->request->getGet('keyword');
        $kategori = $this->request->getGet('kategori');

        // AMBIL barangModel FILTER
        $barangModel = $barangModel->getBarangFiltered($keyword, $kategori);

        // PAGINATE DARI barangModel
        // ->withDeleted() ini buat nampilin dengan barang yang udah dihapus
        $data = [
            'title'     => 'Dashboard Barang',
            'barang'    => $barangModel->paginate(10, 'barang'),
            'pager'     => $barangModel->pager,
            'kategori'  => $kategoriModel->findAll(),
            'keyword'   => $keyword,
            'catFilter' => $kategori
        ];

        return view('dashboard/index', $data);
    }

    public function create()
    {
        $kategoriModel = new KategoriModel();
        $barangModel = new BarangModel();

        return view('dashboard/create', [
            'kategori' => $kategoriModel->findAll(),
            'jenis_barang' => $barangModel->getEnumJenisBarang()
        ]);
    }

    public function store()
    {
        $barangModel = new BarangModel();

        $barangModel->insert([
            'jenis_barang'  => $this->request->getPost('jenis_barang'),
            'merek_barang'  => $this->request->getPost('merek_barang'),
            'tipe_barang'   => $this->request->getPost('tipe_barang'),
            'ram'           => $this->request->getPost('ram'),
            'rom'           => $this->request->getPost('rom'),
            'status'        => $this->request->getPost('status'),
            'keterangan'    => $this->request->getPost('keterangan'),
            'id_kategori'   => $this->request->getPost('id_kategori')
        ]);

        return redirect()->to('/dashboard')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit($id)
    {
        $barangModel = new BarangModel();
        $kategoriModel = new KategoriModel();

        return view('dashboard/edit', [
            'barang'    => $barangModel->find($id),
            'kategori'  => $kategoriModel->findAll()
        ]);
    }

    public function update($id)
    {
        $barangModel = new BarangModel();

        $barangModel->update($id, [
            'jenis_barang'  => $this->request->getPost('jenis_barang'),
            'id_kategori'   => $this->request->getPost('id_kategori'),
            'merek_barang'  => $this->request->getPost('merek_barang'),
            'tipe_barang'   => $this->request->getPost('tipe_barang'),
            'ram'           => $this->request->getPost('ram'),
            'rom'           => $this->request->getPost('rom'),
            'status'        => $this->request->getPost('status'),
            'keterangan'    => $this->request->getPost('keterangan')
        ]);

        return redirect()->to('/dashboard')->with('success', 'Data berhasil diedit.');
    }

    public function delete($id)
    {
        $barangModel = new BarangModel();
        $barangModel->delete($id);

        return redirect()->to('/dashboard')->with('success', 'Data berhasil dihapus.');
    }

}
