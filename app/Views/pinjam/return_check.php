<h3>Konfirmasi Pengembalian</h3>

<p><b>Peminjam:</b> <?= esc($pinjam['email']) ?></p>
<p><b>Barang:</b>
    <?= esc($pinjam['jenis_barang']) ?> -
    <?= esc($pinjam['merek_barang']) ?> -
    <?= esc($pinjam['tipe_barang']) ?>
</p>

<form action="/pinjam/return-update/<?= $pinjam['id_pinjam'] ?>" method="post">
    <select name="status" required>
        <option value="dikembalikan">Dikembalikan</option>
        <option value="rusak">Rusak</option>
    </select>

    <button type="submit">Simpan</button>
</form>
