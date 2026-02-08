<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Tambah Peminjaman</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- JS 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-4">

    <!-- Header -->
    <div class="mb-3">
        <h4 class="fw-bold">Ajukan Peminjaman</h4>
        <p class="text-muted mb-0">
            Silakan pilih barang yang akan dipinjam
        </p>
    </div>

    <!-- Card Form -->
    <div class="card shadow-sm">
        <div class="card-body">

            <form action="/pinjam/store" method="post">
                <?= csrf_field() ?>

                <!-- Admin / Petugas pilih peminjam -->
                <?php if (in_array(session('role'), ['admin', 'petugas'])): ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Peminjam
                        </label>
                        <select name="id_user" class="form-select" required>
                            <option value="">-- Pilih Peminjam --</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id_user'] ?>">
                                    <?= esc(explode('@', $u['email'])[0]) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                <?php endif ?>

                <!-- Pilih Barang -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Barang
                    </label>
                    <select name="id_barang" class="form-select" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($barang as $b): ?>
                            <option value="<?= $b['id_barang'] ?>">
                                <?= esc($b['jenis_barang']) ?>
                                - <?= esc($b['merek_barang']) ?>
                                - <?= esc($b['tipe_barang']) ?>
                                - <?= esc($b['kode_barang']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Button -->
                <div class="d-flex justify-content-between">
                    <a href="/pinjam" class="btn btn-secondary">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-success">
                        Ajukan Peminjaman
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>