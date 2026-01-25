<form action="/barang/update/<?= $barang['id_barang'] ?>" method="post">
    <?= csrf_field() ?>

    <input type="text" name="jenis_barang" value="<?= $barang['jenis_barang'] ?>" required>

    <select name="id_kategori">
        <?php foreach ($kategori as $k): ?>
            <option value="<?= $k['id_kategori'] ?>"
                <?= $barang['id_kategori'] == $k['id_kategori'] ? 'selected' : '' ?>>
                <?= $k['kategori_kondisi'] ?>
            </option>
        <?php endforeach ?>
    </select>

    <select name="status">
        <option value="Tersedia" <?= $barang['status'] == 1 ? 'selected' : '' ?>>Tersedia</option>
        <option value="Tidak Tersedia" <?= $barang['status'] == 0 ? 'selected' : '' ?>>Tidak Tersedia</option>
    </select>

    <input type="text" name="merek_barang" value="<?= $barang['merek_barang'] ?>">
    <input type="text" name="tipe_barang" value="<?= $barang['tipe_barang'] ?>">
    <input type="text" name="ram" value="<?= $barang['ram'] ?>">
    <input type="text" name="rom" value="<?= $barang['rom'] ?>">
    <input type="text" name="keterangan" value="<?= $barang['keterangan'] ?>">

    <button type="submit">Update</button>
</form>
