<h2>Edit Profile</h2>

<form method="post" action="/profile/update">
    <?= csrf_field() ?>

    <p>
    <label for="">Nama Lengkap</label>
    <input type="text" name="nama"
        value="<?= $profile['nama'] ?? '' ?>"
        placeholder="Nama Lengkap" required>
    </p><br>

    <label for="">No Handphone</label>
    <input type="text" name="no_hp"
        value="<?= $profile['no_hp'] ?? '' ?>"
        placeholder="No HP"><br>

    <label for="">Alamat</label>
    <textarea name="alamat"
        placeholder="Alamat"><?= $profile['alamat'] ?? '' ?></textarea><br>

    <button type="submit">Simpan</button><br>
    <a href="/profile">Kembali</a>
</form>
