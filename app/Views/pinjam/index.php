<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
        }
        th {
            background: #eee;
        }
    </style>
</head>

<h3>Data Peminjaman</h3>

<a href="/dashboard">Kembali ke Dashboard</a>

<?php if (session('role') === 'peminjam'): ?>
<a href="/pinjam/create">Ajukan Pinjaman</a>
<?php endif ?>

<table border="1" cellpadding="8">
<tr>
    <th>Barang</th>
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

<?php foreach ($pinjam as $p): ?>
<tr>
    <td><?= $p['jenis_barang'].' - '.$p['merek_barang'] ?></td>
    <td><?= $p['tipe_barang'] ?></td>
    <td><?= explode('@', $p['email'])[0] ?></td>
    <td><?= $p['tgl_pinjam'] ?></td>
    <td>
        <?php if ($p['status'] === 'dikembalikan'): ?>
            <?= date('d-m-Y H:i', strtotime($p['tgl_kembali'])) ?>
        <?php elseif ($p['status'] === 'pengembalian'): ?>
            Menunggu konfirmasi
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
    <td><?= $p['status'] ?></td>
    <?php if (in_array(session('role'), ['admin','petugas'])): ?>
    <td>
        <?php if (in_array(session('role'), ['admin','petugas'])): ?>
            <a href="/pinjam/edit/<?= $p['id_pinjam'] ?>">Ubah Status</a>
        <?php endif ?>

        <?php if (session('role') === 'admin'): ?>
            | <a href="/pinjam/delete/<?= $p['id_pinjam'] ?>">Hapus</a>
        <?php endif ?>
    </td>
    <?php endif ?>
    <td>
        <!-- PEMINJAM -->
        <?php if (session('role') === 'peminjam' && trim($p['status']) === 'disetujui'): ?>
            <form action="/pinjam/return/<?= $p['id_pinjam'] ?>" method="post">
                <button type="submit" class="btn btn-warning btn-sm">
                    Ajukan Pengembalian
                </button>
            </form>
        <?php endif; ?>

        <!-- ADMIN / PETUGAS -->
        <?php if (in_array(session('role'), ['admin','petugas']) && trim($p['status']) === 'pengembalian'): ?>
            <a href="/pinjam/return-check/<?= $p['id_pinjam'] ?>">
                Cek Pengembalian
            </a>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach ?>
</table>
