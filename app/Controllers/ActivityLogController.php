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

    public function index(): string
    {
        if (session('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException('Hanya admin yang dapat mengakses halaman ini');
        }

        $filters = [
            'keyword'   => $this->request->getGet('keyword'),
            'role'      => $this->request->getGet('role'),
            'tabel'     => $this->request->getGet('tabel'),
            'action'    => $this->request->getGet('action'),
            'startDate' => $this->request->getGet('start_date'),
            'endDate'   => $this->request->getGet('end_date'),
        ];

        $this->logModel->getLogFiltered(
            $filters['keyword'],
            $filters['role'],
            $filters['tabel'],
            $filters['action'],
            $filters['startDate'],
            $filters['endDate']
        );

        $data = [
            'title'   => 'Activity Log',
            'logs'    => $this->logModel->paginate(20, 'log'),
            'pager'   => $this->logModel->pager,
            'filters' => $filters,
            'stats'   => [
                'total'       => $this->logModel->getTotalLogs(),
                'today'       => $this->logModel->getTodayLogs(),
                'byRole'      => $this->logModel->getStatsByRole(),
                'byTable'     => $this->logModel->getStatsByTable(),
                'byAction'    => $this->logModel->getStatsByAction(),
                'recentUsers' => $this->logModel->getRecentActiveUsers(5),
            ],
            'tables'  => $this->logModel->getUniqueTables(),
            'actions' => $this->logModel->getUniqueActions(),
        ];

        log_activity('Melihat activity log', 'activity_log', 0);

        return view('activity/index', $data);
    }

    public function exportExcel(): void
    {
        if (session('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException('Hanya admin yang dapat mengakses halaman ini');
        }

        helper('excel');

        $keyword   = $this->request->getGet('keyword');
        $role      = $this->request->getGet('role');
        $tabel     = $this->request->getGet('tabel');
        $action    = $this->request->getGet('action');
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        $this->logModel->getLogFiltered($keyword, $role, $tabel, $action, $startDate, $endDate);
        $logs = $this->logModel->findAll();

        $data = [];
        foreach ($logs as $log) {
            $aktivitas = str_replace('||', ' - ', $log['aktivitas']);
            
            $data[] = [
                date('d-m-Y H:i:s', strtotime($log['created_at'])),
                $log['nama'] ?? explode('@', $log['email'] ?? 'unknown')[0],
                $log['email'] ?? '-',
                $log['role'] ?? '-',
                $aktivitas,
                $log['tabel'] ?? '-',
                $log['id_data'] ?? '-'
            ];
        }

        $headers = ['Waktu', 'Nama User', 'Email', 'Role', 'Aktivitas', 'Tabel', 'ID Data'];

        $filterInfo = [];
        if ($role) $filterInfo[] = "Role-{$role}";
        if ($tabel) $filterInfo[] = "Tabel-{$tabel}";
        if ($action) $filterInfo[] = "Action-{$action}";
        if ($startDate) $filterInfo[] = "From-{$startDate}";
        if ($endDate) $filterInfo[] = "To-{$endDate}";
        
        $filterStr = $filterInfo ? '_' . implode('_', $filterInfo) : '';
        $filename = 'Activity_Log_' . date('Y-m-d_His') . $filterStr . '.xlsx';

        log_activity('Export activity log ke Excel', 'activity_log', 0);

        exportToExcel($data, $headers, $filename, 'ACTIVITY LOG SISTEM');
    }

    public function cleanup(): ResponseInterface
    {
        if (session('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Hanya admin yang dapat melakukan cleanup'
            ])->setStatusCode(403);
        }
        
        try {
            $deleted = $this->logModel->cleanupOldLogs(365);
            
            log_activity("Cleanup activity log manual (hapus log > 1 tahun)", 'activity_log', 0);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => "Berhasil menghapus {$deleted} log lama (> 1 tahun)"
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal cleanup: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}