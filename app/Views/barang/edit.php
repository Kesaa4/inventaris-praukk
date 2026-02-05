<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Edit Barang</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- JS 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-4 mb-5">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3> Edit Data Barang</h3>
        <a href="<?= base_url('barang') ?>" class="btn btn-secondary btn-sm">
            ← Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="card shadow-sm">
        <div class="card-body">

            <form action="<?= base_url('/barang/update/' . $barang['id_barang']) ?>" method="post">
                <?= csrf_field() ?>

                <div class="row g-3">

                    <!-- Jenis Barang -->
                    <div class="col-md-6">
                        <label class="form-label">Jenis Barang</label>
                        <input
                            type="text"
                            name="jenis_barang"
                            class="form-control"
                            value="<?= esc($barang['jenis_barang']) ?>"
                            required
                        >
                    </div>

                    <!-- Merek -->
                    <div class="col-md-6">
                        <label class="form-label">Merek Barang</label>
                        <input
                            type="text"
                            name="merek_barang"
                            class="form-control"
                            value="<?= esc($barang['merek_barang']) ?>"
                            required
                        >
                    </div>

                    <!-- Tipe -->
                    <div class="col-md-6">
                        <label class="form-label">Tipe Barang</label>
                        <input
                            type="text"
                            name="tipe_barang"
                            class="form-control"
                            value="<?= esc($barang['tipe_barang']) ?>"
                            required
                        >
                    </div>

                    <!-- Kode -->
                    <div class="col-md-6">
                        <label class="form-label">Kode Barang</label>
                        <input
                            type="text"
                            name="kode_barang"
                            class="form-control"
                            value="<?= esc($barang['kode_barang']) ?>"
                            required
                        >
                    </div>

                    <!-- RAM -->
                    <div class="col-md-3">
                        <label class="form-label">RAM</label>
                        <input
                            type="text"
                            name="ram"
                            class="form-control"
                            value="<?= esc($barang['ram']) ?>"
                        >
                    </div>

                    <!-- ROM -->
                    <div class="col-md-3">
                        <label class="form-label">ROM</label>
                        <input
                            type="text"
                            name="rom"
                            class="form-control"
                            value="<?= esc($barang['rom']) ?>"
                        >
                    </div>

                    <!-- Kategori -->
                    <div class="col-md-6">
                        <label class="form-label">Kategori Kondisi</label>
                        <select name="id_kategori" class="form-select" required>
                            <?php foreach ($kategori as $k): ?>
                                <option
                                    value="<?= $k['id_kategori'] ?>"
                                    <?= $barang['id_kategori'] == $k['id_kategori'] ? 'selected' : '' ?>>
                                    <?= esc($k['kategori_kondisi']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    
                    <?php
                        // normalisasi biar aman
                        $status = strtolower(trim($barang['status']));
                    ?>
                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label d-block">Status Barang</label>

                        <?php
                            $status = strtolower($barang['status']);
                        ?>

                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="status"
                                id="status_tersedia"
                                value="tersedia"
                                <?= $status === 'tersedia' ? 'checked' : '' ?>
                            >
                            <label class="form-check-label" for="status_tersedia">
                                <span class="badge bg-success">Tersedia</span>
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="status"
                                id="status_tidak"
                                value="tidak tersedia"
                                <?= $status === 'tidak tersedia' ? 'checked' : '' ?>
                            >
                            <label class="form-check-label" for="status_tidak">
                                <span class="badge bg-secondary">Tidak Tersedia</span>
                            </label>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <input
                            type="text"
                            name="keterangan"
                            class="form-control"
                            value="<?= esc($barang['keterangan']) ?>"
                        >
                    </div>

                </div>

                <!-- Action -->
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        Update
                    </button>
                    <a href="<?= base_url('barang') ?>" class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
