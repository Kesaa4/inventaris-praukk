<!DOCTYPE html>
<html>
<head>
    <title>Manajemen User</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
        }
        th {
            background: #eee;
        }
    </style>
</head>
<body>

<h2>Manajemen User</h2>

<p>Login sebagai: <?= session()->get('nama') ?></b> (<?= session()->get('role') ?>)</p>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color:green"><?= session()->getFlashdata('success') ?></p>
<?php endif ?>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Email</th>
            <th>Nama Lengkap</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php $no = 1; foreach ($users as $u): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= esc($u['email']) ?></td>
            <td><?= esc($u['nama'] ?? '-') ?></td>
            <td><?= esc($u['role']) ?></td>
            <td>
                <a href="/user/edit/<?= $u['id_user'] ?>">Edit</a> |
                <a href="/user/delete/<?= $u['id_user'] ?>"
                   onclick="return confirm('Yakin hapus user ini?')">
                   Hapus
                </a>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>

<br>
<a href="/dashboard">Kembali ke Dashboard</a>
<a href="/user/create">Tambah Data</a>
</body>
</html>
