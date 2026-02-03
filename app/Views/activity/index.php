<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Activity Log</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
                                    <?= esc($l['aktivitas']) ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <?= esc($l['tabel']) ?>
                            </td>

                            <td class="text-center">
                                <?= esc($l['id_data']) ?>
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
            Total Data: <b><?= $pager->getTotal('log') ?></b> |
            Per Page: <b><?= $pager->getPerPage('log') ?></b> |
            Page: <b><?= $pager->getCurrentPage('log') ?></b> /
            <?= $pager->getPageCount('log') ?>
        </div>

        <div>
            <?php
                $currentPage = $pager->getCurrentPage('log');
                $pageCount   = $pager->getPageCount('log');
                $range       = 1; // kiri + current + kanan = 3 halaman

                $start = max(1, $currentPage - $range);
                $end   = min($pageCount, $currentPage + $range);

                // jumlah halaman yang dilompati
                $jump = ($range * 2) + 1;
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
                            href="<?= $pager->getPageURI($i, 'log') ?>">
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
                                ? $pager->getPageURI($currentPage + 1, 'log')
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