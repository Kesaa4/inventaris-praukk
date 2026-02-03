<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ActivityLogModel;

class ActivityLogController extends BaseController
{
public function index()
{
    if (session('role') !== 'admin') {
        throw new \CodeIgniter\Exceptions\PageForbiddenException();
    }

    $logModel = new ActivityLogModel();

    $keyword = $this->request->getGet('keyword');
    $role    = $this->request->getGet('role');

    // PANGGIL filter (mirip getBarangFiltered)
    $logModel->getLogFiltered($keyword, $role);

    $data = [
        'title'   => 'Activity Log',
        'logs'    => $logModel->paginate(10, 'log'),
        'pager'   => $logModel->pager,
        'keyword' => $keyword,
        'role'    => $role,
    ];

    return view('activity/index', $data);
}


}
