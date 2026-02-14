<?php

use App\Models\ActivityLogModel;

if (!function_exists('log_activity')) {
    function log_activity($aktivitas, $tabel = null, $id_data = null, $idUser = null)
    {
        try {
            $logModel = new ActivityLogModel();

            return $logModel->insert([
                'id_user'    => $idUser ?? session('id_user'),
                'role'       => session('role'),
                'aktivitas'  => $aktivitas,
                'tabel'      => $tabel,
                'id_data'    => $id_data,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Failed to log activity: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('format_activity')) {
    function format_activity($ringkasan, $detail = [])
    {
        if (empty($detail)) {
            return $ringkasan;
        }
        
        $detailStr = implode('; ', array_map(function($key, $value) {
            return "{$key}: {$value}";
        }, array_keys($detail), $detail));
        
        return $ringkasan . '||' . $detailStr;
    }
}

if (!function_exists('parse_activity')) {
    function parse_activity($aktivitas)
    {
        $parts = explode('||', $aktivitas, 2);
        $ringkasan = trim($parts[0]);
        $detail = [];
        
        if (isset($parts[1])) {
            $detailItems = explode(';', trim($parts[1]));
            foreach ($detailItems as $item) {
                $item = trim($item);
                if (strpos($item, ':') !== false) {
                    list($key, $value) = explode(':', $item, 2);
                    $detail[trim($key)] = trim($value);
                }
            }
        }
        
        return [
            'ringkasan' => $ringkasan,
            'detail' => $detail
        ];
    }
}
