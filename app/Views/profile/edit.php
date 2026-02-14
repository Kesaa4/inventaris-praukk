<?= view('layouts/header', ['title' => 'Edit Profile']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">

        <!-- Header -->
        <div class="page-header">
            <h4><i class="bi bi-pencil-square me-2"></i>Edit Profile</h4>
            <p class="text-muted">Perbarui data pribadi dan foto profil Anda</p>
        </div>

        <!-- Card -->
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm">
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

                        <form method="post" action="/profile/update" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <!-- Section Foto Profil -->
                            <div class="mb-4">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-image me-2"></i>Foto Profil
                                </h6>
                                <p class="text-muted small mb-3">
                                    Upload foto profil Anda (Maks. 2MB, Format: JPG, JPEG, PNG)
                                </p>

                                <div class="text-center mb-3">
                                    <?php if (!empty($profile['foto_profil'])): ?>
                                        <img src="<?= base_url('uploads/profile/' . $profile['foto_profil']) ?>" 
                                             alt="Foto Profil" 
                                             id="preview"
                                             class="img-fluid rounded-circle mb-2"
                                             style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #e9ecef;">
                                    <?php else: ?>
                                        <i class="bi bi-person-circle text-muted d-block mb-2" style="font-size: 100px;" id="iconPreview"></i>
                                        <img src="" 
                                             alt="Preview" 
                                             id="preview"
                                             class="img-fluid rounded-circle mb-2 d-none"
                                             style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #e9ecef;">
                                    <?php endif ?>
                                </div>

                                <div class="mb-3">
                                    <input type="file" 
                                           name="foto_profil" 
                                           id="foto_profil"
                                           class="form-control"
                                           accept="image/jpeg,image/jpg,image/png"
                                           onchange="previewImage(this)">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Section Data Pribadi -->
                            <div class="mb-4">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-person-vcard me-2"></i>Data Pribadi
                                </h6>
                            </div>

                            <!-- Nama -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="nama"
                                    class="form-control"
                                    value="<?= esc(old('nama', $profile['nama'] ?? '')) ?>"
                                    placeholder="Masukkan nama lengkap"
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
                                    value="<?= esc(old('no_hp', $profile['no_hp'] ?? '')) ?>"
                                    placeholder="Contoh: 081234567890"
                                    pattern="[0-9]{10,15}"
                                >
                                <small class="text-muted">10-15 digit angka</small>
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
                                    placeholder="Masukkan alamat lengkap"
                                ><?= esc(old('alamat', $profile['alamat'] ?? '')) ?></textarea>
                            </div>

                            <hr class="my-4">

                            <!-- Section Password -->
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-lock-fill me-2"></i>Ubah Password
                                </h6>
                                <p class="text-muted small mb-3">
                                    Kosongkan semua field password jika tidak ingin mengubah password
                                </p>
                            </div>

                            <!-- Password Lama -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Password Lama
                                </label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        name="password_lama"
                                        class="form-control"
                                        placeholder="Masukkan password lama"
                                        id="password_lama"
                                    >
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="togglePassword('password_lama')">
                                        <i class="bi bi-eye" id="icon_password_lama"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Password Baru -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Password Baru
                                </label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        name="password_baru"
                                        class="form-control"
                                        placeholder="Masukkan password baru"
                                        id="password_baru"
                                        minlength="6"
                                    >
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="togglePassword('password_baru')">
                                        <i class="bi bi-eye" id="icon_password_baru"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Minimal 6 karakter</small>
                            </div>

                            <!-- Konfirmasi Password Baru -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Konfirmasi Password Baru
                                </label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        name="password_konfirmasi"
                                        class="form-control"
                                        placeholder="Ulangi password baru"
                                        id="password_konfirmasi"
                                    >
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="togglePassword('password_konfirmasi')">
                                        <i class="bi bi-eye" id="icon_password_konfirmasi"></i>
                                    </button>
                                </div>
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

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById('icon_' + fieldId);
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

function previewImage(input) {
    const preview = document.getElementById('preview');
    const iconPreview = document.getElementById('iconPreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (iconPreview) {
                iconPreview.classList.add('d-none');
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?= view('layouts/footer') ?>