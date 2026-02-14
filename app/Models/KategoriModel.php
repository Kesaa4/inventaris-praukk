<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table            = 'kategori';
    protected $primaryKey       = 'id_kategori';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_kategori',
        'deskripsi',
        'icon'
    ];

    // Ambil semua kategori
    public function getAllKategori()
    {
        return $this->findAll();
    }

    // Ambil semua kategori dengan jumlah barang
    public function getAllKategoriWithCount()
    {
        return $this->select('kategori.*, COUNT(barang.id_barang) as jumlah_barang')
            ->join('barang', 'barang.id_kategori = kategori.id_kategori', 'left')
            ->groupBy('kategori.id_kategori')
            ->findAll();
    }

    // Ambil kategori dengan jumlah barang per kondisi
    public function getAllKategoriWithConditionCount()
    {
        return $this->select('kategori.*, 
            COUNT(barang.id_barang) as jumlah_barang,
            SUM(CASE WHEN barang.kondisi = "baik" THEN 1 ELSE 0 END) as baik,
            SUM(CASE WHEN barang.kondisi = "rusak ringan" THEN 1 ELSE 0 END) as rusak_ringan,
            SUM(CASE WHEN barang.kondisi = "rusak berat" THEN 1 ELSE 0 END) as rusak_berat')
            ->join('barang', 'barang.id_kategori = kategori.id_kategori', 'left')
            ->groupBy('kategori.id_kategori')
            ->findAll();
    }

    // Search kategori
    public function searchKategori($keyword)
    {
        return $this->select('kategori.*, 
            COUNT(barang.id_barang) as jumlah_barang,
            SUM(CASE WHEN barang.kondisi = "baik" THEN 1 ELSE 0 END) as baik,
            SUM(CASE WHEN barang.kondisi = "rusak ringan" THEN 1 ELSE 0 END) as rusak_ringan,
            SUM(CASE WHEN barang.kondisi = "rusak berat" THEN 1 ELSE 0 END) as rusak_berat')
            ->join('barang', 'barang.id_kategori = kategori.id_kategori', 'left')
            ->groupBy('kategori.id_kategori')
            ->like('kategori.nama_kategori', $keyword)
            ->findAll();
    }

    // Cek apakah kategori memiliki barang
    public function hasBarang($id_kategori)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('barang');
        $count = $builder->where('id_kategori', $id_kategori)->countAllResults();
        return $count > 0;
    }

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nama_kategori' => 'required|min_length[3]|max_length[100]|is_unique[kategori.nama_kategori,id_kategori,{id_kategori}]'
    ];
    protected $validationMessages   = [
        'nama_kategori' => [
            'required' => 'Nama kategori harus diisi',
            'min_length' => 'Nama kategori minimal 3 karakter',
            'max_length' => 'Nama kategori maksimal 100 karakter',
            'is_unique' => 'Nama kategori sudah digunakan'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
