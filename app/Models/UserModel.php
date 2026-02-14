<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'email',
        'password',
        'role',
        'status'
    ];

    public function filterUser($keyword = null, $role = null, $status = null)
    {
        $this->select('user.*, userprofile.nama, userprofile.foto_profil')
             ->join('userprofile', 'userprofile.id_user = user.id_user', 'left');

        if ($keyword) {
            $this->groupStart()
                ->like('user.email', $keyword)
                ->orLike('userprofile.nama', $keyword)
                ->groupEnd();
        }

        if ($role) {
            $this->where('user.role', $role);
        }

        if ($status) {
            $this->where('user.status', $status);
        }

        return $this->orderBy('user.id_user', 'DESC');
    }

    public function getUserWithProfile($id)
    {
        return $this->db->table('user')
            ->select('user.*, userprofile.nama, userprofile.no_hp, userprofile.alamat, userprofile.foto_profil')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->where('user.id_user', $id)
            ->get()
            ->getRowArray();
    }

    public function getUserOnly($id)
    {
        return $this->db->table('user')
            ->where('id_user', $id)
            ->get()
            ->getRowArray();
    }

    public function updateUser($id, $data)
    {
        return $this->db->table('user')
            ->where('id_user', $id)
            ->update($data);
    }

    public function countByRole($role)
    {
        return $this->db->table('user')
            ->where('role', $role)
            ->countAllResults();
    }

    public function countByStatus($status)
    {
        return $this->db->table('user')
            ->where('status', $status)
            ->countAllResults();
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
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
