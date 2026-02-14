<?= view('layouts/header', ['title' => 'Activity Log']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">
        <div class="content-wrapper">

            <!-- Header -->
            <div class="page-header">
                <h4>Activity Log</h4>
                <p class="text-muted">Riwayat aktivitas pengguna sistem</p>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Total Log</p>
                                    <h4 class="mb-0"><?= number_format($stats['total']) ?></h4>
                                </div>
                                <div class="text-primary">
                                    <i class="bi bi-journal-text" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Log Hari Ini</p>
                                    <h4 class="mb-0"><?= number_format($stats['today']) ?></h4>
                                </div>
                                <div class="text-success">
                                    <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Tabel Teraktif</p>
                                    <h6 class="mb-0"><?= $stats['byTable'][0]['tabel'] ?? '-' ?></h6>
                                    <small class="text-muted"><?= $stats['byTable'][0]['total'] ?? 0 ?> aktivitas</small>
                                </div>
                                <div class="text-warning">
                                    <i class="bi bi-table" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">User Aktif</p>
                                    <h6 class="mb-0"><?= $stats['recentUsers'][0]['nama'] ?? '-' ?></h6>
                                    <small class="text-muted"><?= $stats['recentUsers'][0]['total_activity'] ?? 0 ?> aktivitas</small>
                                </div>
                                <div class="text-info">
                                    <i class="bi bi-person-check" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <form method="get" class="row g-2 align-items-end" id="filterForm">

                        <div class="col-md-3">
                            <label class="form-label small text-muted">Cari Aktivitas</label>
                            <input type="text"
                                name="keyword"
                                class="form-control"
                                placeholder="Nama, email, aktivitas..."
                                value="<?= esc($filters['keyword'] ?? '') ?>"
                                id="keywordInput">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-muted">Role</label>
                            <select name="role" class="form-select" id="roleSelect">
                                <option value="">Semua Role</option>
                                <option value="admin" <?= ($filters['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="petugas" <?= ($filters['role'] ?? '') === 'petugas' ? 'selected' : '' ?>>Petugas</option>
                                <option value="peminjam" <?= ($filters['role'] ?? '') === 'peminjam' ? 'selected' : '' ?>>Peminjam</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-muted">Tabel</label>
                            <select name="tabel" class="form-select" id="tabelSelect">
                                <option value="">Semua Tabel</option>
                                <?php foreach ($tables as $t): ?>
                                    <option value="<?= esc($t['tabel']) ?>" <?= ($filters['tabel'] ?? '') === $t['tabel'] ? 'selected' : '' ?>>
                                        <?= esc($t['tabel']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-muted">Action</label>
                            <select name="action" class="form-select" id="actionSelect">
                                <option value="">Semua Action</option>
                                <?php foreach ($actions as $act): ?>
                                    <option value="<?= esc($act) ?>" <?= ($filters['action'] ?? '') === $act ? 'selected' : '' ?>>
                                        <?= esc($act) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small text-muted">Periode</label>
                            <div class="input-group">
                                <input type="date" name="start_date" class="form-control" 
                                    value="<?= esc($filters['startDate'] ?? '') ?>" id="startDate">
                                <span class="input-group-text">-</span>
                                <input type="date" name="end_date" class="form-control" 
                                    value="<?= esc($filters['endDate'] ?? '') ?>" id="endDate">
                            </div>
                        </div>

                        <div class="col-md-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Filter
                            </button>
                            <a href="<?= base_url('activity-log') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                            <a href="<?= base_url('activity-log/export-excel?' . http_build_query($filters)) ?>" 
                               class="btn btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                            </a>
                            <button type="button" class="btn btn-warning ms-auto" onclick="showCleanupModal()">
                                <i class="bi bi-clock-history me-1"></i>Cleanup Manual
                            </button>
                            <small class="text-muted ms-2">
                                <i class="bi bi-info-circle"></i> Log otomatis terhapus setelah 1 tahun
                            </small>
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
                                    <th width="150">Waktu</th>
                                    <th>User</th>
                                    <th width="100">Role</th>
                                    <th>Aktivitas</th>
                                    <th width="120">Tabel</th>
                                    <th width="80">ID Data</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Tidak ada data log</p>
                                    </td>
                                </tr>
                            <?php else: ?>
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

                                        // Pisah ringkasan dan detail aktivitas
                                        $split = explode('||', $l['aktivitas']);
                                        $ringkas = trim($split[0]);
                                        $detail  = trim($split[1] ?? '');
                                    ?>

                                    <tr>
                                        <td class="text-center text-muted small">
                                            <?= date('d-m-Y', strtotime($l['created_at'])) ?><br>
                                            <strong><?= date('H:i:s', strtotime($l['created_at'])) ?></strong>
                                        </td>

                                        <td>
                                            <strong><?= esc($l['nama'] ?? explode('@', $l['email'])[0]) ?></strong>
                                            <div class="text-muted small"><?= esc($l['email']) ?></div>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge bg-<?= $roleBadge ?>">
                                                <?= esc($l['role'] ?? '-') ?>
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge bg-secondary"><?= esc($ringkas) ?></span>
                                            <?php if ($detail): ?>
                                                <button class="btn btn-sm btn-outline-info mt-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailModal<?= $l['id_log'] ?>">
                                                    <i class="bi bi-eye"></i> Detail
                                                </button>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <code><?= esc($l['tabel'] ?? '-') ?></code>
                                        </td>

                                        <td class="text-center">
                                            <?= esc($l['id_data'] ?? '-') ?>
                                        </td>
                                    </tr>

                                    <!-- Modal Detail -->
                                    <?php if ($detail): ?>
                                        <div class="modal fade" id="detailModal<?= $l['id_log'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <i class="bi bi-info-circle me-2"></i><?= esc($ringkas) ?>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <small class="text-muted">User</small>
                                                                <p class="mb-0"><strong><?= esc($l['nama'] ?? $l['email']) ?></strong></p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <small class="text-muted">Waktu</small>
                                                                <p class="mb-0"><?= date('d-m-Y H:i:s', strtotime($l['created_at'])) ?></p>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <h6>Detail Aktivitas:</h6>
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
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            

            <!-- Pagination -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mt-3 gap-2">
                <div class="small">
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
                        <ul class="pagination pagination-sm justify-content-end mb-0">

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
                                <li class="page-item d-none d-sm-inline-block">
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
                                <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?> d-none d-sm-inline-block">
                                    <a class="page-link" href="<?= $pager->getPageURI($i, 'log') ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                                <?php endif ?>
                            <?php endfor ?>

                            <!-- TITIK KANAN -->
                            <?php if ($end < $pageCount): ?>
                                <?php $nextJump = min($pageCount, $currentPage + $jump); ?>
                                <li class="page-item d-none d-sm-inline-block">
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
    </div>
</div>

<!-- Modal Cleanup -->
<div class="modal fade" id="cleanupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="bi bi-clock-history me-2"></i>Cleanup Manual Log Lama
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Info:</strong> Sistem otomatis menghapus log yang lebih dari 1 tahun.
                </div>
                <p>Apakah Anda ingin menjalankan cleanup manual sekarang?</p>
                <p class="text-muted small">Ini akan menghapus semua log yang lebih lama dari 1 tahun (365 hari).</p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Aksi ini tidak dapat dibatalkan!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning" onclick="executeCleanup()">
                    <i class="bi bi-clock-history me-1"></i>Jalankan Cleanup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Auto filter
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    const keywordInput = document.getElementById('keywordInput');
    const roleSelect = document.getElementById('roleSelect');
    const tabelSelect = document.getElementById('tabelSelect');
    const actionSelect = document.getElementById('actionSelect');
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    
    let timeout = null;
    
    // Auto submit saat mengetik (dengan delay 500ms)
    keywordInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            form.submit();
        }, 500);
    });
    
    // Auto submit saat memilih filter
    [roleSelect, tabelSelect, actionSelect, startDate, endDate].forEach(element => {
        element.addEventListener('change', function() {
            form.submit();
        });
    });
});

// Show cleanup modal
function showCleanupModal() {
    const modal = new bootstrap.Modal(document.getElementById('cleanupModal'));
    modal.show();
}

// Execute cleanup
function executeCleanup() {
    if (!confirm('Yakin ingin menjalankan cleanup manual? Ini akan menghapus log > 1 tahun.')) {
        return;
    }
    
    fetch('<?= base_url('activity-log/cleanup') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan: ' + error);
    });
}
</script>

<?= view('layouts/footer') ?>
