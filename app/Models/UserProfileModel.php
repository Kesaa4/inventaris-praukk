<?php

namespace App\Models;

use CodeIgniter\Model;

class UserProfileModel extends Model
{
    protected $table            = 'userprofile';
    protected $primaryKey       = 'id_profile';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'nama',
        'no_hp',
        'alamat',
        'foto_profil'
    ];

    /**
     * Ambil profil user dengan data user
     */
    public function getProfileWithUser($idUser)
    {
        $builder = $this->builder();
        return $builder->select('userprofile.*, user.email, user.role, user.status')
                       ->join('user', 'user.id_user = userprofile.id_user', 'left')
                       ->where('userprofile.id_user', $idUser)
                       ->get()
                       ->getRowArray();
    }

    /**
     * Cek apakah user sudah memiliki profil
     */
    public function hasProfile($idUser)
    {
        $builder = $this->builder();
        return $builder->where('id_user', $idUser)->countAllResults() > 0;
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
