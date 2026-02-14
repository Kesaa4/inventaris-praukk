<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\UserProfileModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $profileModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->profileModel = new UserProfileModel();
    }

    /**
     * Halaman daftar user dengan filter dan pagination
     */
    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $role = $this->request->getGet('role');
        $status = $this->request->getGet('status');

        $this->userModel->filterUser($keyword, $role, $status);

        $data = [
            'users' => $this->userModel->paginate(10, 'user'),
            'pager' => $this->userModel->pager,
            'keyword' => $keyword,
            'role' => $role,
            'status' => $status,
        ];

        return view('user/index', $data);
    }

    /**
     * Form tambah user baru
     */
    public function create()
    {
        $this->mustAdmin();
        return view('user/create');
    }

    /**
     * Simpan user baru ke database
     */
    public function store()
    {
        $this->mustAdmin();

        $rules = [
            'nama' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama lengkap wajib diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[user.email]',
                'errors' => [
                    'required' => 'Email wajib diisi',
                    'valid_email' => 'Format email tidak valid',
                    'is_unique' => 'Email sudah terdaftar'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password wajib diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ],
            'role' => [
                'rules' => 'required|in_list[admin,petugas,peminjam]',
                'errors' => [
                    'required' => 'Role wajib dipilih',
                    'in_list' => 'Role tidak valid'
                ]
            ],
            'status' => [
                'rules' => 'required|in_list[aktif,tidak aktif]',
                'errors' => [
                    'required' => 'Status wajib dipilih',
                    'in_list' => 'Status tidak valid'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $nama = $this->request->getPost('nama');
            $email = $this->request->getPost('email');
            $role = $this->request->getPost('role');

            // Insert user
            $this->userModel->insert([
                'email' => $email,
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role' => $role,
                'status' => $this->request->getPost('status'),
            ]);

            $idUser = $this->userModel->getInsertID();

            // Insert profile
            $this->profileModel->insert([
                'id_user' => $idUser,
                'nama' => $nama
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan data user');
            }

            log_activity("Menambah user $nama ($role)", 'user', $idUser);

            return redirect()->to('/user')->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    /**
     * Form edit user
     */
    public function edit($id)
    {
        $this->mustAdmin();

        $user = $this->userModel->getUserWithProfile($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User dengan ID $id tidak ditemukan");
        }

        return view('user/edit', ['user' => $user]);
    }

    /**
     * Update data user
     */
    public function update($id)
    {
        $this->mustAdmin();

        $user = $this->userModel->getUserWithProfile($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User dengan ID $id tidak ditemukan");
        }

        $rules = [
            'nama' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama lengkap wajib diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'role' => [
                'rules' => 'required|in_list[admin,petugas,peminjam]',
                'errors' => [
                    'required' => 'Role wajib dipilih',
                    'in_list' => 'Role tidak valid'
                ]
            ],
            'status' => [
                'rules' => 'required|in_list[aktif,tidak aktif]',
                'errors' => [
                    'required' => 'Status wajib dipilih',
                    'in_list' => 'Status tidak valid'
                ]
            ]
        ];

        // Validasi password jika diisi
        if ($this->request->getPost('password')) {
            $rules['password'] = [
                'rules' => 'min_length[6]',
                'errors' => [
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $nama = $this->request->getPost('nama');
            $role = $this->request->getPost('role');
            $status = $this->request->getPost('status');
            $password = $this->request->getPost('password');

            // Update user
            $updateData = [
                'role' => $role,
                'status' => $status
            ];

            if ($password) {
                $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $this->userModel->update($id, $updateData);

            // Update profile
            $this->profileModel->where('id_user', $id)->set(['nama' => $nama])->update();

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal mengupdate data user');
            }

            // Log perubahan
            $changes = [];
            if ($user['nama'] !== $nama) $changes[] = "nama: {$user['nama']} → $nama";
            if ($user['role'] !== $role) $changes[] = "role: {$user['role']} → $role";
            if ($user['status'] !== $status) $changes[] = "status: {$user['status']} → $status";
            if ($password) $changes[] = "password diubah";

            $detailLog = $changes ? ' (' . implode(', ', $changes) . ')' : '';
            log_activity("Mengubah data user $nama$detailLog", 'user', $id);

            return redirect()->to('/user')->with('success', 'User berhasil diupdate');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate user: ' . $e->getMessage());
        }
    }

    /**
     * Hapus user (soft delete atau hard delete)
     */
    public function delete($id)
    {
        $this->mustAdmin();

        $user = $this->userModel->getUserWithProfile($id);

        if (!$user) {
            return redirect()->to('/user')->with('error', 'User tidak ditemukan');
        }

        // Cegah hapus diri sendiri
        if ($id == session('id_user')) {
            return redirect()->to('/user')->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Hapus profile
            $this->profileModel->where('id_user', $id)->delete();

            // Hapus user
            $this->userModel->delete($id);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menghapus data user');
            }

            log_activity("Menghapus user {$user['nama']} ({$user['role']})", 'user', $id);

            return redirect()->to('/user')->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/user')->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    /**
     * Reset password user
     */
    public function resetPassword($id)
    {
        $this->mustAdmin();

        $user = $this->userModel->getUserWithProfile($id);

        if (!$user) {
            return redirect()->to('/user')->with('error', 'User tidak ditemukan');
        }

        // Password default: 123456
        $defaultPassword = '123456';
        $this->userModel->update($id, [
            'password' => password_hash($defaultPassword, PASSWORD_DEFAULT)
        ]);

        log_activity("Reset password user {$user['nama']}", 'user', $id);

        return redirect()->to('/user')->with('success', "Password user {$user['nama']} berhasil direset menjadi: $defaultPassword");
    }

    /**
     * Validasi hanya admin yang bisa akses
     */
    protected function mustAdmin()
    {
        if (session('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Halaman tidak ditemukan');
        }
    }
}
