<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Edit Peminjaman</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- JS 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-4">

    <!-- Header -->
    <div class="mb-3">
        <h4 class="fw-bold">Ubah Status Peminjaman</h4>
        <p class="text-muted mb-0">
            Perbarui status peminjaman sesuai kondisi terbaru
        </p>
    </div>

    <!-- Card -->
    <div class="card shadow-sm">
        <div class="card-body">

            <!-- Info -->
            <?php if ($pinjam): ?>
                <div class="mb-3">
                    <div class="row mb-2">
                        <div class="col-md-3 fw-semibold">Peminjam</div>
                        <div class="col-md-9">
                            <?= esc(explode('@', $pinjam['email'])[0]) ?>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3 fw-semibold">Barang</div>
                        <div class="col-md-9">
                            <?= esc($pinjam['jenis_barang']) ?> -
                            <?= esc($pinjam['merek_barang']) ?> -
                            <?= esc($pinjam['tipe_barang']) ?>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3 fw-semibold">Tanggal Pinjam</div>
                        <div class="col-md-9">
                            <?= date('d-m-Y', strtotime($pinjam['tgl_pinjam'])) ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <hr>

            <!-- Form -->
            <form action="<?= base_url('pinjam/update/'.$pinjam['id_pinjam']) ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Status Peminjaman</label>
                    <select name="status" class="form-select" required>
                        <option value="menunggu"
                            <?= $pinjam['status'] === 'menunggu' ? 'selected' : '' ?>>
                            Menunggu
                        </option>
                        <option value="disetujui"
                            <?= $pinjam['status'] === 'disetujui' ? 'selected' : '' ?>>
                            Disetujui
                        </option>
                        <option value="ditolak"
                            <?= $pinjam['status'] === 'ditolak' ? 'selected' : '' ?>>
                            Ditolak
                        </option>
                        <?php if ($pinjam['status'] === 'dikembalikan'): ?>
                            <option value="dikembalikan" selected>
                                Dikembalikan
                            </option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Action -->
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('/pinjam') ?>" class="btn btn-secondary">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
    
</body>
</html>