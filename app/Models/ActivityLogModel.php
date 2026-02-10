<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table            = 'activity_log';
    protected $primaryKey       = 'id_log';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'role',
        'aktivitas',
        'tabel',
        'id_data',
        'created_at'
    ];

    // Ambil log aktivitas beserta info user yang terkait
    public function getWithUser()
    {
        // Query dengan join ke tabel user dan userprofile
        return $this->db->table('activity_log')
            ->select('
                activity_log.*,
                userprofile.nama,
                user.email,
                user.role
            ')
            ->join('user', 'user.id_user = activity_log.id_user')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->orderBy('activity_log.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    // Ambil log aktivitas dengan filter keyword dan role user
    public function getLogFiltered($keyword = null, $role = null)
    {
        // Query dengan join ke tabel user dan userprofile
        $this->select('
                activity_log.*,
                userprofile.nama,
                user.email,
                user.role
            ')
            ->join('user', 'user.id_user = activity_log.id_user')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->orderBy('activity_log.created_at', 'DESC');

        // Terapkan filter
        if ($keyword) {
            $this->groupStart()
                ->like('userprofile.nama', $keyword)
                ->orLike('user.email', $keyword)
                ->orLike('activity_log.aktivitas', $keyword)
                ->groupEnd();
        }

        // Filter berdasarkan role user
        if ($role) {
            $this->where('user.role', $role);
        }

        // Eksekusi query dan kembalikan hasil
        return $this;
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
