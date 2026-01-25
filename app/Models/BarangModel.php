<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'id_barang';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_kategori',
        'jenis_barang',
        'merek_barang',
        'tipe_barang',
        'ram',
        'rom',
        'status',
        'keterangan'
    ];

    // Ambil data barang + nama kategori
    public function getBarangWithCategory()
    {
        return $this->select('barang.*, kategori.kategori_kondisi')
                    ->join('kategori', 'kategori.id_kategori = barang.id_kategori')
                    ->findAll();
    }

    // Ambil jenis barang
    public function getEnumJenisBarang()
    {
        $db = \Config\Database::connect(); //harus pake ini biar enumnya keambil

        $query = $db->query("SHOW COLUMNS FROM barang LIKE 'jenis_barang'");
        $row = $query->getRow();

        preg_match("/^enum\((.*)\)$/", $row->Type, $matches);
        return str_getcsv($matches[1], ',', "'");
    }

    // Search + Filter + Pagination
    public function getBarangFiltered($keyword = null, $kategori = null)
    {
        // $builder = $this->builder();

        $builder = $this->select('barang.*, kategori.kategori_kondisi')
                        ->join('kategori', 'kategori.id_kategori = barang.id_kategori');

        if ($keyword) {
            $builder->groupStart()
                    ->like('jenis_barang', $keyword)
                    ->orLike('merek_barang', $keyword)
                    ->orLike('status', $keyword)
                    ->groupEnd();
        }

        if ($kategori) {
            $builder->where('barang.id_kategori', $kategori);
        }

        return $builder;
    }

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
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
