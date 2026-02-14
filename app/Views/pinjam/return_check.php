<?= view('layouts/header', ['title' => 'Konfirmasi Pengembalian']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">

        <!-- Header -->
        <div class="mb-4 text-center">
            <h4 class="fw-bold">Konfirmasi Pengembalian</h4>
            <p class="text-muted mb-0">
                Pastikan kondisi barang sebelum menyetujui pengembalian
            </p>
        </div>

        <!-- Alert Error -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif ?>

        <!-- Card -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <!-- Info -->
                        <div class="mb-3">
                            <div class="row mb-2">
                                <div class="col-md-5 fw-semibold">Peminjam</div>
                                <div class="col-md-7">
                                    <?= !empty($pinjam['nama']) ? esc($pinjam['nama']) : esc(explode('@', $pinjam['email'])[0]) ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 fw-semibold">Barang</div>
                                <div class="col-md-7">
                                    <?= esc($pinjam['nama_kategori']) ?> -
                                    <?= esc($pinjam['merek_barang']) ?> -
                                    <?= esc($pinjam['tipe_barang']) ?>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-5 fw-semibold">Tgl Pengajuan Return</div>
                                <div class="col-md-7">
                                    <?= date('d-m-Y H:i', strtotime($pinjam['tgl_pengajuan_kembali'])) ?>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <!-- Form -->
                        <form action="/pinjam/return-update/<?= $pinjam['id_pinjam'] ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="form-check mb-3">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="status"
                                    value="dikembalikan"
                                    id="status_dikembalikan"
                                    required>
                                <label class="form-check-label fw-semibold"
                                    for="status_dikembalikan">
                                    Barang sudah dikembalikan
                                </label>
                            </div>

                            <!-- Kondisi Barang -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kondisi Barang Kembali <span class="text-danger">*</span></label>
                                <select class="form-select" name="kondisi_barang" id="kondisi_barang" required>
                                    <option value="">-- Pilih Kondisi --</option>
                                    <option value="baik">Baik (Normal)</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                            </div>

                            <!-- Keterangan Kerusakan (muncul jika rusak) -->
                            <div class="mb-4" id="keterangan_rusak_wrapper" style="display: none;">
                                <label class="form-label fw-semibold">Keterangan Kerusakan <span class="text-danger">*</span></label>
                                <textarea class="form-control" 
                                    name="keterangan_rusak" 
                                    id="keterangan_rusak"
                                    rows="3" 
                                    placeholder="Jelaskan kondisi kerusakan barang..."></textarea>
                            </div>

                            <!-- Action -->
                            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
                                <a href="/pinjam" class="btn btn-secondary">
                                    ← Kembali
                                </a>

                                <button type="submit" class="btn btn-success">
                                    Simpan Konfirmasi
                                </button>
                            </div>
                        </form>

                        <script>
                            // Toggle keterangan kerusakan
                            document.getElementById('kondisi_barang').addEventListener('change', function() {
                                const keteranganWrapper = document.getElementById('keterangan_rusak_wrapper');
                                const keteranganInput = document.getElementById('keterangan_rusak');
                                
                                if (this.value === 'rusak') {
                                    keteranganWrapper.style.display = 'block';
                                    keteranganInput.required = true;
                                } else {
                                    keteranganWrapper.style.display = 'none';
                                    keteranganInput.required = false;
                                    keteranganInput.value = '';
                                }
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?= view('layouts/footer') ?>