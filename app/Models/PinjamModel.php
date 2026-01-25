<?php

namespace App\Models;

use CodeIgniter\Model;

class PinjamModel extends Model
{
    protected $table            = 'pinjam';
    protected $primaryKey       = 'id_pinjam';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_barang',
        'id_user',
        'tgl_pinjam',
        'tgl_kembali',
        'status',
        'approved_at',
        'approved_by'
    ];

    public function getPinjamWithRelasi($userId = null)
    {
        $builder = $this->db->table('pinjam')
            ->select('
                pinjam.*,
                barang.jenis_barang,
                barang.merek_barang,
                barang.tipe_barang,
                user.email
            ')
            ->join('barang', 'barang.id_barang = pinjam.id_barang', 'left')
            ->join('user', 'user.id_user = pinjam.id_user', 'left');
            // ->where('pinjam.deleted_at IS NULL', null, false);

        if ($userId) {
            $builder->where('pinjam.id_user', $userId);
        }

        return $builder->get()->getResultArray();
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
