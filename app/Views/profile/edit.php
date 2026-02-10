<?= view('layouts/header', ['title' => 'Edit Profile']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">

        <!-- Header -->
        <div class="mb-3">
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

                        <form method="post" action="/profile/update">
                            <?= csrf_field() ?>

                            <!-- Nama -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Nama Lengkap
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
                            <div class="mb-4">
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

                            <!-- Action -->
                            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
                                <a href="/profile" class="btn btn-secondary">
                                    Kembali
                                </a>

                                <button type="submit" class="btn btn-success">
                                    Simpan Perubahan
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