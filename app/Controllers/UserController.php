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

        $keyword = $this->request->getGet('keyword');
        $role    = $this->request->getGet('role');

        // APPLY FILTER (tanpa ambil data dulu)
        $userModel->filterUser($keyword, $role);

        $data = [
            'users'   => $userModel->paginate(10, 'user'),
            'pager'   => $userModel->pager,
            'keyword' => $keyword,
            'role'    => $role,
        ];

        return view('user/index', $data);
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

        $rules = [
            'email' => [
                'rules'  => 'required|valid_email|is_unique[user.email]',
                'errors' => [
                    'required'    => 'Email wajib diisi',
                    'valid_email' => 'Format email tidak valid',
                    'is_unique'   => 'Email sudah terdaftar'
                ]
            ],
            'password' => 'required|min_length[6]',
            'role' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $profileModel = new UserProfileModel();

        //insert ke tabel user
        $id = $userModel->insert([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
        ]);

        log_activity(
            'Menambah user',
            'user',
            $id
        );

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

        // ======================
        // UPDATE / INSERT PROFILE
        // ======================
        $profileData = [
            'nama'   => $this->request->getPost('nama'),
            'no_hp'  => $this->request->getPost('no_hp'),
            'alamat' => $this->request->getPost('alamat')
        ];

        $existing = $profileModel->where('id_user', $id)->first();

        if ($existing) {
            $profileModel->update($existing['id_profile'], $profileData);

            log_activity(
                'Mengubah profil user',
                'user',
                $id
            );
        } else {
            $profileData['id_user'] = $id;
            $profileModel->insert($profileData);

            log_activity(
                'Menambahkan profil user',
                'user',
                $id
            );
        }

        // ======================
        // UPDATE ROLE (ADMIN ONLY)
        // ======================
        if (session()->get('role') === 'admin') {
            $userModel->update($id, [
                'role' => $this->request->getPost('role')
            ]);

            log_activity(
                'Mengubah role user',
                'user',
                $id
            );
        }

        return redirect()->to('/user')->with('success', 'User berhasil diupdate');
    }
    
    public function delete($id)
    {
        $userModel = new UserModel();
        $userModel->delete($id);

        log_activity(
            'Menonaktifkan user',
            'user',
            $id
        );

        return redirect()->to('/user')->with('success', 'User dinonaktifkan');
    
    }
}
