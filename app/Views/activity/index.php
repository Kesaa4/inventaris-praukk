<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Activity Log</h4>
        <a href="/dashboard" class="btn btn-secondary btn-sm">
            Kembali ke Dashboard
        </a>
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

</div>
