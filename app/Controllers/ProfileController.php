<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserProfileModel;

class ProfileController extends BaseController
{
    public function index()
    {
        $profileModel = new UserProfileModel();

        $profile = $profileModel
            ->where('id_user', session()->get('id_user'))
            ->first();

        return view('profile/index', [
            'profile' => $profile
        ]);
    }

    public function edit()
    {
        $profileModel = new UserProfileModel();

        $profile = $profileModel
            ->where('id_user', session()->get('id_user'))
            ->first();

        return view('profile/edit', [
            'profile' => $profile
        ]);
    }

    public function update()
    {
        $profileModel = new UserProfileModel();
        $idUser = session()->get('id_user');

        $data = [
            'nama' => $this->request->getPost('nama'),
            'no_hp'        => $this->request->getPost('no_hp'),
            'alamat'       => $this->request->getPost('alamat'),
        ];

        $existing = $profileModel->where('id_user', $idUser)->first();

        if ($existing) {
            $profileModel->update($existing['id_profile'], $data);
        } else {
            $data['id_user'] = $idUser;
            $profileModel->insert($data);
        }

        return redirect()->to('/profile')->with('success', 'Profile berhasil diperbarui');
    }
}
