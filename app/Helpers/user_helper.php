<?php

use App\Models\UserProfileModel;

function getNamaUser($idUser)
{
    $profileModel = new UserProfileModel();

    $profile = $profileModel
        ->where('id_user', $idUser)
        ->first();

    return $profile['nama'] ?? 'User';
}
