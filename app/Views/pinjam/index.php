<?= view('layouts/header', ['title' => 'Data Peminjaman']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">
        <div class="content-wrapper fade-in">

            <!-- Header -->
            <div class="page-header">
                <h4><i class="bi bi-arrow-left-right me-2"></i>Data Peminjaman</h4>
                <p class="text-muted">Kelola data peminjaman barang</p>
            </div>

    <!-- Alert Success -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <!-- Filter -->
    <form method="get" class="card shadow-sm mb-4">

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Cari Data</label>
                    <input type="text" name="keyword" class="form-control"
                        value="<?= esc(request()->getGet('keyword')) ?>" placeholder="Barang atau peminjam...">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="menunggu" <?= request()->getGet('status')=='menunggu'?'selected':'' ?>>Menunggu</option>
                        <option value="dipinjam" <?= request()->getGet('status')=='dipinjam'?'selected':'' ?>>Dipinjam</option>
                        <option value="dikembalikan" <?= request()->getGet('status')=='dikembalikan'?'selected':'' ?>>Dikembalikan</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Pengajuan</label>
                    <input type="date" name="tgl_pengajuan" class="form-control"
                        value="<?= esc(request()->getGet('tgl_pengajuan')) ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Kembali</label>
                    <input type="date" name="tgl_disetujui_kembali" class="form-control"
                        value="<?= esc(request()->getGet('tgl_kembali')) ?>">
                </div>

            </div>
        </div>

        <div class="card-footer d-flex flex-column flex-lg-row justify-content-between gap-2">
            <div class="d-flex gap-2">
                <button class="btn btn-primary">Cari</button>
                <a href="<?= site_url('pinjam') ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
            <div class="d-flex flex-column flex-sm-row gap-2">
                <?php if (in_array(session('role'), ['admin','peminjam','petugas'])): ?>
                    <a href="/pinjam/create" class="btn btn-success btn-sm">
                        Ajukan Pinjaman
                    </a>
                <?php endif ?>
                
                <?php if (session('role') === 'admin'): ?>
                    <a href="<?= site_url('pinjam/trash') ?>" class="btn btn-danger btn-sm">
                        Peminjaman Terhapus
                    </a>
                <?php endif ?>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead class="table-primary text-center">
                        <tr class="align-middle">
                            <th>Jenis Barang</th>
                            <th>Merek Barang</th>
                            <th style="min-width:170px" class="text-nowrap text-center">Tipe Barang</th>
                            <th>Kode Barang</th>
                            <th>Nama Peminjam</th>
                            <th style="min-width:170px" class="text-nowrap text-center">Pengajuan Peminjaman</th>
                            <th style="min-width:170px" class="text-nowrap text-center">Peminjaman Disetujui</th>
                            <th style="min-width:190px" class="text-nowrap text-center">Ajukan Pengembalian</th>
                            <th style="min-width:200px" class="text-nowrap text-center">Pengembalian Disetujui</th>
                            <th>Status</th>
                            <?php if (in_array(session('role'), ['admin','petugas'])): ?>
                                <th style="width:190px;">Aksi</th>
                            <?php endif ?>
                            <th style="width:120px;">Pengembalian</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (count($pinjam) > 0): ?>
                    <?php foreach ($pinjam as $p): ?>

                        <?php
                            // Badge status
                            $status = strtolower(trim($p['status']));
                            $statusBadge = match ($status) {
                                'disetujui'     => 'primary',
                                'pengembalian' => 'warning',
                                'dikembalikan' => 'success',
                                'ditolak'      => 'danger',
                                default        => 'secondary'
                            };
                        ?>

                        <tr>
                            <td>
                                <strong><?= esc($p['jenis_barang']) ?></strong>
                            </td>

                            <td class="text-nowrap text-center">
                                <?= esc($p['merek_barang']) ?>
                            </td>

                            <td class="text-nowrap text-center">
                                <?= esc($p['tipe_barang']) ?>
                            </td>
                            
                            <td class="text-nowrap text-center">
                                <?= esc($p['kode_barang']) ?>
                            </td>

                            <td class="text-nowrap text-center">
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

                            <td class="text-nowrap text-center">
                                <?php if ($status === 'dikembalikan'): ?>
                                    <?= date('d-m-Y H:i', strtotime($p['tgl_disetujui_kembali'])) ?>
                                <?php elseif ($status === 'pengembalian'): ?>
                                    <span class="fw-semibold">
                                        Menunggu konfirmasi
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif ?>
                            </td>

                            <td class="text-nowrap text-center">
                                <span class="badge bg-<?= $statusBadge ?>">
                                    <?= ucfirst($p['status']) ?>
                                </span>

                                <?php if ($status === 'ditolak' && !empty($p['alasan_ditolak'])): ?>
                                    <div class="small text-danger mt-1">
                                        (<?= esc($p['alasan_ditolak']) ?>)
                                    </div>
                                <?php endif ?>
                            </td>

                            <?php if (in_array(session('role'), ['admin','petugas'])): ?>
                            <td class="text-nowrap text-center">
                                <a href="/pinjam/edit/<?= $p['id_pinjam'] ?>"
                                   class="btn btn-warning btn-sm">
                                    Ubah Status
                                </a>

                                <?php if (session('role') === 'admin'): ?>
                                    <a href="/pinjam/delete/<?= $p['id_pinjam'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin hapus data ini?')">
                                        Bersihkan
                                    </a>
                                <?php endif ?>
                            </td>
                            <?php endif ?>

                            <td class="text-nowrap text-center">

                                <!-- PEMINJAM -->
                                <?php if (session('role') === 'peminjam' && $status === 'disetujui'): ?>
                                    <form action="/pinjam/return/<?= $p['id_pinjam'] ?>" method="post">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            Ajukan Pengembalian
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <!-- ADMIN / PETUGAS -->
                                <?php if (in_array(session('role'), ['admin','petugas']) && $status === 'pengembalian'): ?>
                                    <a href="/pinjam/return-check/<?= $p['id_pinjam'] ?>"
                                       class="btn btn-primary btn-sm">
                                        Cek Pengembalian
                                    </a>
                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endforeach ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="12" class="text-center text-muted">
                            Data peminjaman kosong
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
            Total Data: <b><?= $pager->getTotal('pinjam') ?></b> |
            Per Page: <b><?= $pager->getPerPage('pinjam') ?></b> |
            Page: <b><?= $pager->getCurrentPage('pinjam') ?></b> /
            <?= $pager->getPageCount('pinjam') ?>
        </div>

        <div>
            <?php
                $currentPage = $pager->getCurrentPage('pinjam');
                $pageCount   = $pager->getPageCount('pinjam');
                $range       = 1;

                $start = max(1, $currentPage - $range);
                $end   = min($pageCount, $currentPage + $range);

                $jump = ($range * 4) + 1;
            ?>

            <?php if ($pageCount > 1): ?>
            <nav aria-label="Pagination Pinjam">
                <ul class="pagination pagination-sm justify-content-end">

                    <!-- Sebelumnya -->
                    <li class="page-item <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="<?= ($currentPage > 1)
                                ? $pager->getPageURI($currentPage - 1, 'pinjam')
                                : '#' ?>">
                            ‹
                        </a>
                    </li>

                    <!-- Page Pertama -->
                    <li class="page-item <?= (1 == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $pager->getPageURI(1, 'pinjam') ?>">1</a>
                    </li>

                    <!-- TITIK KIRI -->
                    <?php if ($start > 1): ?>
                        <?php $prevJump = max(1, $currentPage - $jump); ?>
                        <li class="page-item">
                            <a class="page-link"
                            href="<?= $pager->getPageURI($prevJump, 'pinjam') ?>"
                            title="Lompat ke halaman <?= $prevJump ?>">
                                ...
                            </a>
                        </li>
                    <?php endif ?>

                    <!-- Nomor Halaman -->
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <?php if ($i > 1 && $i < $pageCount): ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $pager->getPageURI($i, 'pinjam') ?>">
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
                            href="<?= $pager->getPageURI($nextJump, 'pinjam') ?>"
                            title="Lompat ke halaman <?= $nextJump ?>">
                                ...
                            </a>
                        </li>
                    <?php endif ?>

                    <!-- Last page -->
                    <?php if ($pageCount > 1): ?>
                    <li class="page-item <?= ($pageCount == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $pager->getPageURI($pageCount, 'pinjam') ?>">
                            <?= $pageCount ?>
                        </a>
                    </li>
                    <?php endif ?>

                    <!-- Selanjutnya -->
                    <li class="page-item <?= ($currentPage == $pageCount) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="<?= ($currentPage < $pageCount)
                                ? $pager->getPageURI($currentPage + 1, 'pinjam')
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