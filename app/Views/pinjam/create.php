<?= view('layouts/header', ['title' => 'Ajukan Peminjaman']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">

        <!-- Header -->
        <div class="mb-3">
            <h4 class="fw-bold">Ajukan Peminjaman</h4>
            <p class="text-muted mb-0">
                Silakan pilih barang yang akan dipinjam
            </p>
        </div>

        <!-- Card Form -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
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
                                                <?php 
                                                    $displayName = !empty($u['nama']) ? $u['nama'] : explode('@', $u['email'])[0];
                                                    echo esc($displayName);
                                                ?>
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
                            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
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
        </div>

    </div>
</div>

<?= view('layouts/footer') ?>