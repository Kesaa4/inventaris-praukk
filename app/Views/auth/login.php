<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventaris</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<!-- Form Login -->
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <i class="bi bi-box-seam" style="font-size: 3rem;"></i>
            <h3 class="mt-3">Sistem Inventaris</h3>
            <p class="mb-0">Silakan login untuk melanjutkan</p>
        </div>
        
        <div class="login-body">

            <!-- Alert -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif ?>

            <!-- Card -->
            <form method="post" action="<?= base_url('/login') ?>">
                <?= csrf_field() ?>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope me-1"></i>Email
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-control"
                        placeholder="Masukkan email"
                        required
                    >
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock me-1"></i>Password
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-control"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <!-- Show Password -->
                <div class="form-check mb-4">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="showPassword" 
                        onclick="togglePassword()"
                    >
                    <label class="form-check-label" for="showPassword">
                        Tampilkan password
                    </label>
                </div>

                <!-- Button -->
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
