<?= view('layouts/header', ['title' => 'Barang Terhapus']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">

        <!-- Header -->
        <div class="mb-3"> 
            <h4 class="text-danger fw-bold">
                <i class="bi bi-trash"></i> Barang Terhapus
            </h4>
        </div>

        <!-- Alert Success -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif ?>

        <!-- Filter -->
        <form method="get" class="card shadow-sm mb-3" id="filterForm">
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Cari Barang</label>
                        <input type="text" name="keyword" class="form-control" id="keywordInput"
                            value="<?= esc($keyword ?? '') ?>" placeholder="Kategori, merek, tipe, kode...">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Kondisi</label>
                        <select name="kondisi" class="form-select" id="kondisiSelect">
                            <option value="">Semua Kondisi</option>
                            <option value="Baik" <?= ($kondisi ?? '') === 'Baik' ? 'selected' : '' ?>>Baik</option>
                            <option value="Rusak Ringan" <?= ($kondisi ?? '') === 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                            <option value="Rusak Berat" <?= ($kondisi ?? '') === 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" id="statusSelect">
                            <option value="">Semua Status</option>
                            <option value="tersedia" <?= ($status ?? '') === 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                            <option value="tidak tersedia" <?= ($status ?? '') === 'tidak tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tanggal Dihapus</label>
                        <input type="date" name="deleted_date" class="form-control" id="deletedDateInput"
                            value="<?= esc($deletedDate ?? '') ?>">
                    </div>

                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="<?= site_url('barang/trash') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                </a>
                <a href="<?= base_url('barang') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </form>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filterForm');
            const keywordInput = document.getElementById('keywordInput');
            const kondisiSelect = document.getElementById('kondisiSelect');
            const statusSelect = document.getElementById('statusSelect');
            const deletedDateInput = document.getElementById('deletedDateInput');
            
            let timeout = null;
            
            keywordInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    form.submit();
                }, 500);
            });
            
            kondisiSelect.addEventListener('change', function() {
                form.submit();
            });
            
            statusSelect.addEventListener('change', function() {
                form.submit();
            });
            
            deletedDateInput.addEventListener('change', function() {
                form.submit();
            });
        });
        </script>

        <!-- Table -->
        <div class="card shadow-sm border-danger">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                        <thead class="table-dark text-center">
                            <tr class="align-middle">
                                <th style="width:50px;">No</th>
                                <th style="min-width:120px" class="text-nowrap">Kategori</th>
                                <th style="min-width:120px" class="text-nowrap">Merek</th>
                                <th style="min-width:120px" class="text-nowrap">Tipe</th>
                                <th style="min-width:120px" class="text-nowrap">Kode Barang</th>
                                <th style="min-width:80px" class="text-nowrap">RAM</th>
                                <th style="min-width:80px" class="text-nowrap">ROM</th>
                                <th style="min-width:120px" class="text-nowrap">Kondisi</th>
                                <th style="min-width:120px" class="text-nowrap">Status</th>
                                <th style="min-width:150px" class="text-nowrap">Dihapus Pada</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if (empty($barang)): ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> Tidak ada barang terhapus
                                    </td>
                                </tr>
                            <?php endif ?>

                            <?php foreach ($barang as $i => $b): ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td class="text-nowrap text-center"><?= esc($b['nama_kategori']) ?></td>
                                <td class="text-nowrap text-center"><?= esc($b['merek_barang']) ?></td>
                                <td class="text-nowrap text-center"><?= esc($b['tipe_barang']) ?></td>
                                <td class="text-nowrap text-center"><?= esc($b['kode_barang']) ?></td>
                                <td class="text-nowrap text-center"><?= esc($b['ram']) ?></td>
                                <td class="text-nowrap text-center"><?= esc($b['rom']) ?></td>
                                <td class="text-nowrap text-center">
                                    <?php
                                        $kondisiBadge = match($b['kondisi']) {
                                            'Baik' => 'success',
                                            'Rusak Ringan' => 'warning',
                                            'Rusak Berat' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?>
                                    <span class="badge bg-<?= $kondisiBadge ?>">
                                        <?= esc($b['kondisi']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php
                                        $statusLower = strtolower($b['status']);
                                        $statusBadge = $statusLower === 'tersedia' ? 'success' : 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $statusBadge ?>">
                                        <?= esc(ucfirst($b['status'])) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger">
                                        <?= date('d-m-Y H:i', strtotime($b['deleted_at'])) ?>
                                    </span>
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
                Page: <b><?= $pager->getCurrentPage('trash') ?></b> / <?= $pager->getPageCount('trash') ?>
            </div>

            <div>
                <?php
                    $currentPage = $pager->getCurrentPage('trash');
                    $pageCount   = $pager->getPageCount('trash');
                    $range       = 1;

                    $start = max(1, $currentPage - $range);
                    $end   = min($pageCount, $currentPage + $range);

                    $jump = ($range * 4) + 1;
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
</div>

<?= view('layouts/footer') ?>