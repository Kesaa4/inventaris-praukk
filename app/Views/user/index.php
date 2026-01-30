<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Manajemen User</h4>
            <small class="text-muted">
                Login sebagai:
                <strong><?= explode('@', session()->get('email'))[0] ?></strong>
                (<?= session()->get('role') ?>)
            </small>
        </div>

        <a href="/user/create" class="btn btn-success">
            Tambah User
        </a>
    </div>

    <!-- Alert Success -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <!-- Card -->
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="60">No</th>
                            <th>Email</th>
                            <th>Nama Lengkap</th>
                            <th width="120">Role</th>
                            <th width="160">Aksi</th>
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
                                    <a href="/user/edit/<?= $u['id_user'] ?>"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <a href="/user/delete/<?= $u['id_user'] ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin hapus user ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Data user belum tersedia
                            </td>
                        </tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <div class="mt-3">
        <a href="/dashboard" class="btn btn-secondary">
            Kembali ke Dashboard
        </a>
    </div>

</div>
