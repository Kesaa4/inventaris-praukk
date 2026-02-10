<?= view('layouts/header', ['title' => 'Konfirmasi Pengembalian']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">

        <!-- Header -->
        <div class="mb-3">
            <h4 class="fw-bold">Konfirmasi Pengembalian</h4>
            <p class="text-muted mb-0">
                Pastikan kondisi barang sebelum menyetujui pengembalian
            </p>
        </div>

        <!-- Card -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <!-- Info -->
                        <div class="mb-3">
                            <div class="row mb-2">
                                <div class="col-md-5 fw-semibold">Peminjam</div>
                                <div class="col-md-7">
                                    <?php 
                                        $displayName = !empty($pinjam['nama']) ? $pinjam['nama'] : explode('@', $pinjam['email'])[0];
                                        echo esc($displayName);
                                    ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 fw-semibold">Barang</div>
                                <div class="col-md-7">
                                    <?= esc($pinjam['jenis_barang']) ?> -
                                    <?= esc($pinjam['merek_barang']) ?> -
                                    <?= esc($pinjam['tipe_barang']) ?>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-5 fw-semibold">Tgl Pengajuan Return</div>
                                <div class="col-md-7">
                                    <?= date('d-m-Y H:i', strtotime($pinjam['tgl_pengajuan_kembali'])) ?>
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
                            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
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
        </div>

    </div>
</div>

<?= view('layouts/footer') ?>