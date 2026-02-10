<?= view('layouts/header', ['title' => 'Edit Profile']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">

        <!-- Header -->
        <div class="mb-4 text-center">
            <h4 class="fw-bold">Edit Profile</h4>
            <p class="text-muted mb-0">
                Perbarui data pribadi Anda
            </p>
        </div>

        <!-- Card -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif ?>

                        <form method="post" action="/profile/update">
                            <?= csrf_field() ?>

                            <!-- Nama -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="nama"
                                    class="form-control"
                                    value="<?= esc($profile['nama'] ?? '') ?>"
                                    placeholder="Nama Lengkap"
                                    required
                                >
                            </div>

                            <!-- No HP -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    No Handphone
                                </label>
                                <input
                                    type="text"
                                    name="no_hp"
                                    class="form-control"
                                    value="<?= esc($profile['no_hp'] ?? '') ?>"
                                    placeholder="No HP"
                                >
                            </div>

                            <!-- Alamat -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Alamat
                                </label>
                                <textarea
                                    name="alamat"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Alamat"
                                ><?= esc($profile['alamat'] ?? '') ?></textarea>
                            </div>

                            <hr class="my-4">

                            <!-- Section Password -->
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-lock-fill me-2"></i>Ubah Password
                                </h6>
                                <p class="text-muted small mb-3">
                                    Kosongkan jika tidak ingin mengubah password
                                </p>
                            </div>

                            <!-- Password Lama -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Password Lama
                                </label>
                                <input
                                    type="password"
                                    name="password_lama"
                                    class="form-control"
                                    placeholder="Masukkan password lama"
                                    id="password_lama"
                                >
                                <small class="text-muted">
                                    Wajib diisi jika ingin mengubah password
                                </small>
                            </div>

                            <!-- Password Baru -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Password Baru
                                </label>
                                <input
                                    type="password"
                                    name="password_baru"
                                    class="form-control"
                                    placeholder="Masukkan password baru"
                                    id="password_baru"
                                    minlength="6"
                                >
                                <small class="text-muted">
                                    Minimal 6 karakter
                                </small>
                            </div>

                            <!-- Konfirmasi Password Baru -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Konfirmasi Password Baru
                                </label>
                                <input
                                    type="password"
                                    name="password_konfirmasi"
                                    class="form-control"
                                    placeholder="Ulangi password baru"
                                    id="password_konfirmasi"
                                >
                            </div>

                            <!-- Action -->
                            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
                                <a href="/profile" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali
                                </a>

                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
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