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
        // Ambil id_user dari session
        $idUser = session()->get('id_user');

        // Siapkan data untuk update
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

            log_activity(
                'Mengubah profil',
                'user',
                $idUser
            );
        }
        // Insert baru jika belum ada profil
        else {
            $data['id_user'] = $idUser;
            $profileModel->insert($data);
        }

        return redirect()->to('/profile')->with('success', 'Profile berhasil diperbarui');
    }
}
