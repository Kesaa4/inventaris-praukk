<?= view('layouts/dashboard_header', ['title' => 'Dashboard Petugas']) ?>

<!-- Sidebar -->
<?= view('layouts/sidebar') ?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Header -->
        <div class="dashboard-header mb-4">
            <h1 class="mb-0">Selamat Bertugas, <?= esc($nama) ?>!</h1>
            <p class="text-muted mb-0">Kelola data barang dan peminjaman dari dashboard ini</p>
        </div>

        <!-- Welcome Card -->
        <div class="card mb-4" style="background: var(--warning); color: white; border: none;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <i class="bi bi-clipboard-check-fill" style="font-size: 4rem;"></i>
                    </div>
                    <div class="col-md-10">
                        <h5 class="mb-2">Dashboard Petugas</h5>
                        <p class="mb-0">Pastikan semua transaksi tercatat dengan baik dan proses persetujuan dilakukan dengan cepat.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="row mb-4">
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

        <!-- Statistik Barang -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <h5 class="mb-3"><i class="bi bi-box-seam me-2"></i>Statistik Barang</h5>
            </div>

            <!-- Baris 1 -->
            <div class="col-12 col-md-6">
                <div class="stats-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Barang</h6>
                            <h2 class="mb-0 fw-bold"><?= $totalBarang ?? 0 ?></h2>
                            <small class="text-muted">Semua barang</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="stats-card success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Tersedia</h6>
                            <h2 class="mb-0 fw-bold"><?= $barangTersedia ?? 0 ?></h2>
                            <small class="text-muted">Siap dipinjam</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Baris 2 -->
            <div class="col-12 col-md-6">
                <div class="stats-card warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Dipinjam</h6>
                            <h2 class="mb-0 fw-bold"><?= $barangDipinjam ?? 0 ?></h2>
                            <small class="text-muted">Sedang dipinjam</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="stats-card danger">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Tidak Tersedia</h6>
                            <h2 class="mb-0 fw-bold"><?= $barangTidakTersedia ?? 0 ?></h2>
                            <small class="text-muted">Rusak/Hilang</small>
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

            <!-- Baris 1 -->
            <div class="col-6 col-md-4">
                <div class="stats-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total</h6>
                            <h2 class="mb-0 fw-bold"><?= $totalPeminjaman ?? 0 ?></h2>
                            <small class="text-muted">Semua transaksi</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="stats-card warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Menunggu</h6>
                            <h2 class="mb-0 fw-bold"><?= $peminjamanMenunggu ?? 0 ?></h2>
                            <small class="text-muted">Perlu disetujui</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="stats-card info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Disetujui</h6>
                            <h2 class="mb-0 fw-bold"><?= $peminjamanDisetujui ?? 0 ?></h2>
                            <small class="text-muted">Sedang dipinjam</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Baris 2 -->
            <div class="col-6 col-md-4">
                <div class="stats-card success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Dikembalikan</h6>
                            <h2 class="mb-0 fw-bold"><?= $peminjamanDikembalikan ?? 0 ?></h2>
                            <small class="text-muted">Selesai</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-check2-all"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="stats-card danger">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Ditolak</h6>
                            <h2 class="mb-0 fw-bold"><?= $peminjamanDitolak ?? 0 ?></h2>
                            <small class="text-muted">Tidak disetujui</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-x-octagon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Lainnya -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Lainnya</h5>
            </div>

            <div class="col-12 col-md-6">
                <div class="stats-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Kategori</h6>
                            <h2 class="mb-0 fw-bold"><?= $totalKategori ?? 0 ?></h2>
                            <small class="text-muted">Kategori barang</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-tags"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="stats-card success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Tingkat Pengembalian</h6>
                            <h2 class="mb-0 fw-bold">
                                <?php 
                                $tingkatPengembalian = $totalPeminjaman > 0 
                                    ? round(($peminjamanDikembalikan / $totalPeminjaman) * 100, 1) 
                                    : 0;
                                echo $tingkatPengembalian;
                                ?>%
                            </h2>
                            <small class="text-muted">Barang dikembalikan</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-percent"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card" style="border-left: 4px solid var(--success); height: 100%;">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1"><i class="bi bi-printer me-2"></i>Cetak Laporan Barang</h6>
                            <small class="text-muted">Laporan data barang</small>
                        </div>
                        <a href="<?= base_url('barang/export-excel') ?>" class="btn btn-success btn-sm">
                            <i class="bi bi-printer"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card" style="border-left: 4px solid var(--danger); height: 100%;">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1"><i class="bi bi-printer me-2"></i>Cetak Laporan Peminjaman</h6>
                            <small class="text-muted">Laporan peminjaman</small>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalCetakLaporan">
                            <i class="bi bi-printer"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik & Statistik -->
        <div class="row g-4 mb-4">
            <!-- Pie Chart Status Barang -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Distribusi Status Barang</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartStatusBarang" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Pie Chart Status Peminjaman -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-pie-chart-fill me-2"></i>Status Peminjaman</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartStatusPeminjaman" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Peminjaman Menunggu Persetujuan -->
        <?php if (!empty($peminjamanMenungguList)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-hourglass-split me-2"></i>Peminjaman Menunggu Persetujuan</h5>
                        <a href="<?= base_url('pinjam') ?>" class="btn btn-sm btn-warning">
                            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Peminjam</th>
                                        <th>Barang</th>
                                        <th>Durasi</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($peminjamanMenungguList as $pinjam): ?>
                                        <tr>
                                            <td class="text-nowrap">
                                                <small><?= date('d M Y', strtotime($pinjam['tgl_pengajuan'])) ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?= esc($pinjam['nama'] ?? explode('@', $pinjam['email'])[0]) ?></div>
                                                <small class="text-muted"><?= esc($pinjam['email']) ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?= esc($pinjam['nama_kategori']) ?></div>
                                                <small class="text-muted"><?= esc($pinjam['merek_barang']) ?> - <?= esc($pinjam['kode_barang']) ?></small>
                                            </td>
                                            <td>
                                                <small><?= isset($pinjam['durasi_pinjam']) ? $pinjam['durasi_pinjam'] . ' hari' : '-' ?></small>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= base_url('pinjam/edit/' . $pinjam['id_pinjam']) ?>" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil-square"></i> Proses
                                                </a>
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

        <!-- Peminjaman Terbaru -->
        <?php if (!empty($peminjamanTerbaru)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman Terbaru</h5>
                        <a href="<?= base_url('pinjam') ?>" class="btn btn-sm btn-outline-primary">
                            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Peminjam</th>
                                        <th>Barang</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($peminjamanTerbaru as $pinjam): ?>
                                        <tr>
                                            <td class="text-nowrap">
                                                <small><?= date('d M Y', strtotime($pinjam['tgl_pengajuan'])) ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?= esc($pinjam['nama'] ?? explode('@', $pinjam['email'])[0]) ?></div>
                                                <small class="text-muted"><?= esc($pinjam['email']) ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?= esc($pinjam['nama_kategori']) ?></div>
                                                <small class="text-muted"><?= esc($pinjam['merek_barang']) ?></small>
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Pie Chart Status Barang
const ctxStatusBarang = document.getElementById('chartStatusBarang');
new Chart(ctxStatusBarang, {
    type: 'doughnut',
    data: {
        labels: ['Tersedia', 'Dipinjam', 'Tidak Tersedia'],
        datasets: [{
            data: [
                <?= $barangTersedia ?? 0 ?>,
                <?= $barangDipinjam ?? 0 ?>,
                <?= $barangTidakTersedia ?? 0 ?>
            ],
            backgroundColor: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(220, 53, 69, 0.8)'
            ],
            borderColor: [
                'rgba(40, 167, 69, 1)',
                'rgba(255, 193, 7, 1)',
                'rgba(220, 53, 69, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                        return label + ': ' + value + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// Pie Chart Status Peminjaman
const ctxStatusPeminjaman = document.getElementById('chartStatusPeminjaman');
new Chart(ctxStatusPeminjaman, {
    type: 'pie',
    data: {
        labels: ['Menunggu', 'Disetujui', 'Dikembalikan', 'Ditolak'],
        datasets: [{
            data: [
                <?= $peminjamanMenunggu ?? 0 ?>,
                <?= $peminjamanDisetujui ?? 0 ?>,
                <?= $peminjamanDikembalikan ?? 0 ?>,
                <?= $peminjamanDitolak ?? 0 ?>
            ],
            backgroundColor: [
                'rgba(255, 193, 7, 0.8)',
                'rgba(23, 162, 184, 0.8)',
                'rgba(40, 167, 69, 0.8)',
                'rgba(220, 53, 69, 0.8)'
            ],
            borderColor: [
                'rgba(255, 193, 7, 1)',
                'rgba(23, 162, 184, 1)',
                'rgba(40, 167, 69, 1)',
                'rgba(220, 53, 69, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                        return label + ': ' + value + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});
</script>

<?= view('layouts/dashboard_footer') ?>
