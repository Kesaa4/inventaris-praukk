<head>
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

<h3>Activity Log</h3>

<a href="/dashboard">Kembali ke Dashboard</a>

<table border="1" cellpadding="8">
<tr>
    <th>Waktu</th>
    <th>Id User</th>
    <th>Nama User</th>
    <th>Role</th>
    <th>Aktivitas</th>
    <th>Tabel</th>
    <th>ID Data</th>
</tr>

<?php foreach ($logs as $l): ?>
<tr>
    <td><?= $l['created_at'] ?></td>
    <td><?= $l['id_user'] ?></td>
    <td>
        <?= esc($l['nama'] ?? explode('@', $l['email'])[0]) ?>
    </td>
    <td><?= esc($l['role'] ?? '-') ?></td>
    <td><?= $l['aktivitas'] ?></td>
    <td><?= $l['tabel'] ?></td>
    <td><?= $l['id_data'] ?></td>
</tr>
<?php endforeach ?>
</table>
