<h3>Ajukan Peminjaman</h3>

<form action="/pinjam/store" method="post">

    <?php if (in_array(session('role'), ['admin', 'petugas'])): ?>
        <label>Peminjam</label><br>
        <select name="id_user" required>
            <option value="">-- Pilih Peminjam --</option>
            <?php foreach ($users as $u): ?>
                <option value="<?= $u['id_user'] ?>">
                    <?= $u['email'] ?>
                </option>
            <?php endforeach ?>
        </select><br><br>
    <?php endif ?>

    <label>Barang</label><br>
    <select name="id_barang" required>
        <?php foreach ($barang as $b): ?>
            <option value="<?= $b['id_barang'] ?>">
                <?= $b['jenis_barang'].' - '.$b['merek_barang'].' - '.$b['tipe_barang'] ?>
            </option>
        <?php endforeach ?>
    </select><br><br>

    <button type="submit">Ajukan</button>
    <a href="/pinjam">Kembali</a>
</form>
