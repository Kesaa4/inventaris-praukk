<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Terhapus</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Js 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3"> 
        <h4 class="text-danger mb-0">
            <i class="bi bi-trash"></i> <strong>Peminjaman Terhapus</strong>
        </h4>

        <a href="<?= site_url('pinjam') ?>" class="btn btn-secondary btn-sm">
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
            <form method="get" action="<?= site_url('pinjam/trash') ?>" class="row g-2 align-items-end">

                <!-- Keyword -->
                <div class="col-md-4">
                    <label class="form-label">Cari Peminjaman</label>
                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Barang / Email"
                        value="<?= esc($filters['keyword'] ?? '') ?>"
                    >
                </div>

                <!-- Status -->
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="dipinjam" <?= ($filters['status'] ?? '') === 'dipinjam' ? 'selected' : '' ?>>
                            Dipinjam
                        </option>
                        <option value="dikembalikan" <?= ($filters['status'] ?? '') === 'dikembalikan' ? 'selected' : '' ?>>
                            Dikembalikan
                        </option>
                        <option value="ditolak" <?= ($filters['status'] ?? '') === 'ditolak' ? 'selected' : '' ?>>
                            Ditolak
                        </option>
                        <option value="menunggu" <?= ($filters['status'] ?? '') === 'menunggu' ? 'selected' : '' ?>>
                            Menunggu
                        </option>
                    </select>
                </div>

                <!-- Tgl Pinjam -->
                <div class="col-md-2">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input
                        type="date"
                        name="tgl_pinjam"
                        class="form-control"
                        value="<?= esc($filters['tgl_pinjam'] ?? '') ?>"
                    >
                </div>

                <!-- Tgl Kembali -->
                <div class="col-md-2">
                    <label class="form-label">Tanggal Kembali</label>
                    <input
                        type="date"
                        name="tgl_kembali"
                        class="form-control"
                        value="<?= esc($filters['tgl_kembali'] ?? '') ?>"
                    >
                </div>

                <!-- Tombol -->
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Cari
                    </button>

                    <a href="<?= site_url('pinjam/trash') ?>" class="btn btn-outline-secondary">
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
                            <th>Barang</th>
                            <th>Peminjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Dihapus Pada</th>
                            <th style="width:180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (empty($pinjam)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox"></i> Tidak ada peminjaman terhapus
                                </td>
                            </tr>
                        <?php endif ?>

                        <?php foreach ($pinjam as $i => $p): ?>
                        <tr>
                            <td class="text-center"><?= $i + 1 ?></td>
                            <td>
                                <?= esc($p['jenis_barang'].' '.$p['merek_barang'].' '.$p['tipe_barang']) ?>
                            </td>
                            <td><?= esc($p['email']) ?></td>
                            <td class="text-center">
                                <?= date('d-m-Y', strtotime($p['tgl_pinjam'])) ?>
                            </td>
                            <td class="text-center">
                                <?= $p['tgl_kembali']
                                    ? date('d-m-Y', strtotime($p['tgl_kembali']))
                                    : '-' ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">
                                    <?= esc($p['status']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">
                                    <?= date('d-m-Y H:i', strtotime($p['deleted_at'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="<?= site_url('pinjam/restore/'.$p['id_pinjam']) ?>"
                                       class="btn btn-success btn-sm d-flex align-items-center justify-content-center"
                                       onclick="return confirm('Restore data ini?')">
                                        Restore
                                    </a>

                                    <a href="<?= site_url('pinjam/force-delete/'.$p['id_pinjam']) ?>"
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

        <!-- Info -->
        <div>
            Total Data: <b><?= $pager->getTotal('trash') ?></b> |
            Per Page: <b><?= $pager->getPerPage('trash') ?></b> |
            Page: <b><?= $pager->getCurrentPage('trash') ?></b> /
            <?= $pager->getPageCount('trash') ?>
        </div>

        <!-- Navigasi -->
        <div>
            <?php
                $currentPage = $pager->getCurrentPage('trash');
                $pageCount   = $pager->getPageCount('trash');
                $range       = 1;

                $start = max(1, $currentPage - $range);
                $end   = min($pageCount, $currentPage + $range);
                $jump  = ($range * 2) + 1;

                $queryString = http_build_query(array_filter($filters));
                $queryString = $queryString ? '&' . $queryString : '';
            ?>

            <?php if ($pageCount > 1): ?>
            <nav>
                <ul class="pagination pagination-sm mb-0">

                    <li class="page-item <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="<?= ($currentPage > 1)
                            ? $pager->getPageURI($currentPage - 1, 'trash') . $queryString
                            : '#' ?>">
                            Sebelumnya
                        </a>
                    </li>

                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link"
                               href="<?= $pager->getPageURI($i, 'trash') . $queryString ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor ?>

                    <li class="page-item <?= ($currentPage == $pageCount) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="<?= ($currentPage < $pageCount)
                            ? $pager->getPageURI($currentPage + 1, 'trash') . $queryString
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
