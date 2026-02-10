<?= view('layouts/header', ['title' => 'Edit Peminjaman']) ?>
<?= view('layouts/navbar') ?>

<div class="main-content">
    <div class="container-fluid px-3 px-md-4">

        <!-- Header -->
        <div class="mb-3">
            <h4 class="fw-bold">Ubah Status Peminjaman</h4>
            <p class="text-muted mb-0">
                Perbarui status peminjaman sesuai kondisi terbaru
            </p>
        </div>

        <!-- Card -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <!-- Info -->
                        <?php if ($pinjam): ?>
                            <div class="mb-3">
                                <div class="row mb-2">
                                    <div class="col-md-4 fw-semibold">Peminjam</div>
                                    <div class="col-md-8">
                                        <?php 
                                            $displayName = !empty($pinjam['nama']) ? $pinjam['nama'] : explode('@', $pinjam['email'])[0];
                                            echo esc($displayName);
                                        ?>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-4 fw-semibold">Barang</div>
                                    <div class="col-md-8">
                                        <?= esc($pinjam['jenis_barang']) ?> -
                                        <?= esc($pinjam['merek_barang']) ?> -
                                        <?= esc($pinjam['tipe_barang']) ?>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-4 fw-semibold">Tanggal Pengajuan</div>
                                    <div class="col-md-8">
                                        <?= date('d-m-Y H:i', strtotime($pinjam['tgl_pengajuan'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <hr>

                        <!-- Form -->
                        <form action="<?= base_url('pinjam/update/'.$pinjam['id_pinjam']) ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Status Peminjaman</label>
                                <select name="status" class="form-select" required>
                                    <option value="menunggu"
                                        <?= $pinjam['status'] === 'menunggu' ? 'selected' : '' ?>>
                                        Menunggu
                                    </option>
                                    <option value="disetujui"
                                        <?= $pinjam['status'] === 'disetujui' ? 'selected' : '' ?>>
                                        Disetujui
                                    </option>
                                    <option value="ditolak"
                                        <?= $pinjam['status'] === 'ditolak' ? 'selected' : '' ?>>
                                        Ditolak
                                    </option>
                                    <?php if ($pinjam['status'] === 'dikembalikan'): ?>
                                        <option value="dikembalikan" selected>
                                            Dikembalikan
                                        </option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="mb-3 d-none" id="alasanBox">
                                <label class="form-label fw-semibold text-danger">
                                    Alasan Penolakan
                                </label>
                                <textarea name="alasan_ditolak"
                                        class="form-control"
                                        rows="3"
                                        placeholder="Masukkan alasan penolakan..."><?= esc($pinjam['alasan_ditolak'] ?? '') ?></textarea>
                            </div>

                            <!-- Action -->
                            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
                                <a href="<?= base_url('/pinjam') ?>" class="btn btn-secondary">
                                    Kembali
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    Simpan Perubahan
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
document.addEventListener('DOMContentLoaded', function () {
    const statusSelect = document.querySelector('select[name="status"]');
    const alasanBox = document.getElementById('alasanBox');
    const textarea = alasanBox.querySelector('textarea');

    function toggleAlasan() {
        if (statusSelect.value === 'ditolak') {
            alasanBox.classList.remove('d-none');
            textarea.setAttribute('required', 'required');
        } else {
            alasanBox.classList.add('d-none');
            textarea.removeAttribute('required');
        }
    }

    toggleAlasan();
    statusSelect.addEventListener('change', toggleAlasan);
});
</script>

<?= view('layouts/footer') ?>