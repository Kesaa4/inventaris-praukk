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
        $barangModel->getBarangFiltered($keyword, $kategori);

        // PAGINATE DARI barangModel
        // ->withDeleted() ini buat nampilin dengan barang yang udah dihapus
        $data = [
            'title'     => 'Data Barang',
            'barang'    => $barangModel->paginate(10, 'barang'),
            'pager'     => $barangModel->pager,
            'kategori'  => $kategoriModel->findAll(),
            'keyword'   => $keyword,
            'catFilter' => $kategori,
        ];

        return view('barang/index', $data);
    }

    private function mustAdmin()
    {
        if (session()->get('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }
    }

    public function create()
    {
        $this->mustAdmin();

        $kategoriModel = new KategoriModel();
        $barangModel = new BarangModel();

        return view('barang/create', [
            'kategori' => $kategoriModel->findAll(),
            'jenis_barang' => $barangModel->getEnumJenisBarang()
        ]);
    }

    public function store()
    {
        $this->mustAdmin();

        $barangModel = new BarangModel();

        $id = $barangModel->insert([
            'jenis_barang'  => $this->request->getPost('jenis_barang'),
            'merek_barang'  => $this->request->getPost('merek_barang'),
            'tipe_barang'   => $this->request->getPost('tipe_barang'),
            'kode_barang'   => $this->request->getPost('kode_barang'),
            'ram'           => $this->request->getPost('ram'),
            'rom'           => $this->request->getPost('rom'),
            'status'        => $this->request->getPost('status'),
            'keterangan'    => $this->request->getPost('keterangan'),
            'id_kategori'   => $this->request->getPost('id_kategori')
        ]);

        log_activity(
            'Menambah barang',
            'barang',
            $id
        );

        return redirect()->to('/barang')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit($id)
    {
        $this->mustAdmin();

        $barangModel = new BarangModel();
        $kategoriModel = new KategoriModel();

        return view('barang/edit', [
            'barang'    => $barangModel->find($id),
            'kategori'  => $kategoriModel->findAll()
        ]);
    }

    public function update($id)
    {
        $this->mustAdmin();

        $barangModel = new BarangModel();

        $barangModel->update($id, [
            'jenis_barang'  => $this->request->getPost('jenis_barang'),
            'id_kategori'   => $this->request->getPost('id_kategori'),
            'merek_barang'  => $this->request->getPost('merek_barang'),
            'tipe_barang'   => $this->request->getPost('tipe_barang'),
            'kode_barang'   => $this->request->getPost('kode_barang'),
            'ram'           => $this->request->getPost('ram'),
            'rom'           => $this->request->getPost('rom'),
            'status'        => $this->request->getPost('status'),
            'keterangan'    => $this->request->getPost('keterangan')
        ]);

        log_activity(
            'Mengedit barang',
            'barang',
            $id
        );

        return redirect()->to('/barang')->with('success', 'Data berhasil diedit.');
    }

    public function delete($id)
    {
        $this->mustAdmin();

        $barangModel = new BarangModel();
        $barangModel->delete($id);

        log_activity(
            'Menghapus barang',
            'barang',
            $id
        );

        return redirect()->to('/barang')->with('success', 'Data berhasil dihapus.');
    }

    public function trash()
    {
        $this->mustAdmin();

        $barangModel = new BarangModel();

        $keyword      = $this->request->getGet('keyword');
        $deletedDate  = $this->request->getGet('deleted_date');

        $builder = $barangModel->onlyDeleted();

        // keyword search
        if ($keyword) {
            $builder->groupStart()
                ->like('jenis_barang', $keyword)
                ->orLike('merek_barang', $keyword)
                ->orLike('tipe_barang', $keyword)
                ->orLike('kode_barang', $keyword)
                ->groupEnd();
        }

        // filter tanggal hapus
        if ($deletedDate) {
            $builder->where('DATE(deleted_at)', $deletedDate);
        }

        $data = [
            'barang'      => $builder->paginate(10, 'trash'),
            'pager'       => $barangModel->pager,
            'keyword'     => $keyword,
            'deletedDate' => $deletedDate
        ];

        return view('barang/trash', $data);
    }

    public function restore($id)
    {
        $this->mustAdmin();

        $barangModel = new BarangModel();

        $barangModel
            ->withDeleted()
            ->where('id_barang', $id)
            ->set('deleted_at', null)
            ->update();

        log_activity(
            'Restore barang',
            'barang',
            $id
        );

        return redirect()->back()
            ->with('success', 'Barang berhasil direstore');
    }

    public function forceDelete($id)
    {
        $this->mustAdmin();

        $barangModel = new BarangModel();

        $barangModel->delete($id, true);

        log_activity(
            'Hapus permanen barang',
            'barang',
            $id
        );

        return redirect()->back()
            ->with('success', 'Barang dihapus permanen');
    }

}
