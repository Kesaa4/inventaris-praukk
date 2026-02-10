<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserProfileModel;

class ProfileController extends BaseController
{
    // Tampilkan profil user
    public function index()
    {
        $profileModel = new UserProfileModel();

        // Ambil profil berdasarkan id_user dari session
        $profile = $profileModel
            ->where('id_user', session()->get('id_user'))
            ->first();

        // Tampilkan view dengan data profil
        return view('profile/index', [
            'profile' => $profile
        ]);
    }

    // Tampilkan form edit profil
    public function edit()
    {
        $profileModel = new UserProfileModel();

        // Ambil profil berdasarkan id_user dari session
        $profile = $profileModel
            ->where('id_user', session()->get('id_user'))
            ->first();

        // Tampilkan view dengan data profil
        return view('profile/edit', [
            'profile' => $profile
        ]);
    }

    // Proses update profil
    public function update()
    {
        $profileModel = new UserProfileModel();
        $userModel = new \App\Models\UserModel();
        
        // Ambil id_user dari session
        $idUser = session()->get('id_user');

        // Siapkan data untuk update profil
        $data = [
            'nama'         => $this->request->getPost('nama'),
            'no_hp'        => $this->request->getPost('no_hp'),
            'alamat'       => $this->request->getPost('alamat'),
        ];

        // Cek apakah profil sudah ada
        $existing = $profileModel->where('id_user', $idUser)->first();

        // Update atau insert profil berdasarkan ada tidaknya data sebelumnya
        if ($existing) {
            $profileModel->update($existing['id_profile'], $data);
        }
        // Insert baru jika belum ada profil
        else {
            $data['id_user'] = $idUser;
            $profileModel->insert($data);
        }

        // Proses update password jika diisi
        $passwordLama = $this->request->getPost('password_lama');
        $passwordBaru = $this->request->getPost('password_baru');
        $passwordKonfirmasi = $this->request->getPost('password_konfirmasi');

        // Jika ada input password
        if (!empty($passwordLama) || !empty($passwordBaru) || !empty($passwordKonfirmasi)) {
            
            // Validasi: semua field password harus diisi
            if (empty($passwordLama) || empty($passwordBaru) || empty($passwordKonfirmasi)) {
                return redirect()->back()->with('error', 'Semua field password harus diisi jika ingin mengubah password');
            }

            // Validasi: password baru minimal 6 karakter
            if (strlen($passwordBaru) < 6) {
                return redirect()->back()->with('error', 'Password baru minimal 6 karakter');
            }

            // Validasi: password baru dan konfirmasi harus sama
            if ($passwordBaru !== $passwordKonfirmasi) {
                return redirect()->back()->with('error', 'Password baru dan konfirmasi tidak cocok');
            }

            // Ambil data user untuk verifikasi password lama
            $user = $userModel->find($idUser);

            // Verifikasi password lama
            if (!password_verify($passwordLama, $user['password'])) {
                return redirect()->back()->with('error', 'Password lama tidak sesuai');
            }

            // Update password
            $userModel->update($idUser, [
                'password' => password_hash($passwordBaru, PASSWORD_DEFAULT)
            ]);

            log_activity(
                'Mengubah profil dan password',
                'user',
                $idUser
            );

            return redirect()->to('/profile')->with('success', 'Profile dan password berhasil diperbarui');
        }

        log_activity(
            'Mengubah profil',
            'user',
            $idUser
        );

        return redirect()->to('/profile')->with('success', 'Profile berhasil diperbarui');
    }
}
