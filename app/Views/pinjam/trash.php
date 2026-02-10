<?= view('layouts/header', ['title' => 'Peminjaman Terhapus']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">

        <!-- Header -->
        <div class="mb-3"> 
            <h4 class="text-danger fw-bold">
                <i class="bi bi-trash"></i> Peminjaman Terhapus
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
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="get" action="<?= site_url('pinjam/trash') ?>" class="row g-2 align-items-end">

                <!-- Keyword -->
                <div class="col-md-4">
                    <label class="form-label">Cari Peminjaman</label>
                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Barang atau email.."
                        value="<?= esc($filters['keyword'] ?? '') ?>"
                    >
                </div>

                <!-- Status -->
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="dipinjam" <?= ($filters['status'] ?? '') === 'dipinjam' ? 'selected' : '' ?>>
                            Dipinjam
                        </option>
                        <option value="dikembalikan" <?= ($filters['status'] ?? '') === 'dikembalikan' ? 'selected' : '' ?>>
                            Dikembalikan
                        </option>
                        <option value="ditolak" <?= ($filters['status'] ?? '') === 'ditolak' ? 'selected' : '' ?>>
                            Ditolak
                        </option>
                        <option value="menunggu" <?= ($filters['status'] ?? '') === 'menunggu' ? 'selected' : '' ?>>
                            Menunggu
                        </option>
                    </select>
                </div>

                <!-- Tgl Pengajuan -->
                <div class="col-md-2">
                    <label class="form-label">Tanggal Pengajuan</label>
                    <input
                        type="date"
                        name="tgl_pengajuan"
                        class="form-control"
                        value="<?= esc($filters['tgl_pengajuan'] ?? '') ?>"
                    >
                </div>

                <!-- Tgl Kembali -->
                <div class="col-md-2">
                    <label class="form-label">Tanggal Kembali</label>
                    <input
                        type="date"
                        name="tgl_disetujui_kembali"
                        class="form-control"
                        value="<?= esc($filters['tgl_disetujui_kembali'] ?? '') ?>"
                    >
                </div>

                <!-- Tombol -->
                <div class="col-md-12 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Cari
                    </button>

                    <a href="<?= site_url('pinjam/trash') ?>" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm border-danger">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark text-center">
                        <tr class="align-middle">
                            <th style="width:50px;">No</th>
                            <th style="min-width:170px" class="text-nowrap text-center">Barang</th>
                            <th>Peminjam</th>
                            <th style="min-width:170px" class="text-nowrap text-center">Pengajuan Peminjaman</th>
                            <th style="min-width:170px" class="text-nowrap text-center">Peminjaman Disetujui</th>
                            <th style="min-width:190px" class="text-nowrap text-center">Ajukan Pengembalian</th>
                            <th style="min-width:200px" class="text-nowrap text-center">Pengembalian Disetujui</th>
                            <th>Status</th>
                            <th>Dihapus Pada</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (empty($pinjam)): ?>
                            <tr>
                                <td colspan="12" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox"></i> Tidak ada peminjaman terhapus
                                </td>
                            </tr>
                        <?php endif ?>

                        <?php foreach ($pinjam as $i => $p): ?>
                        <tr>
                            <td class="text-center"><?= $i + 1 ?></td>

                            <td class="text-nowrap text-center">
                                <?= esc($p['jenis_barang'].' '.$p['merek_barang'].' '.$p['tipe_barang'].' '.$p['kode_barang']) ?>
                            </td>

                            <td>
                                <?php 
                                    $displayName = !empty($p['nama']) ? $p['nama'] : explode('@', $p['email'])[0];
                                    echo esc($displayName);
                                ?>
                            </td>

                            <td class="text-nowrap text-center">
                                <?= $p['tgl_pengajuan']
                                    ? date('d-m-Y H:i', strtotime($p['tgl_pengajuan']))
                                    : '-' ?>
                            </td>

                            <td class="text-nowrap text-center">
                                <?= $p['tgl_disetujui']
                                    ? date('d-m-Y H:i', strtotime($p['tgl_disetujui']))
                                    : '-' ?>
                            </td>

                            <td class="text-nowrap text-center">
                                <?= $p['tgl_pengajuan_kembali']
                                    ? date('d-m-Y H:i', strtotime($p['tgl_pengajuan_kembali']))
                                    : '-' ?>
                            </td>

                            <td class="text-center">
                                <?= $p['tgl_disetujui_kembali']
                                    ? date('d-m-Y', strtotime($p['tgl_disetujui_kembali']))
                                    : '-' ?>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-secondary">
                                    <?= esc($p['status']) ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-danger">
                                    <?= date('d-m-Y H:i', strtotime($p['deleted_at'])) ?>
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
            Page: <b><?= $pager->getCurrentPage('trash') ?></b> /
            <?= $pager->getPageCount('trash') ?>
        </div>

        <div>
            <?php
                $currentPage = $pager->getCurrentPage('trash');
                $pageCount   = $pager->getPageCount('trash');
                $range       = 1;

                $start = max(1, $currentPage - $range);
                $end   = min($pageCount, $currentPage + $range);

                $queryString = http_build_query(array_filter($filters));
                $queryString = $queryString ? '&' . $queryString : '';
                $jump = ($range * 4) + 1;
            ?>

            <?php if ($pageCount > 1): ?>
            <nav aria-label="Pagination Peminjaman Terhapus">
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
