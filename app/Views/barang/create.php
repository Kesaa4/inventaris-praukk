<?= view('layouts/header', ['title' => 'Tambah Barang']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">

        <!-- Header -->
        <div class="mb-3">
            <h3 class="fw-bold">Tambah Data Barang</h3>
        </div>

    <!-- Form Card -->
    <div class="card shadow-sm">
        <div class="card-body">

            <form action="<?= base_url('/barang/store') ?>" method="post">
                <?= csrf_field() ?>

                <div class="row g-3">

                    <!-- Jenis Barang -->
                    <div class="col-md-6">
                        <label class="form-label">Jenis Barang</label>
                        <select name="jenis_barang" class="form-select" required>
                            <option value="">-- Pilih Jenis Barang --</option>
                            <?php foreach ($jenis_barang as $jenis): ?>
                                <option value="<?= esc($jenis) ?>">
                                    <?= esc($jenis) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <!-- Merek -->
                    <div class="col-md-6">
                        <label class="form-label">Merek Barang</label>
                        <input type="text" name="merek_barang" class="form-control" required>
                    </div>

                    <!-- Tipe -->
                    <div class="col-md-6">
                        <label class="form-label">Tipe Barang</label>
                        <input type="text" name="tipe_barang" class="form-control" required>
                    </div>

                    <!-- Kode -->
                    <div class="col-md-6">
                        <label class="form-label">Kode Barang</label>
                        <input type="text" name="kode_barang" class="form-control" required>
                    </div>

                    <!-- RAM -->
                    <div class="col-md-3">
                        <label class="form-label">RAM</label>
                        <input type="text" name="ram" class="form-control" placeholder="contoh: 8 GB" required>
                    </div>

                    <!-- ROM -->
                    <div class="col-md-3">
                        <label class="form-label">ROM</label>
                        <input type="text" name="rom" class="form-control" placeholder="contoh: 128 GB" required>
                    </div>

                    <!-- Kategori -->
                    <div class="col-md-6">
                        <label class="form-label">Kategori Kondisi</label>
                        <select name="id_kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori Kondisi --</option>
                            <?php foreach ($kategori as $k): ?>
                                <option value="<?= $k['id_kategori'] ?>">
                                    <?= esc($k['kategori_kondisi']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label d-block">Status Barang</label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_tersedia" value="tersedia" required>
                            <label class="form-check-label" for="status_tersedia">
                                <span class="badge bg-success">Tersedia</span>
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_tidak" value="tidak tersedia">
                            <label class="form-check-label" for="status_tidak">
                                <span class="badge bg-secondary">Tidak Tersedia</span>
                            </label>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
                    </div>

                </div>

                <!-- Action -->
                <div class="mt-4 d-flex flex-column flex-sm-row gap-2">
                    <button type="submit" class="btn btn-success">
                        Tambah
                    </button>
                    <a href="<?= base_url('barang') ?>" class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

    </div>
</div>

<?= view('layouts/footer') ?>
