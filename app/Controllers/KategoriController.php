<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\KategoriModel;
use App\Models\BarangModel;

class KategoriController extends BaseController
{
    // Models
    protected $kategoriModel;
    protected $barangModel;

    // Konstruktor untuk inisialisasi model
    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
        $this->barangModel = new BarangModel();
    }

    // List semua kategori
    public function index()
    {
        // Siapkan data untuk view
        $data = [
            'title' => 'Kategori Barang',
            'kategori_list' => $this->kategoriModel->getAllKategoriWithCount()
        ];

        // Tampilkan view dengan data
        return view('kategori/index', $data);
    }

    // Tampilkan barang berdasarkan kategori
    public function show($id_kategori)
    {
        // Ambil kategori berdasarkan ID
        $kategori = $this->kategoriModel->find($id_kategori);
        if (!$kategori) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
        }

        // Ambil barang berdasarkan kategori dengan pagination
        $barangList = $this->barangModel
            ->where('id_kategori', $id_kategori)
            ->paginate(10, 'barang');

        // Siapkan data untuk view
        $pager = $this->barangModel->pager;
        $currentPage = $this->request->getVar('page_barang') ?? 1;
        $perPage = $pager->getPerPage('barang');

        // Siapkan data untuk view
        $data = [
            'title' => 'Barang: ' . $kategori['kategori_kondisi'],
            'kategori' => $kategori,
            'barang_list' => $barangList,
            'pager' => $pager,
            'currentPage' => $currentPage,
            'perPage' => $perPage
        ];

        // Tampilkan view dengan data
        return view('kategori/show', $data);
    }

}
