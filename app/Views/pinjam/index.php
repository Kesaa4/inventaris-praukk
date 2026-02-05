<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- JS 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Data Peminjaman</h4>

        <div>
            <a href="/dashboard" class="btn btn-secondary btn-sm">
                Kembali Ke Dashboard
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <!-- Filter -->
    <form method="get" class="card shadow-sm mb-4">

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Cari Data</label>
                    <input type="text" name="keyword" class="form-control"
                        value="<?= esc(request()->getGet('keyword')) ?>" placeholder="Barang atau peminjam...">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="menunggu" <?= request()->getGet('status')=='menunggu'?'selected':'' ?>>Menunggu</option>
                        <option value="dipinjam" <?= request()->getGet('status')=='dipinjam'?'selected':'' ?>>Dipinjam</option>
                        <option value="dikembalikan" <?= request()->getGet('status')=='dikembalikan'?'selected':'' ?>>Dikembalikan</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date" name="tgl_pinjam" class="form-control"
                        value="<?= esc(request()->getGet('tgl_pinjam')) ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Kembali</label>
                    <input type="date" name="tgl_kembali" class="form-control"
                        value="<?= esc(request()->getGet('tgl_kembali')) ?>">
                </div>

            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <div class="d-flex justify-content-start align-items-center">
                <div class="d-flex gap-2">
                    <button class="btn btn-primary">Cari</button>
                    <a href="<?= site_url('pinjam') ?>" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
            <div class="d-flex justify-content-end align-items-center gap-2">
                <?php if (in_array(session('role'), ['admin','peminjam','petugas'])): ?>
                        <a href="/pinjam/create" class="btn btn-success btn-sm">
                            Ajukan Pinjaman
                        </a>
                <?php endif ?>
                
                <?php if (session('role') === 'admin'): ?>
                    <div class="d-flex gap-2">
                        <a href="<?= site_url('pinjam/trash') ?>" class="btn btn-danger btn-sm">
                            Peminjaman Terhapus
                        </a>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-primary text-center">
                        <tr class="align-middle">
                            <th>Barang</th>
                            <th>Merek</th>
                            <th>Tipe</th>
                            <th>Peminjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <?php if (in_array(session('role'), ['admin','petugas'])): ?>
                                <th style="width:200px;">Aksi</th>
                            <?php endif ?>
                            <th>Pengembalian</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (count($pinjam) > 0): ?>
                    <?php foreach ($pinjam as $p): ?>

                        <?php
                            // Badge status
                            $status = strtolower(trim($p['status']));
                            $statusBadge = match ($status) {
                                'disetujui'     => 'primary',
                                'pengembalian' => 'warning',
                                'dikembalikan' => 'success',
                                'ditolak'      => 'danger',
                                default        => 'secondary'
                            };
                        ?>

                        <tr>
                            <td>
                                <strong><?= esc($p['jenis_barang']) ?></strong>
                            </td>

                            <td class="text-center">
                                <?= esc($p['merek_barang']) ?>
                            </td>

                            <td class="text-center">
                                <?= esc($p['tipe_barang']) ?>
                            </td>

                            <td>
                                <?= esc(explode('@', $p['email'])[0]) ?>
                            </td>

                            <td class="text-center">
                                <?= date('d-m-Y', strtotime($p['tgl_pinjam'])) ?>
                            </td>

                            <td class="text-center">
                                <?php if ($status === 'dikembalikan'): ?>
                                    <?= date('d-m-Y', strtotime($p['tgl_kembali'])) ?>
                                <?php elseif ($status === 'pengembalian'): ?>
                                    <span class="fw-semibold">
                                        Menunggu konfirmasi
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif ?>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-<?= $statusBadge ?>">
                                    <?= ucfirst($p['status']) ?>
                                </span>
                            </td>

                            <?php if (in_array(session('role'), ['admin','petugas'])): ?>
                            <td class="text-center">
                                <a href="/pinjam/edit/<?= $p['id_pinjam'] ?>"
                                   class="btn btn-warning btn-sm mb-1">
                                    Ubah Status
                                </a>

                                <?php if (session('role') === 'admin'): ?>
                                    <a href="/pinjam/delete/<?= $p['id_pinjam'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin hapus data ini?')">
                                        Hapus
                                    </a>
                                <?php endif ?>
                            </td>
                            <?php endif ?>

                            <td class="text-center">

                                <!-- PEMINJAM -->
                                <?php if (session('role') === 'peminjam' && $status === 'disetujui'): ?>
                                    <form action="/pinjam/return/<?= $p['id_pinjam'] ?>" method="post">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            Ajukan Pengembalian
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <!-- ADMIN / PETUGAS -->
                                <?php if (in_array(session('role'), ['admin','petugas']) && $status === 'pengembalian'): ?>
                                    <a href="/pinjam/return-check/<?= $p['id_pinjam'] ?>"
                                       class="btn btn-primary btn-sm">
                                        Cek Pengembalian
                                    </a>
                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endforeach ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="12" class="text-center text-muted">
                            Data peminjaman kosong
                        </td>
                    </tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            Total Data: <b><?= $pager->getTotal('pinjam') ?></b> |
            Per Page: <b><?= $pager->getPerPage('pinjam') ?></b> |
            Page: <b><?= $pager->getCurrentPage('pinjam') ?></b> /
            <?= $pager->getPageCount('pinjam') ?>
        </div>

        <div>
            <?php
                $currentPage = $pager->getCurrentPage('pinjam');
                $pageCount   = $pager->getPageCount('pinjam');
                $range       = 1; // kiri + current + kanan = 3 halaman

                $start = max(1, $currentPage - $range);
                $end   = min($pageCount, $currentPage + $range);
                
                // jumlah halaman yang dilompati
                $jump = ($range * 2) + 1;
            ?>

            <?php if ($pageCount > 1): ?>
            <nav aria-label="Pagination Pinjam">
                <ul class="pagination pagination-sm justify-content-end">

                    <!-- Sebelumnya -->
                    <li class="page-item <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="<?= ($currentPage > 1)
                                ? $pager->getPageURI($currentPage - 1, 'pinjam')
                                : '#' ?>">
                            Sebelumnya
                        </a>
                    </li>

                    <!-- TITIK-TITIK KIRI (klik = lompat ke belakang) -->
                    <?php if ($start > 1): ?>
                        <?php $prevJump = max(1, $currentPage - $jump); ?>
                        <li class="page-item">
                            <a class="page-link"
                            href="<?= $pager->getPageURI($prevJump, 'log') ?>"
                            title="Lompat ke halaman <?= $prevJump ?>">
                                ...
                            </a>
                        </li>
                    <?php endif ?>

                    <!-- Nomor Halaman -->
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link"
                            href="<?= $pager->getPageURI($i, 'pinjam') ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor ?>

                    <!-- TITIK-TITIK KANAN (klik = lompat ke depan) -->
                    <?php if ($end < $pageCount): ?>
                        <?php $nextJump = min($pageCount, $currentPage + $jump); ?>
                        <li class="page-item">
                            <a class="page-link"
                            href="<?= $pager->getPageURI($nextJump, 'log') ?>"
                            title="Lompat ke halaman <?= $nextJump ?>">
                                ...
                            </a>
                        </li>
                    <?php endif ?>

                    <!-- Selanjutnya -->
                    <li class="page-item <?= ($currentPage == $pageCount) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="<?= ($currentPage < $pageCount)
                                ? $pager->getPageURI($currentPage + 1, 'pinjam')
                                : '#' ?>">
                            Selanjutnya
                        </a>
                    </li>

                </ul>
            </nav>
            <?php endif ?>
        </div>
    </div>

</div>

</body>
</html>