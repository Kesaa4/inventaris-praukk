<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-dark bg-primary">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Admin Panel</span>
        <span class="text-white">
            <?= explode('@', session()->get('email'))[0] ?> 
            (<?= session()->get('role') ?>)
        </span>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-white border-end min-vh-100 p-3">
            <h6 class="text-muted mb-3">MENU</h6>

            <div class="list-group list-group-flush">
                <a href="<?= base_url('pinjam') ?>" class="list-group-item list-group-item-action">
                    Data Peminjaman
                </a>
                <a href="<?= base_url('barang') ?>" class="list-group-item list-group-item-action">
                    Data Barang
                </a>

                <?php if (session()->get('role') === 'admin'): ?>
                    <a href="<?= base_url('user') ?>" class="list-group-item list-group-item-action">
                        Manajemen User
                    </a>
                    <a href="<?= base_url('activity-log') ?>" class="list-group-item list-group-item-action">
                        Activity Log
                    </a>
                <?php endif ?>

                <a href="<?= base_url('profile') ?>" class="list-group-item list-group-item-action">
                    Profile Saya
                </a>
                <a href="<?= base_url('logout') ?>" class="list-group-item list-group-item-action text-danger">
                    Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">

            <h3 class="mb-4">Dashboard</h3>

            <!-- Statistik -->
            <div class="row g-3">

                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Total Barang</h6>
                            <h3><?= $totalBarang ?></h3>
                        </div>
                    </div>
                </div>

                <!-- Placeholder card (bisa dikembangkan) -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Status Login</h6>
                            <p class="mb-0">
                                Login sebagai <b><?= session()->get('role') ?></b>
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

</body>
</html>
