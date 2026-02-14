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
        'created_by',
        'tgl_pengajuan',
        'tgl_disetujui',
        'tgl_jatuh_tempo',
        'durasi_pinjam',
        'tgl_pengajuan_kembali',
        'tgl_disetujui_kembali',
        'status',
        'alasan_ditolak',
        'approved_at',
        'approved_by',
        'kondisi_barang',
        'keterangan_kondisi'
    ];

    protected function initialize()
    {
        $this->select('pinjam.*, kategori.nama_kategori, barang.merek_barang, barang.tipe_barang, barang.kode_barang, user.email, userprofile.nama')
            ->join('barang', 'barang.id_barang = pinjam.id_barang', 'left')
            ->join('kategori', 'kategori.id_kategori = barang.id_kategori', 'left')
            ->join('user', 'user.id_user = pinjam.id_user', 'left')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left');
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
        return $this->where('pinjam.id_pinjam', $id)->first();
    }

    public function filterPinjam(array $filters = [], $userId = null)
    {
        if ($userId) {
            $this->where('pinjam.id_user', $userId);
        }

        if (!empty($filters['keyword'])) {
            $this->groupStart()
                ->like('kategori.nama_kategori', $filters['keyword'])
                ->orLike('barang.merek_barang', $filters['keyword'])
                ->orLike('barang.tipe_barang', $filters['keyword'])
                ->orLike('barang.kode_barang', $filters['keyword'])
                ->orLike('user.email', $filters['keyword'])
                ->orLike('userprofile.nama', $filters['keyword'])
                ->groupEnd();
        }

        if (!empty($filters['status'])) {
            $this->where('pinjam.status', $filters['status']);
        }

        if (!empty($filters['tgl_pengajuan'])) {
            $this->where('DATE(pinjam.tgl_pengajuan)', $filters['tgl_pengajuan']);
        }

        if (!empty($filters['tgl_disetujui_kembali'])) {
            $this->where('DATE(pinjam.tgl_disetujui_kembali)', $filters['tgl_disetujui_kembali']);
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
