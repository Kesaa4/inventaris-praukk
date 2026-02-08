<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\KategoriModel;
use App\Models\BarangModel;

class KategoriController extends BaseController
{
    protected $kategoriModel;
    protected $barangModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
        $this->barangModel = new BarangModel();
    }

    // List semua kategori
    public function index()
    {
        $data = [
            'title' => 'Kategori Barang',
            'kategori_list' => $this->kategoriModel->getAllKategori()
        ];

        return view('kategori/index', $data);
    }

    // Tampilkan barang berdasarkan kategori
    public function show($id_kategori)
    {
        $kategori = $this->kategoriModel->find($id_kategori);
        if (!$kategori) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
        }

        $barangList = $this->barangModel
            ->where('id_kategori', $id_kategori)
            ->paginate(10, 'barang');

        $pager = $this->barangModel->pager;
        $currentPage = $this->request->getVar('page_barang') ?? 1;
        $perPage = $pager->getPerPage('barang');

        $data = [
            'title' => 'Barang: ' . $kategori['kategori_kondisi'],
            'kategori' => $kategori,
            'barang_list' => $barangList,
            'pager' => $pager,
            'currentPage' => $currentPage,
            'perPage' => $perPage
        ];

        return view('kategori/show', $data);
    }

}
