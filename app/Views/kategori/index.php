<?= view('layouts/header', ['title' => 'Kategori Barang']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">
        <div class="content-wrapper fade-in">

            <!-- Header -->
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h3><?= $title ?></h3>
                    <p class="text-muted mb-0">Kelola kategori barang inventaris</p>
                </div>
                <?php if (session()->get('role') === 'admin'): ?>
                <div>
                    <a href="<?= site_url('kategori/create') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Kategori
                    </a>
                    <a href="<?= site_url('kategori/export') ?>" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel me-1"></i>Export
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Alert Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Search Bar -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="<?= site_url('kategori') ?>" method="get" class="row g-2 align-items-end" id="filterForm">
                        <div class="col-md-10">
                            <label class="form-label">Cari Kategori</label>
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Cari kategori..." 
                                   value="<?= esc($keyword ?? '') ?>"
                                   id="searchInput">
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <a href="<?= site_url('kategori') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('filterForm');
                const searchInput = document.getElementById('searchInput');
                
                let timeout = null;
                
                searchInput.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(function() {
                        form.submit();
                    }, 500);
                });
            });
            </script>

            <!-- Card Grid -->
            <div class="row">
                <?php if (!empty($kategori_list)): ?>
                    <?php foreach($kategori_list as $k): ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="card h-100 shadow-sm hover-card">
                                <div class="card-body d-flex flex-column">
                                    <div class="text-center mb-3">
                                        <i class="<?= esc($k['icon'] ?? 'bi-tag-fill') ?>" 
                                           style="font-size: 3rem; color: var(--primary);"></i>
                                        <h5 class="card-title mt-3 mb-2"><?= esc($k['nama_kategori']) ?></h5>
                                        <?php if (!empty($k['deskripsi'])): ?>
                                            <p class="text-muted small mb-2"><?= esc($k['deskripsi']) ?></p>
                                        <?php endif; ?>
                                        
                                        <!-- Total Barang -->
                                        <p class="text-muted small mb-2">
                                            <i class="bi bi-box me-1"></i><strong><?= $k['jumlah_barang'] ?? 0 ?></strong> Barang
                                        </p>
                                        
                                        <!-- Kondisi Barang -->
                                        <div class="d-flex justify-content-center gap-2 flex-wrap small">
                                            <span class="badge bg-success" title="Baik">
                                                <i class="bi bi-check-circle"></i> <?= $k['baik'] ?? 0 ?>
                                            </span>
                                            <span class="badge bg-warning" title="Rusak Ringan">
                                                <i class="bi bi-exclamation-triangle"></i> <?= $k['rusak_ringan'] ?? 0 ?>
                                            </span>
                                            <span class="badge bg-danger" title="Rusak Berat">
                                                <i class="bi bi-x-circle"></i> <?= $k['rusak_berat'] ?? 0 ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-auto">
                                        <a href="<?= site_url('kategori/'.$k['id_kategori']) ?>" 
                                           class="btn btn-primary w-100 mb-2">
                                            <i class="bi bi-eye me-1"></i>Lihat Barang
                                        </a>
                                        
                                        <?php if (session()->get('role') === 'admin'): ?>
                                        <div class="btn-group w-100" role="group">
                                            <a href="<?= site_url('kategori/edit/'.$k['id_kategori']) ?>" 
                                               class="btn btn-sm btn-warning"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="confirmDelete(<?= $k['id_kategori'] ?>, '<?= esc($k['nama_kategori']) ?>')"
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-2"></i>
                            <?= $keyword ? 'Tidak ada kategori yang sesuai dengan pencarian' : 'Belum ada kategori tersedia' ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form -->
<form id="deleteForm" method="post" style="display: none;">
    <?= csrf_field() ?>
</form>

<script>
function confirmDelete(id, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus kategori "' + nama + '"?\n\nPerhatian: Kategori yang memiliki barang tidak dapat dihapus.')) {
        const form = document.getElementById('deleteForm');
        form.action = '<?= site_url('kategori/delete/') ?>' + id;
        form.submit();
    }
}
</script>

<?= view('layouts/footer') ?>