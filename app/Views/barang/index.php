<?= view('layouts/header', ['title' => 'Data Barang']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">
        <div class="content-wrapper">

            <!-- Header -->
            <div class="page-header">
                <h3>Data Barang</h3>
                <p class="text-muted">Kelola data barang inventaris</p>
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
            <form method="get" action="<?= base_url('barang') ?>" class="row g-2 align-items-end" id="filterForm">

                <div class="col-md-4">
                    <label class="form-label">Cari Barang</label>
                    <input 
                        type="text" 
                        name="keyword" 
                        class="form-control"
                        placeholder="Cari..."
                        value="<?= esc($keyword) ?>"
                        id="keywordInput"
                    >
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kategori Kondisi</label>
                    <select name="kategori" class="form-select" id="kategoriSelect">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id_kategori'] ?>"
                                <?= $catFilter == $k['id_kategori'] ? 'selected' : '' ?>>
                                <?= esc($k['kategori_kondisi']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-12 col-lg d-flex flex-column flex-lg-row justify-content-between gap-2">
                    <div class="d-flex gap-2">
                        <a href="<?= base_url('barang') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </a>
                        <?php if (session()->get('role') === 'admin'): ?>
                            <a href="<?= base_url('barang/export-excel?' . http_build_query(['keyword' => $keyword, 'kategori' => $catFilter])) ?>" 
                               class="btn btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                            </a>
                        <?php endif ?>
                    </div>

                    <?php if (session()->get('role') === 'admin'): ?>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('barang/create') ?>" class="btn btn-success btn-sm">
                                Tambah Barang
                            </a>
                            <a href="<?= site_url('barang/trash') ?>" class="btn btn-danger btn-sm">
                                Barang Terhapus
                            </a>
                        </div>
                    <?php endif ?>
                </div>

            </form>
        </div>
    </div>

<script>
// Auto filter saat mengetik atau memilih
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    const keywordInput = document.getElementById('keywordInput');
    const kategoriSelect = document.getElementById('kategoriSelect');
    
    let timeout = null;
    
    // Auto submit saat mengetik (dengan delay 500ms)
    keywordInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            form.submit();
        }, 500);
    });
    
    // Auto submit saat memilih kategori
    kategoriSelect.addEventListener('change', function() {
        form.submit();
    });
});
</script>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-primary text-center">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Jenis</th>
                        <th class="text-nowrap">Merek</th>
                        <th class="text-nowrap">Tipe</th>
                        <th class="text-nowrap">Kode Barang</th>
                        <th class="text-nowrap">RAM</th>
                        <th class="text-nowrap">ROM</th>
                        <th class="text-nowrap">Kondisi</th>
                        <th class="text-nowrap">Foto</th>
                        <th class="text-nowrap">Status</th>
                        <th class="text-nowrap">Keterangan</th>
                        <?php if (session()->get('role') === 'admin'): ?>
                            <th class="text-nowrap" width="120">Aksi</th>
                        <?php endif ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($barang) > 0): ?>
                    <?php $no = 1; foreach ($barang as $b): ?>
                    <tr>
                        <td class="text-center text-nowrap"><?= $no++ ?></td>
                        <td class="text-nowrap"><?= esc($b['jenis_barang']) ?></td>
                        <td class="text-nowrap"><?= esc($b['merek_barang']) ?></td>
                        <td class="text-nowrap"><?= esc($b['tipe_barang']) ?></td>
                        <td class="text-nowrap"><?= esc($b['kode_barang']) ?></td>
                        <td class="text-nowrap"><?= esc($b['ram']) ?></td>
                        <td class="text-nowrap"><?= esc($b['rom']) ?></td>
                        <td class="text-nowrap"><?= esc($b['kategori_kondisi']) ?></td>
                        <td class="text-center">
                            <?php helper('upload'); ?>
                            <img src="<?= getFotoBarang($b['foto'] ?? null) ?>" 
                                 alt="<?= esc($b['kode_barang']) ?>"
                                 class="img-thumbnail"
                                 style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                 data-bs-toggle="modal"
                                 data-bs-target="#fotoModal<?= $b['id_barang'] ?>">
                        </td>
                        <td class="text-center text-nowrap">
                            <?php
                                $status = strtolower($b['status']);
                                $badge = 'secondary';

                                if ($status === 'tersedia') {
                                    $badge = 'success';
                                } elseif ($status === 'dibooking') {
                                    $badge = 'warning';
                                } elseif ($status === 'ditolak') {
                                    $badge = 'danger'; 
                                } elseif ($status === 'dipinjam') {
                                    $badge = 'primary';
                                } elseif ($status === 'tidak tersedia') {
                                    $badge = 'secondary';
                                }
                            ?>

                            <span class="badge bg-<?= $badge ?>">
                                <?= esc($b['status']) ?>
                            </span>
                    </td>
                        <td class="text-nowrap"><?= esc($b['keterangan']) ?></td>

                        <?php if (session()->get('role') === 'admin'): ?>
                        <td class="text-center text-nowrap">
                            <a href="<?= base_url('barang/history/' . $b['id_barang']) ?>" 
                               class="btn btn-sm btn-info"
                               title="Lihat Riwayat Peminjaman">
                                <i class="bi bi-clock-history"></i>
                            </a>
                            <a href="<?= base_url('barang/edit/' . $b['id_barang']) ?>" class="btn btn-sm btn-warning">
                                Edit
                            </a>
                            <a 
                                href="<?= base_url('barang/delete/' . $b['id_barang']) ?>" 
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin hapus barang ini?')"
                            >
                                Hapus
                            </a>
                        </td>
                        <?php endif ?>
                    </tr>
                    <?php endforeach ?>
                    
                    <?php else: ?>
                    <tr>
                        <td colspan="12" class="text-center text-muted">
                            Data barang kosong
                        </td>
                    </tr>
                    <?php endif ?>
                </tbody>
            </table>

        </div>
    </div>

    <!-- Modal Foto Barang -->
    <?php if (count($barang) > 0): ?>
        <?php foreach ($barang as $b): ?>
        <div class="modal fade" id="fotoModal<?= $b['id_barang'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Foto: <?= esc($b['kode_barang']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="<?= getFotoBarang($b['foto'] ?? null) ?>" 
                             alt="<?= esc($b['kode_barang']) ?>"
                             class="img-fluid"
                             style="max-height: 500px;">
                        <div class="mt-2">
                            <small class="text-muted">
                                <?= esc($b['jenis_barang']) ?> - <?= esc($b['merek_barang']) ?> - <?= esc($b['tipe_barang']) ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    <?php endif ?>

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

                    <!-- TITIK KIRI -->
                    <?php if ($start > 1): ?>
                        <?php $prevJump = max(1, $currentPage - $jump); ?>
                        <li class="page-item">
                            <a class="page-link"
                            href="<?= $pager->getPageURI($prevJump, 'barang') ?>"
                            title="Lompat ke halaman <?= $prevJump ?>">
                                ...
                            </a>
                        </li>
                    <?php endif ?>

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

                    <!-- TITIK KANAN -->
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

                    <!-- Last page -->
                    <?php if ($pageCount > 1): ?>
                    <li class="page-item <?= ($pageCount == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $pager->getPageURI($pageCount, 'barang') ?>">
                            <?= $pageCount ?>
                        </a>
                    </li>
                    <?php endif ?>

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
            <?php endif ?>
        </div>
    </div>

        </div>
    </div>
</div>

<?= view('layouts/footer') ?>
