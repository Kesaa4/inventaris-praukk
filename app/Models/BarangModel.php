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
        'kode_barang',
        'ram',
        'rom',
        'status',
        'keterangan',
        'deleted_at'
    ];

    // Ambil data barang berdasarkan kategori
    public function getByKategori($id_kategori)
    {
        return $this->where('id_kategori', $id_kategori)->findAll();
    }

    // Ambil data barang + nama kategori
    public function getBarangWithCategory()
    {
        return $this->select('barang.*, kategori.kategori_kondisi')
                    ->join('kategori', 'kategori.id_kategori = barang.id_kategori')
                    ->findAll();
    }

    // Ambil jenis barang
    public function getEnumJenisBarang() //Mengambil semua pilihan ENUM dari kolom database, lalu mengubahnya menjadi array PHP. Biasanya dipakai untuk dropdown form (select option).
    {
        $db = \Config\Database::connect(); //harus pake ini biar enumnya keambil, koneksi langsung ke database

        $query = $db->query("SHOW COLUMNS FROM barang LIKE 'jenis_barang'"); //Query ini meminta info detail kolom jenis_barang, termasuk tipe datanya.
        $row = $query->getRow(); //Mengambil satu row hasil query.

        //Regex adalah singkatan dari Regular Expression, yaitu pola teks khusus untuk mencari, mencocokkan, mengekstrak, atau memanipulasi string.        

        preg_match("/^enum\((.*)\)$/", $row->Type, $matches); //Regex ini: menghapus kata enum (, menghapus  ), menyisakan isi enum saja.
        return str_getcsv($matches[1], ',', "'"); //mengubah string menjadi array
    }

    // Search + Filter + Pagination
    public function getBarangFiltered($keyword = null, $kategori = null)
    {
        // Membangun query dasar dengan join ke tabel kategori
        $builder = $this->select('barang.*, kategori.kategori_kondisi')
                        ->join('kategori', 'kategori.id_kategori = barang.id_kategori');

        // Menambahkan kondisi pencarian jika ada keyword
        if ($keyword) {
            $builder->groupStart()
                    ->like('jenis_barang', $keyword)
                    ->orLike('merek_barang', $keyword)
                    ->orLike('tipe_barang', $keyword)
                    ->orLike('kode_barang', $keyword)
                    ->orLike('status', $keyword)
                    ->orLike('ram', $keyword)
                    ->orLike('rom', $keyword)
                    ->groupEnd();
        }
        // Menambahkan filter kategori
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
