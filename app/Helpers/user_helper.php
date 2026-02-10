<?php

use App\Models\UserProfileModel;

function getNamaUser($idUser)
{
    $profileModel = new UserProfileModel();

    // Ambil data profil berdasarkan id_user yang diberikan
    $profile = $profileModel
        ->where('id_user', $idUser)
        ->first();

    // Kembalikan nama user atau 'User' jika tidak ditemukan
    return $profile['nama'] ?? 'User';
}
