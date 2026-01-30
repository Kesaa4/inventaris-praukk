<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Data Peminjaman</h4>

        <div>
            <?php if (in_array(session('role'), ['admin','peminjam','petugas'])): ?>
                <a href="/pinjam/create" class="btn btn-success btn-sm">
                    Ajukan Pinjaman
                </a>
            <?php endif ?>
            <a href="/dashboard" class="btn btn-secondary btn-sm">
                Kembali Ke Dashboard
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Barang</th>
                            <th>Merek</th>
                            <th>Tipe</th>
                            <th>Peminjam</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <?php if (in_array(session('role'), ['admin','petugas'])): ?>
                                <th>Aksi</th>
                            <?php endif ?>
                            <th>Pengembalian</th>
                        </tr>
                    </thead>
                    <tbody>

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

                            <td class="text-center">
                                <?= esc($p['merek_barang']) ?>
                            </td>

                            <td class="text-center">
                                <?= esc($p['tipe_barang']) ?>
                            </td>

                            <td>
                                <?= esc(explode('@', $p['email'])[0]) ?>
                            </td>

                            <td class="text-center">
                                <?= date('d-m-Y', strtotime($p['tgl_pinjam'])) ?>
                            </td>

                            <td class="text-center">
                                <?php if ($status === 'dikembalikan'): ?>
                                    <?= date('d-m-Y H:i', strtotime($p['tgl_kembali'])) ?>
                                <?php elseif ($status === 'pengembalian'): ?>
                                    <span class="fw-semibold">
                                        Menunggu konfirmasi
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif ?>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-<?= $statusBadge ?>">
                                    <?= ucfirst($p['status']) ?>
                                </span>
                            </td>

                            <?php if (in_array(session('role'), ['admin','petugas'])): ?>
                            <td class="text-center">
                                <a href="/pinjam/edit/<?= $p['id_pinjam'] ?>"
                                   class="btn btn-warning btn-sm mb-1">
                                    Ubah Status
                                </a>

                                <?php if (session('role') === 'admin'): ?>
                                    <a href="/pinjam/delete/<?= $p['id_pinjam'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin hapus data ini?')">
                                        Hapus
                                    </a>
                                <?php endif ?>
                            </td>
                            <?php endif ?>

                            <td class="text-center">

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

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
