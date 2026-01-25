<h2>Dashboard Petugas</h2>

<a href="<?= base_url('barang') ?>">Lihat Data Barang</a> |
<a href="<?= base_url('pinjam') ?>" class="btn btn-warning">Kelola Peminjaman</a> |
<a href="<?= base_url('logout') ?>">Logout</a>

<p>
Login sebagai:
<strong><?= explode('@', session()->get('email'))[0] ?></strong>
(<?= session()->get('role') ?>)
</p>

<li><a href="/profile">Profile Saya</a></li>