<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- JS 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Manajemen User</h4>
        </div>

        <a href="/dashboard" class="btn btn-secondary btn-sm">
            Kembali ke Dashboard
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
            <form method="get" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Cari User</label>
                    <input type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Cari nama atau email..."
                        value="<?= esc($keyword ?? '') ?>"
                    >
                </div>

                <div class="col-md-3">
                    <label class="form-label">Cari Role</label>
                    <select name="role" class="form-select">
                        <option value="">-- Semua Role --</option>
                        <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="peminjam" <?= ($role ?? '') === 'peminjam' ? 'selected' : '' ?>>Peminjam</option>
                        <option value="petugas" <?= ($role ?? '') === 'petugas' ? 'selected' : '' ?>>Petugas</option>
                    </select>
                </div>

                <div class="col-md-5 d-flex justify-content-between">
                    <div class="d-flex gap-2 justify-content-start">
                        <button type="submit" class="btn btn-primary">
                            Cari
                        </button>
                        <a href="<?= base_url('user') ?>" class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>
                    <div class="justify-content-end align-middle">
                        <a href="/user/create" class="btn btn-success ms-auto btn-sm">
                            Tambah User
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-primary text-center">
                        <tr>
                            <th width="60">No</th>
                            <th>Email</th>
                            <th>Nama Lengkap</th>
                            <th width="120">Role</th>
                            <th width="120">Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (count($users) > 0): ?>
                        <?php $no = 1; foreach ($users as $u): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= esc($u['email']) ?></td>
                                <td><?= esc($u['nama'] ?? '-') ?></td>
                                <td class="text-center">
                                    <?php
                                        $role = $u['role'];
                                        $badgeClass = 'bg-secondary';

                                        if ($role === 'admin') {
                                            $badgeClass = 'bg-primary';
                                        } elseif ($role === 'peminjam') {
                                            $badgeClass = 'bg-success';
                                        } elseif ($role === 'petugas') {
                                            $badgeClass = 'bg-warning text-dark';
                                        }
                                    ?>
                                    <span class="badge <?= $badgeClass ?>">
                                        <?= esc($role) ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <?php
                                        $status = $u['status'];
                                        $badge = $status === 'aktif' ? 'bg-success' : 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badge ?>">
                                        <?= esc($status) ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="/user/edit/<?= $u['id_user'] ?>"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12" class="text-center text-muted">
                                Data user belum tersedia
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
            Total Data: <b><?= $pager->getTotal('user') ?></b> |
            Per Page: <b><?= $pager->getPerPage('user') ?></b> |
            Page: <b><?= $pager->getCurrentPage('user') ?></b> /
            <?= $pager->getPageCount('user') ?>
        </div>

        <div>
            <?php
                $currentPage = $pager->getCurrentPage('user');
                $pageCount   = $pager->getPageCount('user');
                $range       = 1;

                $start = max(1, $currentPage - $range);
                $end   = min($pageCount, $currentPage + $range);

                $jump = ($range * 4) + 1;
            ?>

            <?php if ($pageCount > 1): ?>
            <nav aria-label="Pagination User">
                <ul class="pagination pagination-sm justify-content-end">

                    <!-- Sebelumnya -->
                    <li class="page-item <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="<?= ($currentPage > 1)
                                ? $pager->getPageURI($currentPage - 1, 'user')
                                : '#' ?>">
                            ‹
                        </a>
                    </li>

                    <!-- Page Pertama -->
                    <li class="page-item <?= (1 == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $pager->getPageURI(1, 'user') ?>">1</a>
                    </li>

                    <!-- TITIK KIRI -->
                    <?php if ($start > 1): ?>
                        <?php $prevJump = max(1, $currentPage - $jump); ?>
                        <li class="page-item">
                            <a class="page-link"
                            href="<?= $pager->getPageURI($prevJump, 'user') ?>"
                            title="Lompat ke halaman <?= $prevJump ?>">
                                ...
                            </a>
                        </li>
                    <?php endif ?>

                    <!-- Nomor Halaman -->
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <?php if ($i > 1 && $i < $pageCount): ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $pager->getPageURI($i, 'user') ?>">
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
                            href="<?= $pager->getPageURI($nextJump, 'user') ?>"
                            title="Lompat ke halaman <?= $nextJump ?>">
                                ...
                            </a>
                        </li>
                    <?php endif ?>

                    <!-- Last page -->
                    <?php if ($pageCount > 1): ?>
                    <li class="page-item <?= ($pageCount == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $pager->getPageURI($pageCount, 'user') ?>">
                            <?= $pageCount ?>
                        </a>
                    </li>
                    <?php endif ?>

                    <!-- Selanjutnya -->
                    <li class="page-item <?= ($currentPage == $pageCount) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="<?= ($currentPage < $pageCount)
                                ? $pager->getPageURI($currentPage + 1, 'user')
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