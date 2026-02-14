<?= view('layouts/header', ['title' => 'Detail Kategori']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">
        
        <!-- Header -->
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1"><?= $title ?></h3>
                <p class="text-muted mb-0">Daftar barang dalam kategori ini</p>
            </div>
            <a href="/kategori" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <!-- Filter Kondisi -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="me-2 fw-bold">Filter Kondisi:</span>
                    <a href="<?= site_url('kategori/'.$kategori['id_kategori']) ?>" 
                       class="btn btn-sm <?= (!$kondisiFilter || $kondisiFilter === 'semua') ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <i class="bi bi-grid-3x3-gap me-1"></i>Semua
                    </a>
                    <a href="<?= site_url('kategori/'.$kategori['id_kategori'].'?kondisi=baik') ?>" 
                       class="btn btn-sm <?= ($kondisiFilter === 'baik') ? 'btn-success' : 'btn-outline-success' ?>">
                        <i class="bi bi-check-circle me-1"></i>Baik
                    </a>
                    <a href="<?= site_url('kategori/'.$kategori['id_kategori'].'?kondisi=rusak ringan') ?>" 
                       class="btn btn-sm <?= ($kondisiFilter === 'rusak ringan') ? 'btn-warning' : 'btn-outline-warning' ?>">
                        <i class="bi bi-exclamation-triangle me-1"></i>Rusak Ringan
                    </a>
                    <a href="<?= site_url('kategori/'.$kategori['id_kategori'].'?kondisi=rusak berat') ?>" 
                       class="btn btn-sm <?= ($kondisiFilter === 'rusak berat') ? 'btn-danger' : 'btn-outline-danger' ?>">
                        <i class="bi bi-x-circle me-1"></i>Rusak Berat
                    </a>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Jenis Barang</th>
                                <th>Merek Barang</th>
                                <th>Tipe Barang</th>
                                <th>Kode Barang</th>
                                <th>RAM</th>
                                <th>ROM</th>
                                <th>Kondisi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($barang_list): ?>
                                <?php $no = 1 + (($currentPage - 1) * $perPage); foreach($barang_list as $b): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= esc($b['nama_kategori']) ?></td>
                                        <td><?= esc($b['merek_barang']) ?></td>
                                        <td><?= esc($b['tipe_barang']) ?></td>
                                        <td><?= esc($b['kode_barang']) ?></td>
                                        <td class="text-center"><?= esc($b['ram']) ?></td>
                                        <td class="text-center"><?= esc($b['rom']) ?></td>
                                        <td class="text-center">
                                            <?php
                                                $kondisiBadge = match($b['kondisi']) {
                                                    'baik' => 'success',
                                                    'rusak ringan' => 'warning',
                                                    'rusak berat' => 'danger',
                                                    default => 'secondary'
                                                };
                                            ?>
                                            <span class="badge bg-<?= $kondisiBadge ?>">
                                                <?= ucfirst(esc($b['kondisi'])) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                $statusBadge = match($b['status']) {
                                                    'tersedia' => 'success',
                                                    'dipinjam' => 'warning',
                                                    'dibooking' => 'info',
                                                    'tidak tersedia' => 'danger',
                                                    default => 'secondary'
                                                };
                                            ?>
                                            <span class="badge bg-<?= $statusBadge ?>">
                                                <?= ucfirst(esc($b['status'])) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center py-3">
                                        <?= $kondisiFilter && $kondisiFilter !== 'semua' 
                                            ? 'Tidak ada barang dengan kondisi ' . esc($kondisiFilter) . ' di kategori ini.' 
                                            : 'Belum ada barang di kategori ini.' ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Total Data: <b><?= $pager->getTotal('barang') ?></b> |
                Per Page: <b><?= $pager->getPerPage('barang') ?></b> |
                Page: <b><?= $pager->getCurrentPage('barang') ?></b> /
                <?= $pager->getPageCount('barang') ?>
            </div>

            <div>
                <?php
                    $currentPage = $pager->getCurrentPage('barang');
                    $pageCount   = $pager->getPageCount('barang');
                    $range       = 1;

                    $start = max(1, $currentPage - $range);
                    $end   = min($pageCount, $currentPage + $range);

                    $jump = ($range * 4) + 1;
                ?>

                <?php if ($pageCount > 1): ?>
                <nav aria-label="Pagination Barang">
                    <ul class="pagination pagination-sm justify-content-end">

                        <!-- Sebelumnya -->
                        <li class="page-item <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                            <a class="page-link"
                            href="<?= ($currentPage > 1)
                                    ? $pager->getPageURI($currentPage - 1, 'barang')
                                    : '#' ?>">
                                ‹
                            </a>
                        </li>

                        <!-- Page Pertama -->
                        <li class="page-item <?= (1 == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $pager->getPageURI(1, 'barang') ?>">1</a>
                        </li>

                        <!-- Titik Kiri -->
                        <?php if ($start > 1): ?>
                            <?php $prevJump = max(1, $currentPage - $jump); ?>
                            <li class="page-item">
                                <a class="page-link"
                                href="<?= $pager->getPageURI($prevJump, 'barang') ?>"
                                title="Lompat ke halaman <?= $prevJump ?>">
                                ...
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Nomor Halaman -->
                        <?php for ($i = $start; $i <= $end; $i++): ?>
                            <?php if ($i > 1 && $i < $pageCount): ?>
                                <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= $pager->getPageURI($i, 'barang') ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endif ?>
                        <?php endfor ?>

                        <!-- Titik Kanan -->
                        <?php if ($end < $pageCount): ?>
                            <?php $nextJump = min($pageCount, $currentPage + $jump); ?>
                            <li class="page-item">
                                <a class="page-link"
                                href="<?= $pager->getPageURI($nextJump, 'barang') ?>"
                                title="Lompat ke halaman <?= $nextJump ?>">
                                ...
                                </a>
                            </li>
                        <?php endif ?>

                        <!-- Halaman Terakhir -->
                        <li class="page-item <?= ($pageCount == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $pager->getPageURI($pageCount, 'barang') ?>">
                                <?= $pageCount ?>
                            </a>
                        </li>

                        <!-- Selanjutnya -->
                        <li class="page-item <?= ($currentPage == $pageCount) ? 'disabled' : '' ?>">
                            <a class="page-link"
                            href="<?= ($currentPage < $pageCount)
                                    ? $pager->getPageURI($currentPage + 1, 'barang')
                                    : '#' ?>">
                                ›
                            </a>
                        </li>

                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?= view('layouts/footer') ?>