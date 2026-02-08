<?php

namespace App\Models;

use CodeIgniter\Model;

class PinjamModel extends Model
{
    protected $table            = 'pinjam';
    protected $primaryKey       = 'id_pinjam';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_barang',
        'id_user',
        'tgl_pinjam',
        'tgl_kembali',
        'status',
        'alasan_ditolak',
        'approved_at',
        'approved_by',
        'deleted_at'
    ];

    protected function initialize()
    {
        // DEFAULT QUERY (WAJIB untuk paginate + join)
        $this->select('
            pinjam.*,
            barang.jenis_barang,
            barang.merek_barang,
            barang.tipe_barang,
            user.email
        ')
        ->join('barang', 'barang.id_barang = pinjam.id_barang', 'left')
        ->join('user', 'user.id_user = pinjam.id_user', 'left');
    }

    public function getPinjamWithRelasi($userId = null)
    {
        if ($userId) {
            $this->where('pinjam.id_user', $userId);
        }

        return $this->orderBy('pinjam.created_at', 'DESC');
    }

    public function getPinjamWithRelasiById($id)
    {
        return $this->db->table('pinjam')
            ->select('
                pinjam.*,
                barang.jenis_barang,
                barang.merek_barang,
                barang.tipe_barang,
                user.email
            ')
            ->join('barang', 'barang.id_barang = pinjam.id_barang')
            ->join('user', 'user.id_user = pinjam.id_user')
            ->where('pinjam.id_pinjam', $id)
            ->get()
            ->getRowArray();
    }

    public function filterPinjam(array $filters = [], $userId = null)
    {
        if ($userId) {
            $this->where('pinjam.id_user', $userId);
        }

        if (!empty($filters['keyword'])) {
            $this->groupStart()
                ->like('barang.jenis_barang', $filters['keyword'])
                ->orLike('barang.merek_barang', $filters['keyword'])
                ->orLike('barang.tipe_barang', $filters['keyword'])
                ->orLike('user.email', $filters['keyword'])
            ->groupEnd();
        }

        if (!empty($filters['status'])) {
            $this->where('pinjam.status', $filters['status']);
        }

        if (!empty($filters['tgl_pinjam'])) {
            $this->where('DATE(pinjam.tgl_pinjam)', $filters['tgl_pinjam']);
        }

        if (!empty($filters['tgl_kembali'])) {
            $this->where('DATE(pinjam.tgl_kembali)', $filters['tgl_kembali']);
        }

        return $this->orderBy('pinjam.created_at', 'DESC');
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
