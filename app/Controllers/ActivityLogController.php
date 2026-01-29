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

        return view('activity/index', [
            'logs' => $logModel->getWithUser()
        ]);
    }
}
