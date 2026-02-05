<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- JS 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Data Barang</h3>
        <a href="/dashboard" class="btn btn-secondary btn-sm">Kembali Ke Dashboard</a>
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
            <form method="get" action="<?= base_url('barang') ?>" class="row g-2 align-items-end">

                <div class="col-md-4">
                    <label class="form-label">Cari Barang</label>
                    <input 
                        type="text" 
                        name="keyword" 
                        class="form-control"
                        placeholder="Jenis atau merek..."
                        value="<?= esc($keyword) ?>"
                    >
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kategori Kondisi</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id_kategori'] ?>"
                                <?= $catFilter == $k['id_kategori'] ? 'selected' : '' ?>>
                                <?= esc($k['kategori_kondisi']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Cari
                    </button>
                    <a href="<?= base_url('barang') ?>" class="btn btn-outline-secondary">
                        Reset
                    </a>

                    <?php if (session()->get('role') === 'admin'): ?>
                        <a href="<?= base_url('barang/create') ?>" class="btn btn-success ms-auto">
                            Tambah Barang
                        </a>
                        <a href="<?= site_url('barang/trash') ?>" class="btn btn-danger ms-auto">
                            Barang Terhapus
                        </a>
                    <?php endif ?>
                </div>

            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Jenis</th>
                        <th>Merek</th>
                        <th>Tipe</th>
                        <th>Kode Barang</th>
                        <th>RAM</th>
                        <th>ROM</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <?php if (session()->get('role') === 'admin'): ?>
                            <th width="120">Aksi</th>
                        <?php endif ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($barang) > 0): ?>
                    <?php $no = 1; foreach ($barang as $b): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= esc($b['jenis_barang']) ?></td>
                        <td><?= esc($b['merek_barang']) ?></td>
                        <td><?= esc($b['tipe_barang']) ?></td>
                        <td><?= esc($b['kode_barang']) ?></td>
                        <td><?= esc($b['ram']) ?></td>
                        <td><?= esc($b['rom']) ?></td>
                        <td><?= esc($b['kategori_kondisi']) ?></td>
                        <td class="text-center">
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
                        <td><?= esc($b['keterangan']) ?></td>

                        <?php if (session()->get('role') === 'admin'): ?>
                        <td class="text-center">
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
                        <td colspan="15" class="text-center text-muted">
                            Data barang kosong
                        </td>
                    </tr>
                    <?php endif ?>
                </tbody>
            </table>

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
                $range       = 1; // kiri + current + kanan = 3 halaman

                $start = max(1, $currentPage - $range);
                $end   = min($pageCount, $currentPage + $range);
                
                // jumlah halaman yang dilompati
                $jump = ($range * 2) + 1;
            ?>

            <?php if ($pageCount > 1): ?>
            <nav aria-label="Pagination Barang">
                <ul class="pagination pagination-sm justify-content-end">

                    <!-- Sebelumnya -->
                    <li class="page-item <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="<?= ($currentPage > 1) ? $pager->getPageURI($currentPage - 1, 'barang') : '#' ?>">
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
                            href="<?= $pager->getPageURI($i, 'barang') ?>">
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
                        href="<?= ($currentPage < $pageCount) ? $pager->getPageURI($currentPage + 1, 'barang') : '#' ?>">
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
