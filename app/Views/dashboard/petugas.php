<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<!-- Navbar -->
<?= view('layouts/header', ['title' => 'Dashboard Petugas']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">
        <div class="content-wrapper fade-in">

            <!-- Header -->
            <div class="page-header">
                <h3><i class="bi bi-speedometer2 me-2"></i>Dashboard Petugas</h3>
                <p class="text-muted">Selamat bertugas, <?= esc($nama) ?></p>
            </div>

            <!-- Welcome Card -->
            <div class="card mb-4" style="background: var(--warning); color: white; border: none;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h4 class="mb-2">Selamat Bertugas, <?= esc($nama) ?></h4>
                            <p class="mb-0">Kelola data barang dan peminjaman dari dashboard ini. Pastikan semua transaksi tercatat dengan baik.</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <i class="bi bi-clipboard-check-fill" style="font-size: 4rem; opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Peminjaman -->
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <h5 class="mb-3"><i class="bi bi-bar-chart me-2"></i>Statistik Peminjaman</h5>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bi bi-list-ul text-primary" style="font-size: 2rem;"></i>
                            <h3 class="mt-2 mb-0"><?= $totalPeminjaman ?></h3>
                            <p class="text-muted mb-0">Total Peminjaman</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bi bi-clock-history text-warning" style="font-size: 2rem;"></i>
                            <h3 class="mt-2 mb-0"><?= $peminjamanMenunggu ?></h3>
                            <p class="text-muted mb-0">Menunggu</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle text-info" style="font-size: 2rem;"></i>
                            <h3 class="mt-2 mb-0"><?= $peminjamanDisetujui ?></h3>
                            <p class="text-muted mb-0">Disetujui</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bi bi-check-all text-success" style="font-size: 2rem;"></i>
                            <h3 class="mt-2 mb-0"><?= $peminjamanDikembalikan ?></h3>
                            <p class="text-muted mb-0">Dikembalikan</p>
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
                            <h5 class="card-title">Data Barang</h5>
                            <p class="text-muted">Lihat dan kelola data barang inventaris</p>
                            <a href="<?= base_url('barang') ?>" class="btn btn-success w-100">
                                <i class="bi bi-arrow-right me-1"></i>Lihat Data Barang
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="icon mx-auto mb-3" style="width: 60px; height: 60px; background: var(--warning); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-arrow-left-right" style="font-size: 1.5rem; color: white;"></i>
                            </div>
                            <h5 class="card-title">Kelola Peminjaman</h5>
                            <p class="text-muted">Proses, setujui, dan kelola peminjaman</p>
                            <a href="<?= base_url('pinjam') ?>" class="btn btn-warning w-100">
                                <i class="bi bi-arrow-right me-1"></i>Kelola Peminjaman
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="icon mx-auto mb-3" style="width: 60px; height: 60px; background: var(--primary); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person-circle" style="font-size: 1.5rem; color: white;"></i>
                            </div>
                            <h5 class="card-title">Profile Saya</h5>
                            <p class="text-muted">Lihat dan perbarui data akun Anda</p>
                            <a href="<?= base_url('profile') ?>" class="btn btn-primary w-100">
                                <i class="bi bi-arrow-right me-1"></i>Profile Saya
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="icon mx-auto mb-3" style="width: 60px; height: 60px; background: var(--danger); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-printer" style="font-size: 1.5rem; color: white;"></i>
                            </div>
                            <h5 class="card-title">Cetak Laporan</h5>
                            <p class="text-muted">Cetak laporan peminjaman barang</p>
                            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modalCetakLaporan">
                                <i class="bi bi-printer me-1"></i>Cetak Laporan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card" style="border-left: 4px solid var(--warning);">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-2"></i>Tugas Petugas</h6>
                            <ul class="mb-0 text-muted">
                                <li>Verifikasi kondisi barang sebelum dan sesudah peminjaman</li>
                                <li>Proses persetujuan peminjaman dengan cepat</li>
                                <li>Catat semua transaksi dengan detail</li>
                                <li>Laporkan jika ada kerusakan atau kehilangan barang</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Cetak Laporan -->
<div class="modal fade" id="modalCetakLaporan" tabindex="-1" aria-labelledby="modalCetakLaporanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCetakLaporanLabel">
                    <i class="bi bi-printer me-2"></i>Cetak Laporan Peminjaman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('dashboard/cetak-laporan') ?>" method="GET" target="_blank">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Filter Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="menunggu">Menunggu</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="dikembalikan">Dikembalikan</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tgl_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai">
                        <small class="text-muted">Kosongkan jika tidak ingin filter tanggal</small>
                    </div>

                    <div class="mb-3">
                        <label for="tgl_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai">
                        <small class="text-muted">Kosongkan jika tidak ingin filter tanggal</small>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Laporan akan dibuka di tab baru dan siap untuk dicetak</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-printer me-1"></i>Cetak Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
