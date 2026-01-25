<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BarangModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $role = session()->get('role');

        switch ($role) {
            case 'admin':
                return $this->adminDashboard();

            case 'petugas':
                return $this->petugasDashboard();

            case 'peminjam':
                return $this->peminjamDashboard();

            default:
                throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }
    }

    private function adminDashboard()
    {
        $barangModel = new BarangModel();

        return view('dashboard/admin', [
            'totalBarang' => $barangModel->countAllResults()
        ]);
    }

    private function petugasDashboard()
    {
        return view('dashboard/petugas');
    }

    private function peminjamDashboard()
    {
        return view('dashboard/peminjam');
    
    }
}
