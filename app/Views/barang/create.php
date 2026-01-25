<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
</head>
<body>

<h2>Tambah Barang</h2>

<form action="<?= base_url('/barang/store') ?>" method="post">
    <?= csrf_field() ?>

    <p>
        <label>Jenis Barang</label><br>
        <select name="jenis_barang" required>
            <option value="">-- Pilih Jenis Barang --</option>
            <?php foreach ($jenis_barang as $jenis) : ?>
                <option value="<?= $jenis ?>">
                    <?= $jenis ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>
        <label>Merek Barang</label><br>
        <input type="text" name="merek_barang" required>
    </p>

    <p>
        <label>Tipe Barang</label><br>
        <input type="text" name="tipe_barang" required>
    </p>

    <p>
        <label>RAM</label><br>
        <input type="text" name="ram" required>
    </p>

    <p>
        <label>ROM</label><br>
        <input type="text" name="rom" required>
    </p>

    <p>
        <label>Kategori Kondisi</label><br>
        <select name="id_kategori" required>
            <option value="">-- Pilih Kategori Kondisi --</option>
            <?php foreach ($kategori as $k) : ?>
                <option value="<?= $k['id_kategori'] ?>">
                    <?= esc($k['kategori_kondisi']) ?>
                </option>
            <?php endforeach ?>
        </select>
    </p>

    <p>
        <label>Status</label><br>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="status" id="" value="Tersedia" required>
            <label class="form-check-label" for="status_tersedia">Tersedia</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="status" id="" value="Tidak Tersedia">
            <label class="form-check-label" for="status_tidak">Tidak Tersedia</label>
        </div>
    </p>

    <p>
        <label for="">Keterangan</label>
        <input type="text" name="keterangan" id="">
    </p>

    <button type="submit">Simpan</button>
    <a href="<?= base_url('index.php/barang') ?>">Kembali</a>
</form>

</body>
</html>
