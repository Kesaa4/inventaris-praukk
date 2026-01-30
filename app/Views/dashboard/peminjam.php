<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Peminjam</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-dark bg-success">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Dashboard Peminjam</span>
        <span class="text-white">
            <?= explode('@', session()->get('email'))[0] ?>
            (<?= session()->get('role') ?>)
        </span>
    </div>
</nav>

<div class="container mt-4">

    <!-- Welcome Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="mb-1">
                Selamat datang, 
                <b><?= explode('@', session()->get('email'))[0] ?></b> 👋
            </h4>
            <p class="text-muted mb-0">
                Silakan kelola peminjaman barang Anda di sini.
            </p>
        </div>
    </div>

    <!-- Menu Cards -->
    <div class="row g-3">

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5>Lihat Barang</h5>
                    <p class="text-muted">
                        Lihat daftar barang yang tersedia untuk dipinjam.
                    </p>
                    <a href="<?= base_url('barang') ?>" class="btn btn-outline-success w-100">
                        Lihat Barang
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5>Peminjaman Saya</h5>
                    <p class="text-muted">
                        Cek status dan riwayat peminjaman Anda.
                    </p>
                    <a href="<?= base_url('pinjam') ?>" class="btn btn-outline-success w-100">
                        Peminjaman Saya
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
                    <a href="<?= base_url('profile') ?>" class="btn btn-outline-success w-100">
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
