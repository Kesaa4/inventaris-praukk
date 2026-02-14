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
            <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <!-- Alert Error -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>

    <!-- Card -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- Card Foto Profil -->
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="fw-semibold mb-3 text-primary">Foto Profil</h6>
                    
                    <?php if (!empty($profile['foto_profil'])): ?>
                        <img src="<?= base_url('uploads/profile/' . $profile['foto_profil']) ?>" 
                             alt="Foto Profil" 
                             class="img-fluid rounded-circle mb-3"
                             style="width: 200px; height: 200px; object-fit: cover; border: 4px solid #e9ecef;">
                        
                        <div class="d-grid gap-2">
                            <a href="/profile/edit" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil me-1"></i>Ubah Foto
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDeleteFoto()">
                                <i class="bi bi-trash me-1"></i>Hapus Foto
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <i class="bi bi-person-circle text-muted" style="font-size: 150px;"></i>
                        </div>
                        <p class="text-muted small mb-3">Belum ada foto profil</p>
                        <a href="/profile/edit" class="btn btn-sm btn-primary">
                            <i class="bi bi-upload me-1"></i>Upload Foto
                        </a>
                    <?php endif ?>
                </div>
            </div>

            <!-- Card Status -->
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3 text-primary">Status Akun</h6>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Role:</span>
                        <span class="badge bg-info text-dark">
                            <?= esc(ucfirst($profile['role'] ?? session()->get('role'))) ?>
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Status:</span>
                        <span class="badge <?= ($profile['status'] ?? 'aktif') === 'aktif' ? 'bg-success' : 'bg-danger' ?>">
                            <?= esc(ucfirst($profile['status'] ?? 'Aktif')) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Card Informasi -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <!-- Informasi Akun -->
                    <h6 class="fw-semibold mb-3 text-primary">
                        <i class="bi bi-shield-lock me-2"></i>Informasi Akun
                    </h6>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold text-muted">Email</div>
                        <div class="col-md-8">
                            <i class="bi bi-envelope me-2 text-primary"></i>
                            <?= esc($profile['email'] ?? session()->get('email')) ?>
                        </div>
                    </div>

                    <hr>

                    <!-- Informasi Pribadi -->
                    <h6 class="fw-semibold mb-3 text-primary">
                        <i class="bi bi-person-vcard me-2"></i>Data Pribadi
                    </h6>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold text-muted">Nama Lengkap</div>
                        <div class="col-md-8">
                            <i class="bi bi-person me-2 text-primary"></i>
                            <?= esc($profile['nama'] ?? '-') ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold text-muted">No. Handphone</div>
                        <div class="col-md-8">
                            <i class="bi bi-telephone me-2 text-primary"></i>
                            <?= esc($profile['no_hp'] ?? '-') ?>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-semibold text-muted">Alamat</div>
                        <div class="col-md-8">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>
                            <?= esc($profile['alamat'] ?? '-') ?>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                        <a href="/profile/edit" class="btn btn-primary">
                            <i class="bi bi-pencil-square me-1"></i>Edit Profile
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

        </div>
    </div>
</div>

<script>
function confirmDeleteFoto() {
    if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
        window.location.href = '/profile/deleteFoto';
    }
}
</script>

<?= view('layouts/footer') ?>