<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container mt-4">

    <!-- Header -->
    <div class="mb-3">
        <h4 class="fw-bold">Konfirmasi Pengembalian</h4>
        <p class="text-muted mb-0">
            Pastikan kondisi barang sebelum menyetujui pengembalian
        </p>
    </div>

    <!-- Card -->
    <div class="card shadow-sm">
        <div class="card-body">

            <!-- Info -->
            <div class="mb-3">
                <div class="row mb-2">
                    <div class="col-md-3 fw-semibold">Peminjam</div>
                    <div class="col-md-9">
                        <?= esc(explode('@', $pinjam['email'])[0]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 fw-semibold">Barang</div>
                    <div class="col-md-9">
                        <?= esc($pinjam['jenis_barang']) ?> -
                        <?= esc($pinjam['merek_barang']) ?> -
                        <?= esc($pinjam['tipe_barang']) ?>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Form -->
            <form action="/pinjam/return-update/<?= $pinjam['id_pinjam'] ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-check mb-4">
                    <input class="form-check-input"
                           type="checkbox"
                           name="status"
                           value="dikembalikan"
                           id="status_dikembalikan"
                           required>
                    <label class="form-check-label fw-semibold"
                           for="status_dikembalikan">
                        Barang sudah dikembalikan
                    </label>
                </div>

                <!-- Action -->
                <div class="d-flex justify-content-between">
                    <a href="/pinjam" class="btn btn-secondary">
                        ← Kembali
                    </a>

                    <button type="submit" class="btn btn-success">
                        Simpan Konfirmasi
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
