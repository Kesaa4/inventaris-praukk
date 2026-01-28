<form method="post" action="/user/update/<?= $user['id_user'] ?>">
<?= csrf_field() ?>

<label for="">Nama Lengkap</label>
<input type="text" name="nama" value="<?= $profile['nama'] ?? '' ?>"><br><br>

<label for="">No Handphone</label>
<input type="text" name="no_hp" value="<?= $profile['no_hp'] ?? '' ?>"><br><br>

<label for="">Alamat</label>
<textarea name="alamat"><?= $profile['alamat'] ?? '' ?></textarea><br><br>

<label>Role</label>
<select name="role">
    <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
    <option value="petugas" <?= $user['role']=='petugas'?'selected':'' ?>>Petugas</option>
    <option value="peminjam" <?= $user['role']=='peminjam'?'selected':'' ?>>Peminjam</option>
</select><br><br>

<button type="submit">Update</button>
<a href="/user">Kembali</a>
</form>
