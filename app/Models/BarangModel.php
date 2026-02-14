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
        'merek_barang',
        'tipe_barang',
        'kode_barang',
        'ram',
        'rom',
        'kondisi',
        'status',
        'keterangan',
        'foto'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getByKategori($id_kategori)
    {
        return $this->where('id_kategori', $id_kategori)->findAll();
    }

    public function getBarangById($id)
    {
        return $this->select('barang.*, kategori.nama_kategori')
                    ->join('kategori', 'kategori.id_kategori = barang.id_kategori', 'left')
                    ->where('barang.id_barang', $id)
                    ->first();
    }

    public function getBarangWithCategory()
    {
        return $this->select('barang.*, kategori.nama_kategori')
                    ->join('kategori', 'kategori.id_kategori = barang.id_kategori')
                    ->findAll();
    }

    public function getEnumKondisi()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SHOW COLUMNS FROM barang LIKE 'kondisi'");
        $row = $query->getRow();
        
        if ($row && preg_match("/^enum\((.*)\)$/", $row->Type, $matches)) {
            return str_getcsv($matches[1], ',', "'");
        }
        
        return ['Baik', 'Rusak Ringan', 'Rusak Berat'];
    }

    public function getBarangFiltered($keyword = null, $kategori = null)
    {
        $builder = $this->select('barang.*, kategori.nama_kategori')
                        ->join('kategori', 'kategori.id_kategori = barang.id_kategori');

        if ($keyword) {
            $builder->groupStart()
                    ->like('kategori.nama_kategori', $keyword)
                    ->orLike('merek_barang', $keyword)
                    ->orLike('tipe_barang', $keyword)
                    ->orLike('kode_barang', $keyword)
                    ->orLike('status', $keyword)
                    ->orLike('ram', $keyword)
                    ->orLike('rom', $keyword)
                    ->groupEnd();
        }

        if ($kategori) {
            $builder->where('barang.id_kategori', $kategori);
        }

        return $builder;
    }
}
