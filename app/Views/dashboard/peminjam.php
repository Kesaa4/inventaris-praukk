<?= view('layouts/dashboard_header', ['title' => 'Dashboard Peminjam']) ?>

<!-- Sidebar -->
<?= view('layouts/sidebar') ?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Header -->
        <div class="dashboard-header mb-4">
            <h1 class="mb-0">Selamat Datang, <?= esc($nama) ?>!</h1>
            <p class="text-muted mb-0">Kelola peminjaman barang Anda dengan mudah</p>
        </div>

        <!-- Welcome Card -->
        <div class="card mb-4" style="background: var(--success); color: white; border: none;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <i class="bi bi-person-check-fill" style="font-size: 4rem;"></i>
                    </div>
                    <div class="col-md-10">
                        <h5 class="mb-2">Dashboard Peminjam</h5>
                        <p class="mb-0">Lihat status peminjaman Anda dan pastikan mengembalikan barang tepat waktu.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="row mb-4">
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

        <!-- Statistik Peminjaman Saya -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <h5 class="mb-3"><i class="bi bi-bar-chart me-2"></i>Statistik Peminjaman Saya</h5>
            </div>

            <div class="col-6 col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-list-ul text-primary" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0"><?= $totalPeminjamanSaya ?? 0 ?></h3>
                        <p class="text-muted mb-0">Total</p>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-clock-history text-warning" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0"><?= $peminjamanSaya['menunggu'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Menunggu</p>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle text-info" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0"><?= $peminjamanSaya['disetujui'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Disetujui</p>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="bi bi-check-all text-success" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0"><?= $peminjamanSaya['dikembalikan'] ?? 0 ?></h3>
                        <p class="text-muted mb-0">Dikembalikan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Peminjaman Terbaru -->
        <?php if (!empty($riwayatTerbaru)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman Terbaru</h5>
                        <a href="<?= base_url('pinjam') ?>" class="btn btn-sm btn-primary">
                            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Barang</th>
                                        <th>Durasi</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($riwayatTerbaru as $pinjam): ?>
                                        <tr>
                                            <td class="text-nowrap">
                                                <small><?= date('d M Y', strtotime($pinjam['tgl_pengajuan'])) ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?= esc($pinjam['nama_kategori']) ?></div>
                                                <small class="text-muted"><?= esc($pinjam['merek_barang']) ?> - <?= esc($pinjam['kode_barang']) ?></small>
                                            </td>
                                            <td>
                                                <small><?= isset($pinjam['durasi_pinjam']) ? $pinjam['durasi_pinjam'] . ' hari' : '-' ?></small>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $badgeClass = match($pinjam['status']) {
                                                    'menunggu' => 'bg-warning',
                                                    'disetujui' => 'bg-info',
                                                    'dikembalikan' => 'bg-success',
                                                    'ditolak' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= ucfirst($pinjam['status']) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>
    </div>
</div>

<?= view('layouts/dashboard_footer') ?>
