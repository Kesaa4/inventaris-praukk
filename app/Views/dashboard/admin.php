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
<?= view('layouts/header', ['title' => 'Dashboard Admin']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">
        <div class="content-wrapper fade-in">

            <!-- Header -->
            <div class="page-header">
                <h3>Dashboard Admin</h3>
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

            <!-- Grafik & Statistik Lanjutan -->
            <div class="row g-4 mb-4">
                <!-- Grafik Peminjaman per Bulan -->
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Trend Peminjaman (6 Bulan Terakhir)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartPeminjaman" height="80"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Barang Paling Populer -->
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-star me-2"></i>Barang Terpopuler</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($barangPopuler)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($barangPopuler as $index => $barang): ?>
                                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-bold"><?= $index + 1 ?>. <?= esc($barang['jenis_barang']) ?></div>
                                                <small class="text-muted"><?= esc($barang['merek_barang']) ?> - <?= esc($barang['kode_barang']) ?></small>
                                            </div>
                                            <span class="badge bg-primary rounded-pill"><?= $barang['total'] ?>x</span>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center">Belum ada data</p>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Paling Aktif -->
            <div class="row g-4 mb-4">
                <div class="col-12">
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
                                                <th width="50">Rank</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th width="150" class="text-center">Total Peminjaman</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($userAktif as $index => $user): ?>
                                                <tr>
                                                    <td class="text-center">
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
                                                        <strong><?= esc($user['nama'] ?? explode('@', $user['email'])[0]) ?></strong>
                                                    </td>
                                                    <td><?= esc($user['email']) ?></td>
                                                    <td class="text-center">
                                                        <span class="badge bg-primary"><?= $user['total'] ?> kali</span>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center">Belum ada data</p>
                            <?php endif ?>
                        </div>
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
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
