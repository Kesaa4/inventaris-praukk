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
    
    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7fafc;
            position: relative;
            overflow: hidden;
        }

        .login-page::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveBackground 20s linear infinite;
        }

        @keyframes moveBackground {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
            padding: 0 1rem;
        }

        .login-box {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
            border: 1px solid #e2e8f0;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
        }

        .login-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 20px solid #764ba2;
        }

        .login-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(255, 255, 255, 0);
            }
        }

        .login-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .login-header h3 {
            color: white;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            font-size: 1.75rem;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            font-size: 0.95rem;
        }

        .login-body {
            padding: 2.5rem 2rem 2rem;
        }

        .form-floating {
            margin-bottom: 1.25rem;
        }

        .form-floating .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1rem 1rem 1rem 3rem;
            height: 58px;
            transition: all 0.3s ease;
        }

        .form-floating .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        .form-floating label {
            padding-left: 3rem;
            color: #718096;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.25rem;
            z-index: 4;
            transition: color 0.3s ease;
        }

        .form-floating .form-control:focus ~ .input-icon {
            color: #667eea;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a0aec0;
            font-size: 1.25rem;
            cursor: pointer;
            z-index: 4;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .form-check {
            margin-bottom: 1.5rem;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-check-label {
            color: #4a5568;
            font-size: 0.9rem;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 0.75rem;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .alert-danger {
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
            color: #c53030;
        }

        .login-footer {
            text-align: center;
            padding: 1.5rem 2rem;
            background: #f7fafc;
            border-top: 1px solid #e2e8f0;
        }

        .login-footer p {
            margin: 0;
            color: #718096;
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-header {
                padding: 2rem 1.5rem;
            }

            .login-header h3 {
                font-size: 1.5rem;
            }

            .login-icon {
                width: 70px;
                height: 70px;
            }

            .login-icon i {
                font-size: 2rem;
            }

            .login-body {
                padding: 2rem 1.5rem 1.5rem;
            }

            .login-footer {
                padding: 1.25rem 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="login-page">
    <div class="login-container">
        <div class="login-box">
            
            <!-- Header -->
            <div class="login-header">
                <div class="login-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h3>Sistem Inventaris</h3>
                <p>Silakan login untuk melanjutkan</p>
            </div>
            
            <!-- Body -->
            <div class="login-body">

                <!-- Alert -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif ?>

                <!-- Form -->
                <form method="post" action="<?= base_url('/login') ?>" id="loginForm">
                    <?= csrf_field() ?>

                    <!-- Email -->
                    <div class="form-floating position-relative">
                        <i class="bi bi-envelope input-icon"></i>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-control"
                            placeholder="Email"
                            required
                            autocomplete="email"
                        >
                        <label for="email">Email</label>
                    </div>

                    <!-- Password -->
                    <div class="form-floating position-relative">
                        <i class="bi bi-lock input-icon"></i>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-control"
                            placeholder="Password"
                            required
                            autocomplete="current-password"
                        >
                        <label for="password">Password</label>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="rememberMe"
                        >
                        <label class="form-check-label" for="rememberMe">
                            Ingat saya
                        </label>
                    </div>

                    <!-- Button -->
                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </button>
                </form>

            </div>

            <!-- Footer -->
            <div class="login-footer">
                <p>
                    <i class="bi bi-shield-check me-1"></i>
                    Sistem Manajemen Inventaris © <?= date('Y') ?>
                </p>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const passwordInput = document.getElementById("password");
    const toggleIcon = document.getElementById("toggleIcon");
    
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.remove("bi-eye");
        toggleIcon.classList.add("bi-eye-slash");
    } else {
        passwordInput.type = "password";
        toggleIcon.classList.remove("bi-eye-slash");
        toggleIcon.classList.add("bi-eye");
    }
}

// Auto focus on email input
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('email').focus();
});

// Form validation feedback
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const btn = this.querySelector('.btn-login');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    btn.disabled = true;
});
</script>

</body>
</html>
