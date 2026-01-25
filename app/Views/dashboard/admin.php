<h2>Dashboard Admin</h2>

<p>Total Barang: <?= $totalBarang ?></p>

<h2>Dashboard</h2>

<p>
Login sebagai:
<b><?= explode('@', session()->get('email'))[0] ?></b>
(<?= session()->get('role') ?>)
</p>

<ul>
    <li><a href="<?= base_url('pinjam') ?>" class="btn btn-primary">Data Peminjaman</a></li>
    <li><a href="<?= base_url('barang') ?>">Data Barang</a></li>

    <?php if (session()->get('role') === 'admin'): ?>
        <li><a href="/user">Manajemen User</a></li>
    <?php endif ?>

    <li><a href="/profile">Profile Saya</a></li>
    <li><a href="/logout">Logout</a></li>
</ul>

