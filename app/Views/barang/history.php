<?= view('layouts/header', ['title' => 'Riwayat Peminjaman Barang']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">
        <div class="content-wrapper">

            <!-- Header -->
            <div class="page-header">
                <h3><i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman Barang</h3>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted mb-0">
                        <?= esc($barang['nama_kategori']) ?> - 
                        <?= esc($barang['merek_barang']) ?> - 
                        <?= esc($barang['tipe_barang']) ?>
                        <span class="badge bg-secondary"><?= esc($barang['kode_barang']) ?></span>
                    </p>
                    <a href="<?= base_url('barang') ?>" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>

            <!-- Statistik -->
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm border-primary">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle p-3 me-3">
                                    <i class="bi bi-arrow-repeat" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Total Peminjaman</h6>
                                    <h3 class="mb-0"><?= $totalPinjam ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm border-success">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success text-white rounded-circle p-3 me-3">
                                    <i class="bi bi-check-circle" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Dikembalikan</h6>
                                    <h3 class="mb-0"><?= $totalDikembalikan ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat -->
            <div class="card shadow-sm">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-primary text-center">
                            <tr>
                                <th class="text-nowrap">No</th>
                                <th class="text-nowrap">Peminjam</th>
                                <th class="text-nowrap">Tgl Pengajuan</th>
                                <th class="text-nowrap">Tgl Disetujui</th>
                                <th class="text-nowrap">Jatuh Tempo</th>
                                <th class="text-nowrap">Tgl Dikembalikan</th>
                                <th class="text-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($riwayat) > 0): ?>
                                <?php $no = 1; foreach ($riwayat as $r): ?>
                                    <?php
                                        $status = strtolower(trim($r['status']));
                                        $statusBadge = match ($status) {
                                            'disetujui'     => 'primary',
                                            'pengembalian'  => 'warning',
                                            'dikembalikan'  => 'success',
                                            'ditolak'       => 'danger',
                                            default         => 'secondary'
                                        };
                                    ?>
                                    <tr>
                                        <td class="text-center text-nowrap"><?= $no++ ?></td>
                                        <td class="text-nowrap">
                                            <strong><?= esc($r['nama'] ?? explode('@', $r['email'])[0]) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= esc($r['email']) ?></small>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <?= $r['tgl_pengajuan'] ? date('d-m-Y H:i', strtotime($r['tgl_pengajuan'])) : '-' ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <?= $r['tgl_disetujui'] ? date('d-m-Y H:i', strtotime($r['tgl_disetujui'])) : '-' ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <?= $r['tgl_jatuh_tempo'] ? date('d-m-Y H:i', strtotime($r['tgl_jatuh_tempo'])) : '-' ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <?= $r['tgl_disetujui_kembali'] ? date('d-m-Y H:i', strtotime($r['tgl_disetujui_kembali'])) : '-' ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <span class="badge bg-<?= $statusBadge ?>">
                                                <?= ucfirst($r['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-2 mb-0">Barang ini belum pernah dipinjam</p>
                                    </td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<?= view('layouts/footer') ?>
