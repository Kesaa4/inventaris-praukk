<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        
    <!-- JS 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-dark bg-warning">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1 text-dark">Dashboard Petugas</span>
        <span class="text-dark">
            <?= explode('@', session()->get('email'))[0] ?>
            <strong>(<?= session()->get('role') ?>)</strong>
        </span>
    </div>
</nav>

<div class="container mt-4">

    <!-- Welcome Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="mb-1">
                Selamat bertugas, 
                <b><?= explode('@', session()->get('email'))[0] ?></b> 👋
            </h4>
            <p class="text-muted mb-0">
                Kelola data barang dan peminjaman dari dashboard ini.
            </p>
        </div>
    </div>

    <!-- Menu Cards -->
    <div class="row g-3">

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5>Data Barang</h5>
                    <p class="text-muted">
                        Lihat dan kelola data barang inventaris.
                    </p>
                    <a href="<?= base_url('barang') ?>" class="btn btn-outline-warning w-100">
                        Lihat Data Barang
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5>Kelola Peminjaman</h5>
                    <p class="text-muted">
                        Proses, setujui, dan kelola peminjaman.
                    </p>
                    <a href="<?= base_url('pinjam') ?>" class="btn btn-outline-warning w-100">
                        Kelola Peminjaman
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5>Profile Saya</h5>
                    <p class="text-muted">
                        Lihat dan perbarui data akun Anda.
                    </p>
                    <a href="<?= base_url('profile') ?>" class="btn btn-outline-warning w-100">
                        Profile Saya
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Logout -->
    <div class="text-center mt-4">
        <a href="<?= base_url('logout') ?>" class="btn btn-danger">
            Logout
        </a>
    </div>

</div>

</body>
</html>
