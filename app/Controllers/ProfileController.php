<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserProfileModel;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected $profileModel;
    protected $userModel;

    public function __construct()
    {
        $this->profileModel = new UserProfileModel();
        $this->userModel = new UserModel();
        helper('upload');
    }

    /**
     * Halaman tampilan profil user
     */
    public function index()
    {
        $idUser = session()->get('id_user');
        $profile = $this->profileModel->getProfileWithUser($idUser);

        if (!$profile) {
            // Jika profil belum ada, buat profil kosong
            $this->profileModel->insert([
                'id_user' => $idUser,
                'nama' => session()->get('email')
            ]);
            $profile = $this->profileModel->getProfileWithUser($idUser);
        }

        return view('profile/index', ['profile' => $profile]);
    }

    /**
     * Form edit profil
     */
    public function edit()
    {
        $idUser = session()->get('id_user');
        $profile = $this->profileModel->getProfileWithUser($idUser);

        if (!$profile) {
            // Jika profil belum ada, buat profil kosong
            $this->profileModel->insert([
                'id_user' => $idUser,
                'nama' => session()->get('email')
            ]);
            $profile = $this->profileModel->getProfileWithUser($idUser);
        }

        return view('profile/edit', ['profile' => $profile]);
    }

    /**
     * Proses update profil
     */
    public function update()
    {
        $idUser = session()->get('id_user');

        // Validasi input profil
        $rules = [
            'nama' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama lengkap wajib diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'no_hp' => [
                'rules' => 'permit_empty|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'numeric' => 'No HP harus berupa angka',
                    'min_length' => 'No HP minimal 10 digit',
                    'max_length' => 'No HP maksimal 15 digit'
                ]
            ],
            'alamat' => [
                'rules' => 'permit_empty|max_length[255]',
                'errors' => [
                    'max_length' => 'Alamat maksimal 255 karakter'
                ]
            ]
        ];

        // Validasi foto profil jika diupload
        if ($this->request->getFile('foto_profil')->isValid()) {
            $rules['foto_profil'] = [
                'rules' => 'uploaded[foto_profil]|max_size[foto_profil,2048]|is_image[foto_profil]|mime_in[foto_profil,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Pilih foto profil',
                    'max_size' => 'Ukuran foto maksimal 2MB',
                    'is_image' => 'File harus berupa gambar',
                    'mime_in' => 'Format foto harus JPG, JPEG, atau PNG'
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
            $profile = $this->profileModel->where('id_user', $idUser)->first();

            // Siapkan data untuk update profil
            $data = [
                'nama' => $this->request->getPost('nama'),
                'no_hp' => $this->request->getPost('no_hp'),
                'alamat' => $this->request->getPost('alamat'),
            ];

            // Proses upload foto profil
            $file = $this->request->getFile('foto_profil');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Hapus foto lama jika ada
                if (!empty($profile['foto_profil'])) {
                    $oldPath = FCPATH . 'uploads/profile/' . $profile['foto_profil'];
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                // Upload foto baru
                $newName = 'profile_' . $idUser . '_' . time() . '.' . $file->getExtension();
                $file->move(FCPATH . 'uploads/profile', $newName);
                $data['foto_profil'] = $newName;
            }

            // Update profil
            if ($profile) {
                $this->profileModel->update($profile['id_profile'], $data);
            } else {
                $data['id_user'] = $idUser;
                $this->profileModel->insert($data);
            }

            // Update session nama jika berubah
            session()->set('nama', $data['nama']);

            // Proses update password jika diisi
            $passwordUpdated = $this->updatePassword($idUser);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan data profil');
            }

            $message = $passwordUpdated 
                ? 'Profil dan password berhasil diperbarui' 
                : 'Profil berhasil diperbarui';

            log_activity($passwordUpdated ? 'Mengubah profil dan password' : 'Mengubah profil', 'user', $idUser);

            return redirect()->to('/profile')->with('success', $message);
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    /**
     * Update password user
     */
    private function updatePassword($idUser)
    {
        $passwordLama = $this->request->getPost('password_lama');
        $passwordBaru = $this->request->getPost('password_baru');
        $passwordKonfirmasi = $this->request->getPost('password_konfirmasi');

        // Jika tidak ada input password, skip
        if (empty($passwordLama) && empty($passwordBaru) && empty($passwordKonfirmasi)) {
            return false;
        }

        // Validasi: semua field password harus diisi
        if (empty($passwordLama) || empty($passwordBaru) || empty($passwordKonfirmasi)) {
            throw new \Exception('Semua field password harus diisi jika ingin mengubah password');
        }

        // Validasi: password baru minimal 6 karakter
        if (strlen($passwordBaru) < 6) {
            throw new \Exception('Password baru minimal 6 karakter');
        }

        // Validasi: password baru dan konfirmasi harus sama
        if ($passwordBaru !== $passwordKonfirmasi) {
            throw new \Exception('Password baru dan konfirmasi tidak cocok');
        }

        // Ambil data user untuk verifikasi password lama (tanpa join)
        $user = $this->userModel->find($idUser);

        if (!$user) {
            throw new \Exception('User tidak ditemukan');
        }

        // Verifikasi password lama
        if (!password_verify($passwordLama, $user['password'])) {
            throw new \Exception('Password lama tidak sesuai');
        }

        // Update password
        $this->userModel->update($idUser, [
            'password' => password_hash($passwordBaru, PASSWORD_DEFAULT)
        ]);

        return true;
    }

    /**
     * Hapus foto profil
     */
    public function deleteFoto()
    {
        $idUser = session()->get('id_user');
        $profile = $this->profileModel->where('id_user', $idUser)->first();

        if (!$profile || empty($profile['foto_profil'])) {
            return redirect()->to('/profile')->with('error', 'Foto profil tidak ditemukan');
        }

        try {
            // Hapus file foto
            $fotoPath = FCPATH . 'uploads/profile/' . $profile['foto_profil'];
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }

            // Update database
            $this->profileModel->update($profile['id_profile'], ['foto_profil' => null]);

            log_activity('Menghapus foto profil', 'user', $idUser);

            return redirect()->to('/profile')->with('success', 'Foto profil berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->to('/profile')->with('error', 'Gagal menghapus foto profil: ' . $e->getMessage());
        }
    }
}
