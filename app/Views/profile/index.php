<?= view('layouts/header', ['title' => 'Profile']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">
        <div class="content-wrapper fade-in">

            <!-- Header -->
            <div class="page-header">
                <h4><i class="bi bi-person-circle me-2"></i>Profile Saya</h4>
                <p class="text-muted">Informasi akun dan data pribadi Anda</p>
            </div>

    <!-- Alert Success -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <!-- Card -->
    <div class="card shadow-sm">
        <div class="card-body">

            <!-- Informasi Akun -->
            <h6 class="fw-semibold mb-3 text-primary">Informasi Akun</h6>

            <div class="row mb-2">
                <div class="col-md-3 fw-semibold">Email</div>
                <div class="col-md-9">
                    <?= esc(session()->get('email')) ?>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 fw-semibold">Role</div>
                <div class="col-md-9">
                    <span class="badge bg-info text-dark">
                        <?= esc(session()->get('role')) ?>
                    </span>
                </div>
            </div>

            <hr>

            <!-- Informasi Pribadi -->
            <h6 class="fw-semibold mb-3 text-primary">Data Pribadi</h6>

            <div class="row mb-2">
                <div class="col-md-3 fw-semibold">Nama</div>
                <div class="col-md-9">
                    <?= esc($profile['nama'] ?? '-') ?>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 fw-semibold">No. HP</div>
                <div class="col-md-9">
                    <?= esc($profile['no_hp'] ?? '-') ?>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 fw-semibold">Alamat</div>
                <div class="col-md-9">
                    <?= esc($profile['alamat'] ?? '-') ?>
                </div>
            </div>

            <!-- Action -->
            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                <a href="/profile/edit" class="btn btn-primary">
                    Edit Profile
                </a>
            </div>

        </div>
    </div>

        </div>
    </div>
</div>

<?= view('layouts/footer') ?>