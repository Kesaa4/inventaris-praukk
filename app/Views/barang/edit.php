<?= view('layouts/header', ['title' => 'Edit Barang']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">

        <!-- Header -->
        <div class="mb-4 text-center">
            <h3 class="fw-bold">Edit Data Barang</h3>
            <p class="text-muted mb-0">Perbarui informasi barang</p>
        </div>

    <!-- Form Card -->
    <div class="card shadow-sm">
        <div class="card-body">

            <form action="<?= base_url('/barang/update/' . $barang['id_barang']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row g-3">

                    <!-- Jenis Barang -->
                    <div class="col-md-6">
                        <label class="form-label">Jenis Barang</label>
                        <select name="jenis_barang" class="form-select" required>
                            <option value="">-- Pilih Jenis Barang --</option>
                            <?php foreach ($jenis_barang as $jenis): ?>
                                <option value="<?= esc($jenis) ?>"
                                    <?= ($barang['jenis_barang'] === $jenis) ? 'selected' : '' ?>>
                                    <?= esc($jenis) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <!-- Merek -->
                    <div class="col-md-6">
                        <label class="form-label">Merek Barang</label>
                        <input
                            type="text"
                            name="merek_barang"
                            class="form-control"
                            value="<?= esc($barang['merek_barang']) ?>"
                            required
                        >
                    </div>

                    <!-- Tipe -->
                    <div class="col-md-6">
                        <label class="form-label">Tipe Barang</label>
                        <input
                            type="text"
                            name="tipe_barang"
                            class="form-control"
                            value="<?= esc($barang['tipe_barang']) ?>"
                            required
                        >
                    </div>

                    <!-- Kode -->
                    <div class="col-md-6">
                        <label class="form-label">Kode Barang</label>
                        <input
                            type="text"
                            name="kode_barang"
                            class="form-control"
                            value="<?= esc($barang['kode_barang']) ?>"
                            required
                        >
                    </div>

                    <!-- RAM -->
                    <div class="col-md-3">
                        <label class="form-label">RAM</label>
                        <input
                            type="text"
                            name="ram"
                            class="form-control"
                            value="<?= esc($barang['ram']) ?>"
                        >
                    </div>

                    <!-- ROM -->
                    <div class="col-md-3">
                        <label class="form-label">ROM</label>
                        <input
                            type="text"
                            name="rom"
                            class="form-control"
                            value="<?= esc($barang['rom']) ?>"
                        >
                    </div>

                    <!-- Kategori -->
                    <div class="col-md-6">
                        <label class="form-label">Kategori Kondisi</label>
                        <select name="id_kategori" class="form-select" required>
                            <?php foreach ($kategori as $k): ?>
                                <option
                                    value="<?= $k['id_kategori'] ?>"
                                    <?= $barang['id_kategori'] == $k['id_kategori'] ? 'selected' : '' ?>>
                                    <?= esc($k['kategori_kondisi']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    
                    <?php
                        // normalisasi biar aman
                        $status = strtolower(trim($barang['status']));
                    ?>
                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label d-block">Status Barang</label>

                        <?php
                            $status = strtolower($barang['status']);
                        ?>

                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="status"
                                id="status_tersedia"
                                value="tersedia"
                                <?= $status === 'tersedia' ? 'checked' : '' ?>
                            >
                            <label class="form-check-label" for="status_tersedia">
                                <span class="badge bg-success">Tersedia</span>
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="status"
                                id="status_tidak"
                                value="tidak tersedia"
                                <?= $status === 'tidak tersedia' ? 'checked' : '' ?>
                            >
                            <label class="form-check-label" for="status_tidak">
                                <span class="badge bg-secondary">Tidak Tersedia</span>
                            </label>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <input
                            type="text"
                            name="keterangan"
                            class="form-control"
                            value="<?= esc($barang['keterangan'] ?? '') ?>"
                        >
                    </div>

                    <!-- Foto Barang -->
                    <div class="col-12">
                        <label class="form-label">Foto Barang</label>
                        
                        <?php helper('upload'); ?>
                        
                        <!-- Current Photo -->
                        <?php if (!empty($barang['foto'])): ?>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Foto Saat Ini:</label>
                                <div>
                                    <img src="<?= getFotoBarang($barang['foto']) ?>" 
                                         alt="Current" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                </div>
                                <div class="mt-2">
                                    <a href="<?= base_url('barang/delete-foto/' . $barang['id_barang']) ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin hapus foto ini?')">
                                        <i class="bi bi-trash"></i> Hapus Foto
                                    </a>
                                </div>
                            </div>
                        <?php endif ?>
                        
                        <!-- Upload New -->
                        <input type="file" 
                               name="foto" 
                               class="form-control" 
                               accept="image/jpeg,image/jpg,image/png,image/gif"
                               id="fotoInput">
                        <small class="text-muted">
                            <?= !empty($barang['foto']) ? 'Upload foto baru untuk mengganti' : 'Format: JPG, PNG, GIF. Maksimal 2MB' ?>
                        </small>
                        
                        <!-- Preview -->
                        <div id="fotoPreview" class="mt-3" style="display: none;">
                            <label class="form-label small text-muted">Preview Foto Baru:</label>
                            <div>
                                <img id="previewImage" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Action -->
                <div class="mt-4 d-flex flex-column flex-sm-row gap-2">
                    <button type="submit" class="btn btn-warning">
                        Update
                    </button>
                    <a href="<?= base_url('barang') ?>" class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

    </div>
</div>

<script>
// Preview image before upload
document.getElementById('fotoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validate size (2MB = 2048000 bytes)
        if (file.size > 2048000) {
            alert('Ukuran file maksimal 2MB');
            this.value = '';
            document.getElementById('fotoPreview').style.display = 'none';
            return;
        }
        
        // Validate type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file harus JPG, PNG, atau GIF');
            this.value = '';
            document.getElementById('fotoPreview').style.display = 'none';
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('fotoPreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        document.getElementById('fotoPreview').style.display = 'none';
    }
});
</script>

<?= view('layouts/footer') ?>
