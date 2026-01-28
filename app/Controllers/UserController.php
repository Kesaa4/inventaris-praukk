<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use App\Models\UserProfileModel;

class UserController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $profileModel = new UserProfileModel();

        $users = $userModel
            ->select('user.*, userprofile.nama')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->findAll();

        return view('user/index', compact('users'));
    }

    protected function mustAdmin()
    {
        if (session('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }
    }

    //TAMBAH USER
    public function create()
    {
        $this->mustAdmin();
        return view('user/create');
    }

    // SIMPAN USER
    public function store()
    {
        $this->mustAdmin();

        $userModel = new UserModel();
        $profileModel = new UserProfileModel();

        //insert ke tabel user
        $userModel->insert([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
        ]);

        // ambil ID user yang BARU dibuat
        $idUser = $userModel->getInsertID();

        // insert ke userprofile
        $profileModel->insert([
            'id_user' => $idUser,
            'nama'    => $this->request->getPost('nama')
        ]);

        return redirect()->to('/user')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $userModel = new UserModel();
        $profileModel = new UserProfileModel();

        return view('user/edit', [
            'user' => $userModel->find($id),
            'profile' => $profileModel->where('id_user', $id)->first()
        ]);
    }

    public function update($id)
    {
        $userModel = new UserModel();
        $profileModel = new UserProfileModel();

        // UPDATE PROFILE
        $profileData = [
            'nama' => $this->request->getPost('nama'),
            'no_hp'        => $this->request->getPost('no_hp'),
            'alamat'       => $this->request->getPost('alamat')
        ];

        $existing = $profileModel->where('id_user', $id)->first();
        if ($existing) {
            $profileModel->update($existing['id_profile'], $profileData);
        } else {
            $profileData['id_user'] = $id;
            $profileModel->insert($profileData);
        }

        // UPDATE ROLE (ADMIN ONLY)
        if (session()->get('role') === 'admin') {
            $userModel->update($id, [
                'role' => $this->request->getPost('role')
            ]);
        }

        return redirect()->to('/user')->with('success', 'User berhasil diupdate');
    }

    public function delete($id)
    {
        $userModel = new UserModel();
        $userModel->delete($id);

        return redirect()->to('/user')->with('success', 'User dihapus');
    
    }
}
