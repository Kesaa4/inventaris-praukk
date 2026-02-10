<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peminjam</title>

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
                <h3><i class="bi bi-speedometer2 me-2"></i>Dashboard Peminjam</h3>
                <p class="text-muted">Selamat datang, <?= esc($nama) ?></p>
            </div>

            <!-- Welcome Card -->
            <div class="card mb-4" style="background: var(--success); color: white; border: none;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h4 class="mb-2">Halo, <?= esc($nama) ?></h4>
                            <p class="mb-0">Silakan kelola peminjaman barang Anda di sini. Pastikan mengembalikan barang tepat waktu.</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <i class="bi bi-person-check-fill" style="font-size: 4rem; opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Access Menu -->
            <div class="row g-4">
                <div class="col-12">
                    <h5 class="mb-3"><i class="bi bi-lightning-charge me-2"></i>Menu Utama</h5>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="icon mx-auto mb-3" style="width: 60px; height: 60px; background: var(--success); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-box-seam" style="font-size: 1.5rem; color: white;"></i>
                            </div>
                            <h5 class="card-title">Lihat Barang</h5>
                            <p class="text-muted">Lihat daftar barang yang tersedia untuk dipinjam</p>
                            <a href="<?= base_url('barang') ?>" class="btn btn-success w-100">
                                <i class="bi bi-arrow-right me-1"></i>Lihat Barang
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="icon mx-auto mb-3" style="width: 60px; height: 60px; background: var(--primary); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-arrow-left-right" style="font-size: 1.5rem; color: white;"></i>
                            </div>
                            <h5 class="card-title">Peminjaman Saya</h5>
                            <p class="text-muted">Cek status dan riwayat peminjaman Anda</p>
                            <a href="<?= base_url('pinjam') ?>" class="btn btn-primary w-100">
                                <i class="bi bi-arrow-right me-1"></i>Peminjaman Saya
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="icon mx-auto mb-3" style="width: 60px; height: 60px; background: var(--warning); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person-circle" style="font-size: 1.5rem; color: white;"></i>
                            </div>
                            <h5 class="card-title">Profile Saya</h5>
                            <p class="text-muted">Lihat dan perbarui data akun Anda</p>
                            <a href="<?= base_url('profile') ?>" class="btn btn-warning w-100">
                                <i class="bi bi-arrow-right me-1"></i>Profile Saya
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card" style="border-left: 4px solid var(--success);">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-2"></i>Informasi Penting</h6>
                            <ul class="mb-0 text-muted">
                                <li>Pastikan barang yang dipinjam dalam kondisi baik</li>
                                <li>Kembalikan barang sesuai waktu yang ditentukan</li>
                                <li>Laporkan jika ada kerusakan pada barang</li>
                                <li>Hubungi petugas jika ada kendala</li>
                            </ul>
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
