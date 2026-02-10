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


}
