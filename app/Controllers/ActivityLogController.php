<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ActivityLogModel;

class ActivityLogController extends BaseController
{
public function index()
{
    // Cek apakah user adalah admin
    if (session('role') !== 'admin') {
        throw new \CodeIgniter\Exceptions\PageForbiddenException();
    }

    // Inisialisasi model
    $logModel = new ActivityLogModel();

    // Ambil parameter filter dari query string
    $keyword = $this->request->getGet('keyword');
    $role    = $this->request->getGet('role');

    // PANGGIL filter dari model
    $logModel->getLogFiltered($keyword, $role);

    // Siapkan data untuk view
    $data = [
        'title'   => 'Activity Log',
        'logs'    => $logModel->paginate(10, 'log'),
        'pager'   => $logModel->pager,
        'keyword' => $keyword,
        'role'    => $role,
    ];

    // Tampilkan view dengan data
    return view('activity/index', $data);
}

public function exportExcel()
{
    // Cek apakah user adalah admin
    if (session('role') !== 'admin') {
        throw new \CodeIgniter\Exceptions\PageForbiddenException();
    }

    helper('excel');

    $logModel = new ActivityLogModel();

    // Ambil parameter filter
    $keyword = $this->request->getGet('keyword');
    $role    = $this->request->getGet('role');

    // Filter data
    $logModel->getLogFiltered($keyword, $role);
    $logs = $logModel->findAll();

    // Siapkan data untuk export
    $data = [];
    foreach ($logs as $log) {
        $data[] = [
            date('d-m-Y H:i', strtotime($log['created_at'])),
            $log['nama'] ?? explode('@', $log['email'])[0],
            $log['email'],
            $log['role'] ?? '-',
            str_replace('||', ' - ', $log['aktivitas']),
            $log['tabel'],
            $log['id_data']
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

    // Filename
    $filename = 'Activity_Log_' . date('Y-m-d_His') . '.xlsx';

    // Export
    exportToExcel($data, $headers, $filename, 'ACTIVITY LOG SISTEM');
}



}
