<?= view('layouts/header', ['title' => 'Kategori Barang']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">
        <div class="content-wrapper fade-in">

            <!-- Header -->
            <div class="page-header">
                <h3><i class="bi bi-tags me-2"></i><?= $title ?></h3>
                <p class="text-muted">Pilih kategori untuk melihat daftar barang berdasarkan kondisi</p>
            </div>

            <!-- Card -->
            <div class="row">
                <?php if (!empty($kategori_list)): ?>
                    <?php foreach($kategori_list as $k): ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="card h-100 shadow-sm hover-card">
                                <div class="card-body d-flex flex-column justify-content-between text-center">
                                    <div>
                                        <i class="bi bi-tag-fill" style="font-size: 3rem; color: var(--primary);"></i>
                                        <h5 class="card-title mt-3 mb-2"><?= esc($k['kategori_kondisi']) ?></h5>
                                        <p class="text-muted small mb-0">
                                            <?= $k['jumlah_barang'] ?? 0 ?> Barang
                                        </p>
                                    </div>
                                    <a href="<?= site_url('kategori/'.$k['id_kategori']) ?>" class="btn btn-primary mt-3">
                                        <i class="bi bi-eye me-1"></i>Lihat Barang
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-2"></i>
                            Belum ada kategori tersedia
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= view('layouts/footer') ?>