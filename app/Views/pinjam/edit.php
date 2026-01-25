<!DOCTYPE html>
<html>
<head>
    <title>Ubah Status Peminjaman</title>
</head>
<body>

<h2>Ubah Status Peminjaman</h2>
<?php if ($pinjam): ?>
<p><b>Peminjam:</b> <?= esc($pinjam['email']) ?></p>
<?php endif; ?>
<p><b>Barang:</b> 
    <?= esc($pinjam['jenis_barang']) ?> -
    <?= esc($pinjam['merek_barang']) ?> -
    <?= esc($pinjam['tipe_barang']) ?>
</p>

<p><b>Tanggal Pinjam:</b> <?= $pinjam['tgl_pinjam'] ?></p>
<p><b>Tanggal Kembali:</b> <?= $pinjam['tgl_kembali'] ?></p>

<form action="<?= base_url('pinjam/update/'.$pinjam['id_pinjam']) ?>" method="post">
    <?= csrf_field() ?>

    <label>Status</label><br>

    <select name="status" required>
        <option value="menunggu" <?= $pinjam['status'] == 'menunggu' ? 'selected' : '' ?>>
            Menunggu
        </option>
        <option value="disetujui" <?= $pinjam['status'] == 'disetujui' ? 'selected' : '' ?>>
            Disetujui
        </option>
        <option value="ditolak" <?= $pinjam['status'] == 'ditolak' ? 'selected' : '' ?>>
            Ditolak
        </option>
        <option value="dikembalikan" <?= $pinjam['status'] == 'dikembalikan' ? 'selected' : '' ?>>
            Dikembalikan
        </option>
    </select>

    <br><br>

    <button type="submit">Simpan Perubahan</button>
    <a href="<?= base_url('/pinjam') ?>">Kembali</a>
</form>

</body>
</html>
