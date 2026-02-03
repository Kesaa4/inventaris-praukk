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
        'role'
    ];

    protected function initialize()
    {
        // DEFAULT SELECT + JOIN (WAJIB untuk paginate)
        $this->select('user.*, userprofile.nama')
             ->join('userprofile', 'userprofile.id_user = user.id_user', 'left');
    }

    public function filterUser($keyword = null, $role = null)
    {
        if ($keyword) {
            $this->groupStart()
                ->like('user.email', $keyword)
                ->orLike('userprofile.nama', $keyword)
            ->groupEnd();
        }

        if ($role) {
            $this->where('user.role', $role);
        }

        return $this->orderBy('user.id_user', 'DESC');
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
