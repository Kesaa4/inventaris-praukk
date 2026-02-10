<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="/dashboard">
            <i class="bi bi-box-seam me-2"></i>Sistem Peminjaman
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="/dashboard">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/barang">
                        <i class="bi bi-box me-1"></i>Barang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/kategori">
                        <i class="bi bi-tags me-1"></i>Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/pinjam">
                        <i class="bi bi-arrow-left-right me-1"></i>Peminjaman
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/user">
                        <i class="bi bi-people me-1"></i>User
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/activity-log">
                        <i class="bi bi-clock-history me-1"></i>Activity Log
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i><?= esc($nama) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">
        <div class="content-wrapper fade-in">

            <!-- Header -->
            <div class="page-header">
                <h3><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</h3>
                <p class="text-muted">Selamat datang, <?= esc($nama) ?></p>
            </div>

            <!-- Statistik Barang -->
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <h5 class="mb-3"><i class="bi bi-graph-up me-2"></i>Statistik Barang</h5>
                </div>

                <!-- Total Barang -->
                <div class="col-12 col-sm-6 col-lg-3">
                                        <div class="stats-card primary">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-muted mb-2">Total Barang</h6>
                                                    <h2 class="mb-0 fw-bold"><?= $totalBarang ?></h2>
                                                    <small class="text-muted">Semua barang</small>
                                                </div>
                                                <div class="icon">
                                                    <i class="bi bi-box-seam"></i>
                                                </div>
                                            </div>
                                        </div>
                </div>

                <!-- Barang Tersedia -->
                <div class="col-12 col-sm-6 col-lg-3">
                                        <div class="stats-card success">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-muted mb-2">Barang Tersedia</h6>
                                                    <h2 class="mb-0 fw-bold"><?= $barangTersedia ?></h2>
                                                    <small class="text-muted">Siap dipinjam</small>
                                                </div>
                                                <div class="icon">
                                                    <i class="bi bi-check-circle"></i>
                                                </div>
                                            </div>
                                        </div>
                </div>

                <!-- Barang Dipinjam -->
                <div class="col-12 col-sm-6 col-lg-3">
                                        <div class="stats-card warning">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-muted mb-2">Barang Dipinjam</h6>
                                                    <h2 class="mb-0 fw-bold"><?= $barangDipinjam ?></h2>
                                                    <small class="text-muted">Sedang dipinjam</small>
                                                </div>
                                                <div class="icon">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </div>
                                            </div>
                                        </div>
                </div>

                <!-- Barang Tidak Tersedia -->
                <div class="col-12 col-sm-6 col-lg-3">
                                        <div class="stats-card danger">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-muted mb-2">Tidak Tersedia</h6>
                                                    <h2 class="mb-0 fw-bold"><?= $barangTidakTersedia ?></h2>
                                                    <small class="text-muted">Rusak atau Lainnya</small>
                                                </div>
                                                <div class="icon">
                                                    <i class="bi bi-x-circle"></i>
                                                </div>
                                            </div>
                                        </div>
                </div>
            </div>

            <!-- Statistik Peminjaman -->
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <h5 class="mb-3"><i class="bi bi-graph-up me-2"></i>Statistik Peminjaman</h5>
                </div>

                <!-- Total Peminjaman -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stats-card primary">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total Peminjaman</h6>
                                <h2 class="mb-0 fw-bold"><?= $totalPeminjaman ?></h2>
                                <small class="text-muted">Semua transaksi</small>
                            </div>
                            <div class="icon">
                                <i class="bi bi-clipboard-data"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menunggu Persetujuan -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stats-card warning">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Menunggu</h6>
                                <h2 class="mb-0 fw-bold"><?= $peminjamanMenunggu ?></h2>
                                <small class="text-muted">Perlu disetujui</small>
                            </div>
                            <div class="icon">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sedang Dipinjam -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stats-card info">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Sedang Dipinjam</h6>
                                <h2 class="mb-0 fw-bold"><?= $peminjamanDisetujui ?></h2>
                                <small class="text-muted">Belum kembali</small>
                            </div>
                            <div class="icon">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sudah Dikembalikan -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stats-card success">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Dikembalikan</h6>
                                <h2 class="mb-0 fw-bold"><?= $peminjamanDikembalikan ?></h2>
                                <small class="text-muted">Selesai</small>
                            </div>
                            <div class="icon">
                                <i class="bi bi-check2-all"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik User -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="stats-card primary">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total User</h6>
                                <h2 class="mb-0 fw-bold"><?= $totalUser ?></h2>
                                <small class="text-muted">Pengguna sistem</small>
                            </div>
                            <div class="icon">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
