<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use App\Models\UserProfileModel;

class AuthController extends BaseController
{
    public function index()
    {
        //
    }

    public function login()
    {
        return view('auth/login');
    }

    public function attemptLogin()
    {   
        $userModel = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        // Cek email
        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak ditemukan');
        }

        // Cek status user
        if ($user['status'] !== 'aktif') {
            return redirect()->back()->with('error', 'Akun anda tidak aktif');
        }

        // Cek password
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Password salah');
        }
        
        // Set session
        session()->set([
            'id_user'    => $user['id_user'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true
        ]);

        // Log activity
        log_activity(
            'Login ke sistem',
            'user',
            $user['id_user']
        );

        // Redirect ke dashboard
        return redirect()->to('/dashboard');

    }

    public function logout()
    {
        // Log activity
        log_activity(
            'Logout dari sistem',
            'user',
            session('id_user')
        );

        // Hapus session
        session()->destroy();
        // Redirect ke halaman login
        return redirect()->to('/')->with('success', 'Berhasil logout');
    }

}
