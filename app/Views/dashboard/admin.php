<?= view('layouts/dashboard_header', ['title' => 'Dashboard Admin']) ?>

<!-- Sidebar -->
<?= view('layouts/sidebar') ?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Header -->
        <div class="dashboard-header mb-4">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-speedometer2 me-2 text-primary" style="font-size: 1.5rem;"></i>
                <span class="text-muted">Dashboard Admin</span>
            </div>
            <h1 class="mb-0">Selamat datang, <?= esc($nama) ?></h1>
        </div>

        <!-- Statistik Barang -->
        <div class="row g-3 g-md-4 mb-4">
            <div class="col-12">
                <h5 class="mb-3"><i class="bi bi-box-seam me-2"></i>Statistik Barang</h5>
            </div>

            <!-- Baris 1 -->
            <div class="col-12 col-sm-6 col-lg-6">
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

            <div class="col-12 col-sm-6 col-lg-6">
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
            <div class="col-12 col-sm-6 col-lg-6">
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

            <div class="col-12 col-sm-6 col-lg-6">
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
        <div class="row g-3 g-md-4 mb-4">
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
                            <small class="text-muted d-none d-sm-inline">Semua transaksi</small>
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
                            <small class="text-muted d-none d-sm-inline">Perlu disetujui</small>
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
                            <small class="text-muted d-none d-sm-inline">Sedang dipinjam</small>
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
                            <small class="text-muted d-none d-sm-inline">Selesai</small>
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
                            <small class="text-muted d-none d-sm-inline">Tidak disetujui</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-x-octagon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Lainnya -->
        <div class="row g-3 g-md-4 mb-4">
            <div class="col-12">
                <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Lainnya</h5>
            </div>

            <div class="col-6 col-md-4">
                <div class="stats-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total User</h6>
                            <h2 class="mb-0 fw-bold"><?= $totalUser ?? 0 ?></h2>
                            <small class="text-muted d-none d-sm-inline">Pengguna sistem</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="stats-card info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Kategori</h6>
                            <h2 class="mb-0 fw-bold"><?= $totalKategori ?? 0 ?></h2>
                            <small class="text-muted d-none d-sm-inline">Kategori barang</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-tags"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
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
                            <small class="text-muted d-none d-sm-inline">Barang dikembalikan</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-percent"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Charts Bersebelahan -->
        <div class="row g-3 g-md-4 mb-4">
            <!-- Pie Chart Status Barang -->
            <div class="col-12 col-lg-6">
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
            <div class="col-12 col-lg-6">
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

        <!-- Barang Terpopuler & User Paling Aktif Bersebelahan -->
        <div class="row g-3 g-md-4 mb-4">
            <!-- Barang Paling Populer -->
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-star me-2"></i>Barang Terpopuler</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($barangPopuler)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($barangPopuler as $index => $barang): ?>
                                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1 me-2">
                                            <div class="fw-bold"><?= $index + 1 ?>. <?= esc($barang['nama_kategori']) ?></div>
                                            <small class="text-muted d-block text-truncate"><?= esc($barang['merek_barang']) ?> - <?= esc($barang['kode_barang']) ?></small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill"><?= $barang['total'] ?>x</span>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-center mb-0">Belum ada data</p>
                        <?php endif ?>
                    </div>
                </div>
            </div>

            <!-- User Paling Aktif -->
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-trophy me-2"></i>User Paling Aktif</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($userAktif)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50" class="d-none d-sm-table-cell">Rank</th>
                                            <th>Nama</th>
                                            <th width="100" class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($userAktif as $index => $user): ?>
                                            <tr>
                                                <td class="text-center d-none d-sm-table-cell">
                                                    <?php if ($index === 0): ?>
                                                        <i class="bi bi-trophy-fill text-warning" style="font-size: 1.5rem;"></i>
                                                    <?php elseif ($index === 1): ?>
                                                        <i class="bi bi-trophy-fill text-secondary" style="font-size: 1.3rem;"></i>
                                                    <?php elseif ($index === 2): ?>
                                                        <i class="bi bi-trophy-fill text-danger" style="font-size: 1.1rem;"></i>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary"><?= $index + 1 ?></span>
                                                    <?php endif ?>
                                                </td>
                                                <td>
                                                    <div class="fw-bold"><?= esc($user['nama'] ?? explode('@', $user['email'])[0]) ?></div>
                                                    <small class="text-muted d-none d-md-inline"><?= esc($user['email']) ?></small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary"><?= $user['total'] ?>x</span>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-center mb-0">Belum ada data</p>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trend Peminjaman Full Width -->
        <div class="row g-3 g-md-4 mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Trend Peminjaman (6 Bulan Terakhir)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartPeminjaman" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Peminjaman Terbaru Full Width -->
        <div class="row g-3 g-md-4 mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Peminjaman Terbaru</h5>
                        <a href="<?= base_url('pinjam') ?>" class="btn btn-sm btn-outline-primary">
                            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($peminjamanTerbaru)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Peminjam</th>
                                            <th class="d-none d-md-table-cell">Barang</th>
                                            <th class="d-none d-lg-table-cell">Merek</th>
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
                                                    <small class="text-muted d-none d-sm-inline"><?= esc($pinjam['email']) ?></small>
                                                </td>
                                                <td class="d-none d-md-table-cell">
                                                    <div class="fw-bold"><?= esc($pinjam['nama_kategori']) ?></div>
                                                    <small class="text-muted"><?= esc($pinjam['kode_barang']) ?></small>
                                                </td>
                                                <td class="d-none d-lg-table-cell"><?= esc($pinjam['merek_barang']) ?></td>
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
                        <?php else: ?>
                            <p class="text-muted text-center mb-0 py-3">Belum ada data</p>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Grafik Peminjaman per Bulan
const ctx = document.getElementById('chartPeminjaman');
const chartData = <?= json_encode($peminjamanPerBulan) ?>;

new Chart(ctx, {
    type: 'line',
    data: {
        labels: chartData.map(item => item.bulan),
        datasets: [{
            label: 'Jumlah Peminjaman',
            data: chartData.map(item => item.total),
            borderColor: '#5a67d8',
            backgroundColor: 'rgba(90, 103, 216, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: '#5a67d8',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 14
                },
                bodyFont: {
                    size: 13
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

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

<script>
// Grafik Peminjaman per Bulan
const ctx = document.getElementById('chartPeminjaman');
const chartData = <?= json_encode($peminjamanPerBulan) ?>;

new Chart(ctx, {
    type: 'line',
    data: {
        labels: chartData.map(item => item.bulan),
        datasets: [{
            label: 'Jumlah Peminjaman',
            data: chartData.map(item => item.total),
            borderColor: '#5a67d8',
            backgroundColor: 'rgba(90, 103, 216, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: '#5a67d8',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 14
                },
                bodyFont: {
                    size: 13
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>

<?= view('layouts/dashboard_footer') ?>
