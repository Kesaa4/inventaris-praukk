<?= view('layouts/header', ['title' => 'Kategori Barang']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">
        <div class="content-wrapper fade-in">

            <!-- Header -->
            <div class="page-header">
                <h3><i class="bi bi-tags me-2"></i><?= $title ?></h3>
                <p class="text-muted">Pilih kategori untuk melihat barang</p>
            </div>

            <!-- Card -->
            <div class="row">
                <?php foreach($kategori_list as $k): ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column justify-content-between text-center">
                                <div>
                                    <i class="bi bi-tag-fill" style="font-size: 3rem; color: var(--primary);"></i>
                                    <h5 class="card-title mt-3"><?= esc($k['kategori_kondisi']) ?></h5>
                                </div>
                                <a href="<?= site_url('kategori/'.$k['id_kategori']) ?>" class="btn btn-primary mt-3">
                                    <i class="bi bi-eye me-1"></i>Lihat Barang
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?= view('layouts/footer') ?>