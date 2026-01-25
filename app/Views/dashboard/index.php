<form method="get" action="<?= base_url('dashboard') ?>">
    <input type="text" name="keyword" placeholder="Cari Jenis atau Merek..." value="<?= $keyword ?>">
    
    <select name="kategori">
        <option value="">Semua Kategori</option>
        <?php foreach ($kategori as $k): ?>
            <option value="<?= $k['id_kategori'] ?>"
                <?= $catFilter == $k['id_kategori'] ? 'selected' : '' ?>>
                <?= $k['kategori_kondisi'] ?>
            </option>
        <?php endforeach ?>
    </select>

    <button type="submit">Cari</button>
</form>

<a href="<?= base_url('/dashboard/create') ?>">Tambah Barang</a>
<a href="<?= base_url('dashboard') ?>">Reset</a>


<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>No</th>
            <th>Jenis Barang</th>
            <th>Merek Barang</th>
            <th>Tipe Barang</th>
            <th>RAM</th>
            <th>ROM</th>
            <th>Kategori Kondisi</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach ($barang as $b) : ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= esc($b['jenis_barang']) ?></td>
            <td><?= esc($b['merek_barang']) ?></td>
            <td><?= esc($b['tipe_barang']) ?></td>
            <td><?= esc($b['ram']) ?></td>
            <td><?= esc($b['rom']) ?></td>
            <td><?= esc($b['kategori_kondisi']) ?></td>
            <td><?= esc($b['status']) ?></td>
            <td><?= esc($b['keterangan']) ?></td>
            <td>
                <a href="/dashboard/edit/<?= $b['id_barang'] ?>">Edit</a> |
                <a href="/dashboard/delete/<?= $b['id_barang'] ?>" 
                onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?= $pager->links('barang', 'default_full') ?>
Total Data: <?= $pager->getTotal('barang') ?><br>
Per Page: <?= $pager->getPerPage('barang') ?><br>
Current Page: <?= $pager->getCurrentPage('barang') ?><br>
Page Count: <?= $pager->getPageCount('barang') ?>


