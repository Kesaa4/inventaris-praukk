<h2>Dashboard Peminjam</h2>

<p>Selamat datang, <?= explode('@', session()->get('email'))[0] ?></p>

<a href="<?= base_url('barang') ?>">Lihat Barang</a> |
<a href="<?= base_url('logout') ?>">Logout</a>

<p>
Login sebagai:
<strong><?= explode('@', session()->get('email'))[0] ?></strong>
(<?= session()->get('role') ?>)
</p>

<li><a href="/profile">Profile Saya</a></li>


