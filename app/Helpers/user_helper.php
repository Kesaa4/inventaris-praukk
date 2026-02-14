<?php

use App\Models\UserProfileModel;

if (!function_exists('getNamaUser')) {
    function getNamaUser($idUser)
    {
        $profileModel = new UserProfileModel();
        $profile = $profileModel->where('id_user', $idUser)->first();
        return $profile['nama'] ?? 'User';
    }
}
