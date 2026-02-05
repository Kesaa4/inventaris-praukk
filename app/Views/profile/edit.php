<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Edit Profile</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- JS 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-4">

    <!-- Header -->
    <div class="mb-3">
        <h4 class="fw-bold">Edit Profile</h4>
        <p class="text-muted mb-0">
            Perbarui data pribadi Anda
        </p>
    </div>

    <!-- Card -->
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
                <div class="d-flex justify-content-between">
                    <a href="/profile" class="btn btn-secondary">
                        ← Kembali
                    </a>

                    <button type="submit" class="btn btn-success">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
    
</body>
</html>