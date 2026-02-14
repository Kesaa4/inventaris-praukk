<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ActivityLogModel;

class ActivityLogController extends BaseController
{
    protected ActivityLogModel $logModel;

    public function __construct()
    {
        $this->logModel = new ActivityLogModel();
    }

    /**
     * Halaman utama activity log
     */
    public function index(): string
    {
        // Cek apakah user adalah admin
        if (session('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException('Hanya admin yang dapat mengakses halaman ini');
        }

        // Ambil parameter filter dari query string
        $filters = [
            'keyword'   => $this->request->getGet('keyword'),
            'role'      => $this->request->getGet('role'),
            'tabel'     => $this->request->getGet('tabel'),
            'startDate' => $this->request->getGet('start_date'),
            'endDate'   => $this->request->getGet('end_date'),
        ];

        // Terapkan filter
        $this->logModel->getLogFiltered(
            $filters['keyword'],
            $filters['role'],
            $filters['tabel'],
            $filters['startDate'],
            $filters['endDate']
        );

        // Siapkan data untuk view
        $data = [
            'title'   => 'Activity Log',
            'logs'    => $this->logModel->paginate(20, 'log'),
            'pager'   => $this->logModel->pager,
            'filters' => $filters,
            'stats'   => [
                'byRole'  => $this->logModel->getStatsByRole(),
                'byTable' => $this->logModel->getStatsByTable(),
            ],
        ];

        // Log aktivitas melihat log
        log_activity('Melihat activity log', 'activity_log', 0);

        return view('activity/index', $data);
    }

    /**
     * Export activity log ke Excel
     */
    public function exportExcel(): void
    {
        // Cek apakah user adalah admin
        if (session('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException('Hanya admin yang dapat mengakses halaman ini');
        }

        helper('excel');

        // Ambil parameter filter
        $keyword   = $this->request->getGet('keyword');
        $role      = $this->request->getGet('role');
        $tabel     = $this->request->getGet('tabel');
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        // Filter data
        $this->logModel->getLogFiltered($keyword, $role, $tabel, $startDate, $endDate);
        $logs = $this->logModel->findAll();

        // Siapkan data untuk export
        $data = [];
        foreach ($logs as $log) {
            // Pisahkan ringkasan dan detail
            $aktivitas = str_replace('||', ' - ', $log['aktivitas']);
            
            $data[] = [
                date('d-m-Y H:i', strtotime($log['created_at'])),
                $log['nama'] ?? explode('@', $log['email'] ?? 'unknown')[0],
                $log['email'] ?? '-',
                $log['role'] ?? '-',
                $aktivitas,
                $log['tabel'] ?? '-',
                $log['id_data'] ?? '-'
            ];
        }

        // Headers
        $headers = [
            'Waktu',
            'Nama User',
            'Email',
            'Role',
            'Aktivitas',
            'Tabel',
            'ID Data'
        ];

        // Filename dengan filter info
        $filterInfo = [];
        if ($role) $filterInfo[] = "Role-{$role}";
        if ($tabel) $filterInfo[] = "Tabel-{$tabel}";
        if ($startDate) $filterInfo[] = "From-{$startDate}";
        if ($endDate) $filterInfo[] = "To-{$endDate}";
        
        $filterStr = $filterInfo ? '_' . implode('_', $filterInfo) : '';
        $filename = 'Activity_Log_' . date('Y-m-d_His') . $filterStr . '.xlsx';

        // Log aktivitas export
        log_activity('Export activity log ke Excel', 'activity_log', 0);

        // Export
        exportToExcel($data, $headers, $filename, 'ACTIVITY LOG SISTEM');
    }

    /**
     * Hapus log lama (cleanup)
     */
    public function cleanup(): ResponseInterface
    {
        // Cek apakah user adalah admin
        if (session('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Hanya admin yang dapat melakukan cleanup'
            ])->setStatusCode(403);
        }

        // Ambil jumlah hari dari input, default 90 hari    
        $days = (int) $this->request->getPost('days') ?: 90;
        
        // Validasi input
        try {
            // Hapus log yang lebih lama dari jumlah hari yang ditentukan
            $deleted = $this->logModel->cleanupOldLogs($days);
            // Log aktivitas cleanup
            log_activity("Cleanup activity log (hapus log > {$days} hari)", 'activity_log', 0);
            
            // Kembalikan response sukses
            return $this->response->setJSON([
                'success' => true,
                'message' => "Berhasil menghapus {$deleted} log lama"
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal cleanup: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}