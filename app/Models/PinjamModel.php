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
        'keterangan_kondisi',
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
            barang.kode_barang,
            user.email,
            userprofile.nama
        ')
        ->join('barang', 'barang.id_barang = pinjam.id_barang', 'left')
        ->join('user', 'user.id_user = pinjam.id_user', 'left')
        ->join('userprofile', 'userprofile.id_user = user.id_user', 'left');
    }

    // Ambil data pinjam dengan relasi barang dan user (optional filter by userId)
    public function getPinjamWithRelasi($userId = null)
    {
        if ($userId) {
            // Filter berdasarkan userId jika diberikan
            $this->where('pinjam.id_user', $userId);
        }

        // Urutkan berdasarkan tanggal pengajuan terbaru
        return $this->orderBy('pinjam.created_at', 'DESC');
    }

    // Ambil data pinjam berdasarkan ID dengan relasi barang dan user
    public function getPinjamWithRelasiById($id)
    {
        // Query dengan join ke tabel barang dan user
        return $this->db->table('pinjam')
            ->select('
                pinjam.*,
                barang.jenis_barang,
                barang.merek_barang,
                barang.tipe_barang,
                barang.kode_barang,
                user.email,
                userprofile.nama
            ')
            ->join('barang', 'barang.id_barang = pinjam.id_barang')
            ->join('user', 'user.id_user = pinjam.id_user')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->where('pinjam.id_pinjam', $id)
            ->get()
            ->getRowArray();
    }

    // Filter data pinjam
    public function filterPinjam(array $filters = [], $userId = null)
    {
        if ($userId) {
            // Filter berdasarkan userId jika diberikan
            $this->where('pinjam.id_user', $userId);
        }

        // Terapkan filter berdasarkan kriteria yang diberikan
        if (!empty($filters['keyword'])) {
            $this->groupStart()
                ->like('barang.jenis_barang', $filters['keyword'])
                ->orLike('barang.merek_barang', $filters['keyword'])
                ->orLike('barang.tipe_barang', $filters['keyword'])
                ->orLike('barang.kode_barang', $filters['keyword'])
                ->orLike('user.email', $filters['keyword'])
                ->orLike('userprofile.nama', $filters['keyword'])
            ->groupEnd();
        }

        // Filter berdasarkan status
        if (!empty($filters['status'])) {
            $this->where('pinjam.status', $filters['status']);
        }

        // Filter berdasarkan tanggal pengajuan
        if (!empty($filters['tgl_pengajuan'])) {
            $this->where('DATE(pinjam.tgl_pengajuan)', $filters['tgl_pengajuan']);
        }

        // Filter berdasarkan tanggal disetujui
        if (!empty($filters['tgl_disetujui_kembali'])) {
            $this->where('DATE(pinjam.tgl_disetujui_kembali)', $filters['tgl_disetujui_kembali']);
        }

        // Urutkan berdasarkan tanggal pengajuan terbaru
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
