<?php

namespace App\Controllers;

use Config\Database;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TestDb extends BaseController
{
    public function index()
    {
        // Membuat koneksi database
        $db = Database::connect();

        try {
            // Menjalankan query sederhana untuk test
            $query = $db->query("SELECT 1");

            // Jika query berhasil, berarti koneksi OK
            if ($query) {
                echo "✅ Database terhubung dan query BERHASIL!";
            }
        } catch (\Exception $e) {
            // Jika terjadi error, tampilkan pesan error
            echo "❌ Error koneksi database: " . $e->getMessage();
        }
    }
}
