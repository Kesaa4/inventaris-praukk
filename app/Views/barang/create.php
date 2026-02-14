<?= view('layouts/header', ['title' => 'Tambah Barang']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">

        <!-- Header -->
        <div class="mb-4 text-center">
            <h3 class="fw-bold">Tambah Data Barang</h3>
            <p class="text-muted mb-0">Lengkapi form untuk menambahkan barang baru</p>
        </div>

    <!-- Form Card -->
    <div class="card shadow-sm">
        <div class="card-body">

            <form action="<?= base_url('/barang/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row g-3">

                    <!-- Kategori -->
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select name="id_kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategori as $k): ?>
                                <option value="<?= esc($k['id_kategori']) ?>">
                                    <?= esc($k['nama_kategori']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <!-- Merek -->
                    <div class="col-md-6">
                        <label class="form-label">Merek Barang</label>
                        <input type="text" name="merek_barang" class="form-control" required>
                    </div>

                    <!-- Tipe -->
                    <div class="col-md-6">
                        <label class="form-label">Tipe Barang</label>
                        <input type="text" name="tipe_barang" class="form-control" required>
                    </div>

                    <!-- Kode -->
                    <div class="col-md-6">
                        <label class="form-label">Kode Barang</label>
                        <input type="text" name="kode_barang" class="form-control" required>
                    </div>

                    <!-- RAM -->
                    <div class="col-md-3">
                        <label class="form-label">RAM</label>
                        <input type="text" name="ram" class="form-control" placeholder="contoh: 8 GB" required>
                    </div>

                    <!-- ROM -->
                    <div class="col-md-3">
                        <label class="form-label">ROM</label>
                        <input type="text" name="rom" class="form-control" placeholder="contoh: 128 GB" required>
                    </div>

                    <!-- Kondisi -->
                    <div class="col-md-6">
                        <label class="form-label">Kondisi Barang</label>
                        <select name="kondisi" class="form-select" id="kondisiSelect" required>
                            <option value="">-- Pilih Kondisi --</option>
                            <?php foreach ($kondisi_list as $kondisi): ?>
                                <option value="<?= esc($kondisi) ?>">
                                    <?= esc($kondisi) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label d-block">Status Barang</label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_tersedia" value="tersedia" required>
                            <label class="form-check-label" for="status_tersedia">
                                <span class="badge bg-success">Tersedia</span>
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_tidak" value="tidak tersedia">
                            <label class="form-check-label" for="status_tidak">
                                <span class="badge bg-secondary">Tidak Tersedia</span>
                            </label>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
                    </div>

                    <!-- Foto Barang -->
                    <div class="col-12">
                        <label class="form-label">Foto Barang</label>
                        <input type="file" 
                               name="foto" 
                               class="form-control" 
                               accept="image/jpeg,image/jpg,image/png,image/gif"
                               id="fotoInput">
                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                        
                        <!-- Preview -->
                        <div id="fotoPreview" class="mt-3" style="display: none;">
                            <label class="form-label">Preview:</label>
                            <div>
                                <img id="previewImage" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Action -->
                <div class="mt-4 d-flex flex-column flex-sm-row gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Tambah
                    </button>
                    <a href="<?= base_url('barang') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

    </div>
</div>

<?= view('layouts/footer') ?>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const kondisiSelect = document.getElementById('kondisiSelect');
    const statusTersedia = document.getElementById('status_tersedia');
    const statusTidak = document.getElementById('status_tidak');
    
    // Auto set status based on kondisi
    kondisiSelect.addEventListener('change', function() {
        if (this.value === 'Rusak Berat') {
            statusTidak.checked = true;
        } else if (this.value === 'Baik' || this.value === 'Rusak Ringan') {
            statusTersedia.checked = true;
        }
    });
    
    // Auto set kondisi based on status
    statusTidak.addEventListener('change', function() {
        if (this.checked) {
            kondisiSelect.value = 'Rusak Berat';
        }
    });
    
    statusTersedia.addEventListener('change', function() {
        if (this.checked && kondisiSelect.value === 'Rusak Berat') {
            kondisiSelect.value = '';
        }
    });
});

// Preview image before upload
document.getElementById('fotoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (file.size > 2048000) {
            alert('Ukuran file maksimal 2MB');
            this.value = '';
            document.getElementById('fotoPreview').style.display = 'none';
            return;
        }
        
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file harus JPG, PNG, atau GIF');
            this.value = '';
            document.getElementById('fotoPreview').style.display = 'none';
            return;
        }
        
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
