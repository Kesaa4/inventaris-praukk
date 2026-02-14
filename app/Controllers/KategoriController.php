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

    public function index()
    {
        $keyword = $this->request->getGet('search');
        
        $kategori_list = $keyword 
            ? $this->kategoriModel->searchKategori($keyword)
            : $this->kategoriModel->getAllKategoriWithConditionCount();

        $data = [
            'title' => 'Kategori Barang',
            'kategori_list' => $kategori_list,
            'keyword' => $keyword
        ];

        return view('kategori/index', $data);
    }

    // Form create kategori
    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori',
            'validation' => \Config\Services::validation()
        ];

        return view('kategori/create', $data);
    }

    // Proses simpan kategori baru
    public function store()
    {
        // Validasi input
        if (!$this->validate([
            'nama_kategori' => 'required|min_length[3]|max_length[100]|is_unique[kategori.nama_kategori]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan data
        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori')
        ];

        if ($this->kategoriModel->insert($data)) {
            log_activity('Menambah kategori: ' . $data['nama_kategori']);
            return redirect()->to('/kategori')->with('success', 'Kategori berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kategori');
    }

    // Form edit kategori
    public function edit($id_kategori)
    {
        $kategori = $this->kategoriModel->find($id_kategori);
        
        if (!$kategori) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Kategori',
            'kategori' => $kategori,
            'validation' => \Config\Services::validation()
        ];

        return view('kategori/edit', $data);
    }

    // Proses update kategori
    public function update($id_kategori)
    {
        $kategori = $this->kategoriModel->find($id_kategori);
        
        if (!$kategori) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
        }

        // Validasi input
        $rules = [
            'nama_kategori' => "required|min_length[3]|max_length[100]|is_unique[kategori.nama_kategori,id_kategori,{$id_kategori}]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update data
        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori')
        ];

        if ($this->kategoriModel->update($id_kategori, $data)) {
            log_activity('Mengubah kategori: ' . $data['nama_kategori']);
            return redirect()->to('/kategori')->with('success', 'Kategori berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kategori');
    }

    // Hapus kategori (soft delete)
    public function delete($id_kategori)
    {
        $kategori = $this->kategoriModel->find($id_kategori);
        
        if (!$kategori) {
            return redirect()->to('/kategori')->with('error', 'Kategori tidak ditemukan');
        }

        // Cek apakah kategori memiliki barang
        if ($this->kategoriModel->hasBarang($id_kategori)) {
            return redirect()->to('/kategori')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki barang');
        }

        if ($this->kategoriModel->delete($id_kategori)) {
            log_activity('Menghapus kategori: ' . $kategori['nama_kategori']);
            return redirect()->to('/kategori')->with('success', 'Kategori berhasil dihapus');
        }

        return redirect()->to('/kategori')->with('error', 'Gagal menghapus kategori');
    }

    // Export kategori ke Excel
    public function export()
    {
        helper('excel');
        
        $kategori_list = $this->kategoriModel->getAllKategoriWithConditionCount();
        
        $headers = ['Nama Kategori', 'Total Barang', 'Baik', 'Rusak Ringan', 'Rusak Berat'];
        $data = [];
        
        foreach ($kategori_list as $k) {
            $data[] = [
                $k['nama_kategori'],
                $k['jumlah_barang'] ?? 0,
                $k['baik'] ?? 0,
                $k['rusak_ringan'] ?? 0,
                $k['rusak_berat'] ?? 0
            ];
        }
        
        return exportToExcel($data, $headers, 'Kategori_' . date('Y-m-d') . '.xlsx', 'Data Kategori Barang');
    }

    // Tampilkan barang berdasarkan kategori
    public function show($id_kategori)
    {
        // Ambil kategori berdasarkan ID
        $kategori = $this->kategoriModel->find($id_kategori);
        if (!$kategori) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
        }

        // Filter kondisi
        $kondisiFilter = $this->request->getGet('kondisi');

        // Query builder untuk barang
        $builder = $this->barangModel
            ->select('barang.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = barang.id_kategori', 'left')
            ->where('barang.id_kategori', $id_kategori);

        // Terapkan filter kondisi jika ada
        if ($kondisiFilter && $kondisiFilter !== 'semua') {
            $builder->where('barang.kondisi', $kondisiFilter);
        }

        // Ambil data dengan pagination
        $barangList = $builder->paginate(10, 'barang');

        // Siapkan data untuk view
        $pager = $this->barangModel->pager;
        $currentPage = $this->request->getVar('page_barang') ?? 1;
        $perPage = $pager->getPerPage('barang');

        // Siapkan data untuk view
        $data = [
            'title' => 'Barang: ' . $kategori['nama_kategori'],
            'kategori' => $kategori,
            'barang_list' => $barangList,
            'pager' => $pager,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'kondisiFilter' => $kondisiFilter
        ];

        // Tampilkan view dengan data
        return view('kategori/show', $data);
    }

}
