<?= view('layouts/header', ['title' => 'Tambah Kategori']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">
        <div class="content-wrapper fade-in">

            <!-- Header -->
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h3><?= $title ?></h3>
                    <p class="text-muted mb-0">Tambah kategori barang baru</p>
                </div>
                <a href="<?= site_url('kategori') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <!-- Form Card -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form action="<?= site_url('kategori/store') ?>" method="post">
                                <?= csrf_field() ?>

                                <!-- Nama Kategori -->
                                <div class="mb-3">
                                    <label for="nama_kategori" class="form-label">
                                        Nama Kategori <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control <?= session('errors.nama_kategori') ? 'is-invalid' : '' ?>" 
                                           id="nama_kategori" 
                                           name="nama_kategori" 
                                           value="<?= old('nama_kategori') ?>"
                                           placeholder="Contoh: Laptop, Smartphone, Tablet"
                                           required>
                                    <?php if (session('errors.nama_kategori')): ?>
                                        <div class="invalid-feedback">
                                            <?= session('errors.nama_kategori') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Buttons -->
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= site_url('kategori') ?>" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i>Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?= view('layouts/footer') ?>
