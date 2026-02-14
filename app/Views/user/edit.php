<?= view('layouts/header', ['title' => 'Edit User']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <!-- Card Form -->
    <div class="container-fluid px-3 px-md-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark text-center">
                        <h5 class="mb-0">Edit User</h5>
                        <p class="mb-0 small">Ubah data pengguna</p>
                    </div>

                    <div class="card-body">

                        <!-- Error validation -->
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger">
                                <strong><i class="bi bi-exclamation-triangle me-2"></i>Terjadi Kesalahan:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        <?php endif ?>

                        <!-- Error message -->
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif ?>

                        <form method="post" action="/user/update/<?= $user['id_user'] ?>">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" 
                                       name="nama" 
                                       class="form-control" 
                                       value="<?= esc($user['nama'] ?? '') ?>"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control" 
                                       value="<?= esc($user['email']) ?>"
                                       disabled>
                                <small class="text-muted">Email tidak dapat diubah</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control"
                                       placeholder="Kosongkan jika tidak ingin mengubah password">
                                <small class="text-muted">Minimal 6 karakter</small>
                            </div>

                            <!-- Show Password -->
                            <div class="form-check mb-3">
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

                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="petugas" <?= $user['role'] === 'petugas' ? 'selected' : '' ?>>Petugas</option>
                                    <option value="peminjam" <?= $user['role'] === 'peminjam' ? 'selected' : '' ?>>Peminjam</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="aktif" <?= $user['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="tidak aktif" <?= $user['status'] === 'tidak aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                                </select>
                            </div>

                            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
                                <a href="/user" class="btn btn-secondary">
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-success">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

<?= view('layouts/footer') ?>