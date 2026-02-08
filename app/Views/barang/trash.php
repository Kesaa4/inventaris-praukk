<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Terhapus</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- JS 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3"> 
        <h4 class="text-danger mb-0">
            <i class="bi bi-trash"></i> <strong>Barang Terhapus</strong>
        </h4>

        <a href="<?= base_url('barang') ?>" class="btn btn-secondary ms-auto btn-sm">
            Kembali
        </a>
    </div>

    <!-- Alert Success -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <!-- Filter -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="get" action="<?= base_url('barang/trash') ?>" class="row g-2 align-items-end">

                <!-- Keyword -->
                <div class="col-md-5">
                    <label class="form-label">Cari Barang Terhapus</label>
                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Jenis, merek, tipe, kode..."
                        value="<?= esc($keyword ?? '') ?>"
                    >
                </div>

                <!-- Tanggal Hapus -->
                <div class="col-md-3">
                    <label class="form-label">Tanggal Dihapus</label>
                    <input
                        type="date"
                        name="deleted_date"
                        class="form-control"
                        value="<?= esc($deletedDate ?? '') ?>"
                    >
                </div>

                <!-- Tombol -->
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Cari
                    </button>

                    <a href="<?= base_url('barang/trash') ?>" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm border-danger">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark text-center">
                        <tr class="align-middle">
                            <th style="width:50px;">No</th>
                            <th>Jenis</th>
                            <th>Merek</th>
                            <th>Tipe</th>
                            <th>Kode Barang</th>
                            <th>RAM</th>
                            <th>ROM</th>
                            <th>Dihapus Pada</th>
                            <th style="width:180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($barang)): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox"></i> Tidak ada barang terhapus
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($barang as $i => $b): ?>
                        <tr>
                            <td class="text-center"><?= $i + 1 ?></td>
                            <td><?= esc($b['jenis_barang']) ?></td>
                            <td><?= esc($b['merek_barang']) ?></td>
                            <td><?= esc($b['tipe_barang']) ?></td>
                            <td><?= esc($b['kode_barang']) ?></td>
                            <td><?= esc($b['ram']) ?></td>
                            <td><?= esc($b['rom']) ?></td>
                            <td class="text-center">
                                <span class="badge bg-danger">
                                    <?= date('d-m-Y H:i', strtotime($b['deleted_at'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    <!-- Restore -->
                                    <a href="<?= site_url('barang/restore/' . $b['id_barang']) ?>"
                                    class="btn btn-success btn-sm d-flex align-items-center justify-content-center"
                                    onclick="return confirm('Restore barang ini?')">
                                        <span>Restore</span>
                                    </a>
                                    <!-- Hapus Permanen -->
                                    <a href="<?= site_url('barang/force-delete/' . $b['id_barang']) ?>"
                                       class="btn btn-dark btn-sm d-flex align-items-center justify-content-center"
                                       onclick="return confirm('Hapus permanen? Data tidak bisa dikembalikan!')">
                                        Hapus Permanen
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            Total Data: <b><?= $pager->getTotal('trash') ?></b> |
            Per Page: <b><?= $pager->getPerPage('trash') ?></b> |
            Page: <b><?= $pager->getCurrentPage('trash') ?></b> /
            <?= $pager->getPageCount('trash') ?>
        </div>

        <div>
            <?php
                $currentPage = $pager->getCurrentPage('trash');
                $pageCount   = $pager->getPageCount('trash');
                $range       = 1;

                $start = max(1, $currentPage - $range);
                $end   = min($pageCount, $currentPage + $range);

                $jump = ($range * 4) + 1;

                //Tambahan Filter Query String
                $queryString = http_build_query([
                    'keyword'      => $keyword ?? null,
                    'deleted_date' => $deletedDate ?? null
                ]);
                $queryString = $queryString ? '&' . $queryString : '';
            ?>

            <?php if ($pageCount > 1): ?>
            <nav aria-label="Pagination Barang Terhapus">
                <ul class="pagination pagination-sm justify-content-end">

                    <!-- Sebelumnya -->
                    <li class="page-item <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="<?= ($currentPage > 1)
                                ? $pager->getPageURI($currentPage - 1, 'trash')
                                : '#' ?>">
                            ‹
                        </a>
                    </li>

                    <!-- Page Pertama -->
                    <li class="page-item <?= (1 == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $pager->getPageURI(1, 'trash') ?>">1</a>
                    </li>

                    <!-- TITIK KIRI -->
                    <?php if ($start > 1): ?>
                        <?php $prevJump = max(1, $currentPage - $jump); ?>
                        <li class="page-item">
                            <a class="page-link"
                            href="<?= $pager->getPageURI($prevJump, 'trash') ?>"
                            title="Lompat ke halaman <?= $prevJump ?>">
                                ...
                            </a>
                        </li>
                    <?php endif ?>

                    <!-- Nomor Halaman -->
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <?php if ($i > 1 && $i < $pageCount): ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $pager->getPageURI($i, 'trash') ?>">
                                <?= $i ?>
                            </a>
                        </li>
                        <?php endif ?>
                    <?php endfor ?>

                    <!-- TITIK KANAN -->
                    <?php if ($end < $pageCount): ?>
                        <?php $nextJump = min($pageCount, $currentPage + $jump); ?>
                        <li class="page-item">
                            <a class="page-link"
                            href="<?= $pager->getPageURI($nextJump, 'trash') ?>"
                            title="Lompat ke halaman <?= $nextJump ?>">
                                ...
                            </a>
                        </li>
                    <?php endif ?>

                    <!-- Last page -->
                    <?php if ($pageCount > 1): ?>
                    <li class="page-item <?= ($pageCount == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $pager->getPageURI($pageCount, 'trash') ?>">
                            <?= $pageCount ?>
                        </a>
                    </li>
                    <?php endif ?>

                    <!-- Selanjutnya -->
                    <li class="page-item <?= ($currentPage == $pageCount) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="<?= ($currentPage < $pageCount)
                                ? $pager->getPageURI($currentPage + 1, 'trash')
                                : '#' ?>">
                            ›
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