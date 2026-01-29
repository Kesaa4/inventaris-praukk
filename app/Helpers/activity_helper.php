<?php

use App\Models\ActivityLogModel;

function log_activity($aktivitas, $tabel = null, $id_data = null)
{
    $logModel = new ActivityLogModel();

    $logModel->insert([
        'id_user'  => session('id_user'),
        'role'     => session('role'),
        'aktivitas'=> $aktivitas,
        'tabel'    => $tabel,
        'id_data'  => $id_data,
        'created_at' => date('Y-m-d H:i:s')
    ]);
}
