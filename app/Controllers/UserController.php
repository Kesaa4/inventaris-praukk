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

        // Ambil parameter filter dari query string
        $keyword = $this->request->getGet('keyword');
        $role    = $this->request->getGet('role');

        // APPLY FILTER (tanpa ambil data dulu)
        $userModel->filterUser($keyword, $role);

        // Siapkan data untuk view
        $data = [
            'users'   => $userModel->paginate(10, 'user'),
            'pager'   => $userModel->pager,
            'keyword' => $keyword,
            'role'    => $role,
        ];

        return view('user/index', $data);
    }

    // Cek apakah user adalah admin
    protected function mustAdmin()
    {
        if (session('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }
    }

    //TAMBAH USER
    public function create()
    {
        // Cek admin
        $this->mustAdmin();
        // Tampilkan form create user
        return view('user/create');
    }

    // SIMPAN USER
    public function store()
    {
        $this->mustAdmin();

        // Validasi input
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

        // Jika validasi gagal
        if (!$this->validate($rules)) 
            // Kembali ke form dengan input lama dan pesan error
        {
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
            'status'   => $this->request->getPost('status'),
        ]);

        log_activity(
            'Menambah user ' . $this->request->getPost('nama') .
            ' (' . $this->request->getPost('role') . ')',
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

        // Tampilkan form edit user dengan data user dan profil
        return view('user/edit', [
            'user' => $userModel->find($id),
            'profile' => $profileModel->where('id_user', $id)->first()
        ]);
    }

    public function update($id)
    {
        $this->mustAdmin();

        $userModel = new UserModel();
        $profileModel = new UserProfileModel();

        // DATA LAMA
        $user = $userModel->find($id);
        // Ambil data profil untuk mendapatkan nama user
        $profile = $profileModel->where('id_user', $id)->first();

        // Jika user tidak ditemukan
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Nama user dari profil atau 'Unknown' jika tidak ada
        $nama = $profile['nama'] ?? 'Unknown';

        // DATA LAMA
        $oldRole   = $user['role'];
        $oldStatus = $user['status'];

        // DATA BARU
        $newRole   = $this->request->getPost('role');
        $newStatus = $this->request->getPost('status');

        // Update data user
        $userModel->update($id, [
            'role'   => $newRole,
            'status' => $newStatus
        ]);

        // RANGKAI LOG DETAIL
        $detail = [];

        // Cek perubahan role
        if ($oldRole !== $newRole) {
            $detail[] = "role: $oldRole → $newRole";
        }

        // Cek perubahan status
        if ($oldStatus !== $newStatus) {
            $detail[] = "status: $oldStatus → $newStatus";
        }

        // Gabungkan detail log menjadi satu string
        $detailLog = implode(', ', $detail);

        log_activity(
            'Mengubah data user ' . $nama . ($detailLog ? " ($detailLog)" : ''),
            'user',
            $id
        );

        return redirect()->to('/user')->with('success', 'User berhasil diupdate');
    }
}
