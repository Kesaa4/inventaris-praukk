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

    public function getWithUser()
    {
        return $this->db->table('activity_log')
            ->select('activity_log.*, userprofile.nama, user.email, user.role')
            ->join('user', 'user.id_user = activity_log.id_user')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->orderBy('activity_log.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getLogFiltered($keyword = null, $role = null, $tabel = null, $action = null, $startDate = null, $endDate = null)
    {
        $this->select('activity_log.*, userprofile.nama, user.email, user.role')
            ->join('user', 'user.id_user = activity_log.id_user')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->orderBy('activity_log.created_at', 'DESC');

        if ($keyword) {
            $this->groupStart()
                ->like('userprofile.nama', $keyword)
                ->orLike('user.email', $keyword)
                ->orLike('activity_log.aktivitas', $keyword)
                ->groupEnd();
        }

        if ($role) {
            $this->where('user.role', $role);
        }

        if ($tabel) {
            $this->where('activity_log.tabel', $tabel);
        }

        if ($action) {
            $this->like('activity_log.aktivitas', $action);
        }

        if ($startDate) {
            $this->where('activity_log.created_at >=', $startDate . ' 00:00:00');
        }

        if ($endDate) {
            $this->where('activity_log.created_at <=', $endDate . ' 23:59:59');
        }

        return $this;
    }

    public function getLogDetail($id)
    {
        return $this->db->table('activity_log')
            ->select('activity_log.*, userprofile.nama, user.email, user.role')
            ->join('user', 'user.id_user = activity_log.id_user')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->where('activity_log.id_log', $id)
            ->get()
            ->getRowArray();
    }

    public function getStatsByRole()
    {
        return $this->db->table('activity_log')
            ->select('user.role, COUNT(*) as total')
            ->join('user', 'user.id_user = activity_log.id_user')
            ->groupBy('user.role')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getStatsByTable()
    {
        return $this->db->table('activity_log')
            ->select('tabel, COUNT(*) as total')
            ->where('tabel IS NOT NULL')
            ->groupBy('tabel')
            ->orderBy('total', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();
    }

    public function getStatsByAction()
    {
        $sql = "
            SELECT 
                CASE 
                    WHEN aktivitas LIKE '%Login%' THEN 'Login'
                    WHEN aktivitas LIKE '%Logout%' THEN 'Logout'
                    WHEN aktivitas LIKE '%Tambah%' OR aktivitas LIKE '%Menambah%' THEN 'Create'
                    WHEN aktivitas LIKE '%Edit%' OR aktivitas LIKE '%Update%' OR aktivitas LIKE '%Ubah%' THEN 'Update'
                    WHEN aktivitas LIKE '%Hapus%' OR aktivitas LIKE '%Delete%' THEN 'Delete'
                    WHEN aktivitas LIKE '%Lihat%' OR aktivitas LIKE '%Melihat%' THEN 'View'
                    WHEN aktivitas LIKE '%Export%' THEN 'Export'
                    ELSE 'Other'
                END as action_type,
                COUNT(*) as total
            FROM activity_log
            GROUP BY action_type
            ORDER BY total DESC
        ";
        
        return $this->db->query($sql)->getResultArray();
    }

    public function getTotalLogs()
    {
        return $this->countAllResults(false);
    }

    public function getTodayLogs()
    {
        return $this->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults(false);
    }

    public function getRecentActiveUsers($limit = 5)
    {
        return $this->db->table('activity_log')
            ->select('user.id_user, userprofile.nama, user.email, user.role, COUNT(*) as total_activity, MAX(activity_log.created_at) as last_activity')
            ->join('user', 'user.id_user = activity_log.id_user')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->groupBy('user.id_user')
            ->orderBy('last_activity', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getUniqueTables()
    {
        return $this->db->table('activity_log')
            ->select('tabel')
            ->distinct()
            ->where('tabel IS NOT NULL')
            ->orderBy('tabel', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getUniqueActions()
    {
        return ['Login', 'Logout', 'Tambah', 'Edit', 'Hapus', 'Lihat', 'Export', 'Update', 'Delete'];
    }

    public function cleanupOldLogs($days = 365)
    {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        $this->db->table($this->table)
            ->where('created_at <', $date)
            ->delete();
        
        return $this->db->affectedRows();
    }

    protected function autoCleanup()
    {
        $this->cleanupOldLogs(365);
    }

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['autoCleanupCallback'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function autoCleanupCallback(array $data)
    {
        if (rand(1, 100) === 1) {
            $this->autoCleanup();
        }
        return $data;
    }
}
