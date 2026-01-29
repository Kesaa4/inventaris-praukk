<?php if (session()->getFlashdata('errors')): ?>
    <div style="color:red">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <p><?= esc($error) ?></p>
        <?php endforeach ?>
    </div>
<?php endif ?>

<h3>Tambah User</h3>

<a href="/user">Kembali</a>

<form action="/user/store" method="post">
    <?= csrf_field() ?>

    <p>
        <label>Nama</label><br>
        <input type="text" name="nama" required>
    </p>

    <p>
        <label>Email</label><br>
        <input type="email" name="email" required>
    </p>

    <p>
        <label>Password</label><br>
        <input type="password" name="password" required>
    </p>

    <p>
        <label>Role</label><br>
        <select name="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="admin">Admin</option>
            <option value="petugas">Petugas</option>
            <option value="peminjam">Peminjam</option>
        </select>
    </p>

    <button type="submit">Simpan</button>
</form>
