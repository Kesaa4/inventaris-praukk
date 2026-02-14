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

    // Menampilkan login form
    public function login()
    {
        return view('auth/login');
    }

    public function attemptLogin()
    {
        // ✅ Rate limiting untuk mencegah brute force
        $throttle = service('throttler');
        $ipAddress = $this->request->getIPAddress();
        
        if ($throttle->check(md5($ipAddress), 5, MINUTE) === false) {
            return redirect()->back()->with('error', 'Terlalu banyak percobaan login. Coba lagi dalam 1 menit.');
        }
        
        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            // Tambahkan ke throttle saat gagal
            $throttle->check(md5($ipAddress), 5, MINUTE);
            return redirect()->back()->with('error', 'Email tidak ditemukan');
        }

        if ($user['status'] !== 'aktif') {
            return redirect()->back()->with('error', 'Akun anda tidak aktif');
        }

        if (!password_verify($password, $user['password'])) {
            // Tambahkan ke throttle saat gagal
            $throttle->check(md5($ipAddress), 5, MINUTE);
            return redirect()->back()->with('error', 'Password salah');
        }
        
        session()->set([
            'id_user'    => $user['id_user'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true
        ]);

        log_activity('Login ke sistem', 'user', $user['id_user']);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        log_activity('Logout dari sistem', 'user', session('id_user'));
        session()->destroy();
        return redirect()->to('/')->with('success', 'Berhasil logout');
    }

}
