<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container mt-4">

    <!-- Header -->
    <div class="mb-3">
        <h4 class="fw-bold">Profile Saya</h4>
        <p class="text-muted mb-0">
            Informasi akun dan data pribadi Anda
        </p>
    </div>

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
            <div class="d-flex justify-content-between">
                <a href="/dashboard" class="btn btn-secondary">
                    Kembali ke Dashboard
                </a>

                <a href="/profile/edit" class="btn btn-primary">
                    Edit Profile
                </a>
            </div>

        </div>
    </div>

</div>
