<h2>Edit Profile</h2>

<form method="post" action="/profile/update">
    <?= csrf_field() ?>

    <p>
    <label for="">Nama Lengkap</label>
    <input type="text" name="nama"
        value="<?= $profile['nama'] ?? '' ?>"
        placeholder="Nama Lengkap" required>
    </p>

    <label for="">No Handphone</label>
    <input type="text" name="no_hp"
        value="<?= $profile['no_hp'] ?? '' ?>"
        placeholder="No HP">

    <label for="">Alamat</label>
    <textarea name="alamat"
        placeholder="Alamat"><?= $profile['alamat'] ?? '' ?></textarea>

    <button type="submit">Simpan</button>
    <a href="/profile">Kembali</a>
</form>
