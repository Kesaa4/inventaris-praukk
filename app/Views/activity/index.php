<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        
    <!-- JS 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Activity Log</h4>
        <a href="/dashboard" class="btn btn-secondary btn-sm">
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Filter -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="get" class="row g-2 align-items-end">

                <div class="col-md-4">
                    <label class="form-label small text-muted">
                        Cari Aktivitas
                    </label>
                    <input type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Nama, email, aktivitas..."
                        value="<?= esc($keyword ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted">
                        Role
                    </label>
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="petugas" <?= ($role ?? '') === 'petugas' ? 'selected' : '' ?>>Petugas</option>
                        <option value="peminjam" <?= ($role ?? '') === 'peminjam' ? 'selected' : '' ?>>Peminjam</option>
                    </select>
                </div>

                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Cari
                    </button>

                    <a href="<?= base_url('activity-log') ?>" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Aktivitas</th>
                            <th>Tabel</th>
                            <th>ID Data</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($logs as $l): ?>

                        <?php
                            // Badge Role
                            $role = strtolower($l['role'] ?? '');
                            $roleBadge = match ($role) {
                                'admin' => 'primary',
                                'petugas' => 'warning',
                                'peminjam' => 'success',
                                default => 'secondary'
                            };

                            $split = explode('||', $l['aktivitas']);
                            $ringkas = trim($split[0]);
                            $detail  = trim($split[1] ?? '');
                        ?>

                        <tr>
                            <td class="text-center text-muted">
                                <?= date('d-m-Y H:i', strtotime($l['created_at'])) ?>
                            </td>

                            <td>
                                <strong>
                                    <?= esc($l['nama'] ?? explode('@', $l['email'])[0]) ?>
                                </strong>
                                <div class="text-muted small">
                                    ID: <?= $l['id_user'] ?>
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-<?= $roleBadge ?>">
                                    <?= esc($l['role'] ?? '-') ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-secondary">
                                    <?= esc($ringkas) ?>
                                </span>

                                <?php if ($detail): ?>
                                    <br>
                                    <button class="btn btn-sm btn-outline-secondary mt-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailModal<?= $l['id_log'] ?>">
                                        Detail
                                    </button>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <?= esc($l['tabel']) ?>
                            </td>

                            <td class="text-center">
                                <?= esc($l['id_data']) ?>
                            </td>
                        </tr>

                        <?php if ($detail): ?>
                            <div class="modal fade" id="detailModal<?= $l['id_log'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><?= esc($ringkas) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <ul class="list-group list-group-flush">
                                                <?php foreach (explode(';', $detail) as $row): ?>
                                                    <li class="list-group-item">
                                                        <?= esc(trim($row)) ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            Total Data: <b><?= $pager->getTotal('log') ?></b> |
            Per Page: <b><?= $pager->getPerPage('log') ?></b> |
            Page: <b><?= $pager->getCurrentPage('log') ?></b> /
            <?= $pager->getPageCount('log') ?>
        </div>

        <div>
            <?php
                $currentPage = $pager->getCurrentPage('log');
                $pageCount   = $pager->getPageCount('log');
                $range       = 1;

                $start = max(1, $currentPage - $range);
                $end   = min($pageCount, $currentPage + $range);

                $jump = ($range * 4) + 1;
            ?>

            <?php if ($pageCount > 1): ?>
            <nav aria-label="Pagination Activity Log">
                <ul class="pagination pagination-sm justify-content-end">

                    <!-- Sebelumnya -->
                    <li class="page-item <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="<?= ($currentPage > 1)
                                ? $pager->getPageURI($currentPage - 1, 'log')
                                : '#' ?>">
                            ‹
                        </a>
                    </li>

                    <!-- Page Pertama -->
                    <li class="page-item <?= (1 == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $pager->getPageURI(1, 'log') ?>">1</a>
                    </li>

                    <!-- TITIK KIRI -->
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
                        <?php if ($i > 1 && $i < $pageCount): ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $pager->getPageURI($i, 'log') ?>">
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
                            href="<?= $pager->getPageURI($nextJump, 'log') ?>"
                            title="Lompat ke halaman <?= $nextJump ?>">
                                ...
                            </a>
                        </li>
                    <?php endif ?>

                    <!-- Last page -->
                    <?php if ($pageCount > 1): ?>
                    <li class="page-item <?= ($pageCount == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $pager->getPageURI($pageCount, 'log') ?>">
                            <?= $pageCount ?>
                        </a>
                    </li>
                    <?php endif ?>

                    <!-- Selanjutnya -->
                    <li class="page-item <?= ($currentPage == $pageCount) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="<?= ($currentPage < $pageCount)
                                ? $pager->getPageURI($currentPage + 1, 'log')
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