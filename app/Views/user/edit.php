<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Edit User</h5>
                </div>

                <div class="card-body">
                    <form method="post" action="/user/update/<?= $user['id_user'] ?>">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                value="<?= esc($profile['nama'] ?? '') ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No Handphone</label>
                            <input
                                type="text"
                                name="no_hp"
                                class="form-control"
                                value="<?= esc($profile['no_hp'] ?? '') ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea
                                name="alamat"
                                class="form-control"
                                rows="3"
                            ><?= esc($profile['alamat'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>
                                    Admin
                                </option>
                                <option value="petugas" <?= $user['role'] === 'petugas' ? 'selected' : '' ?>>
                                    Petugas
                                </option>
                                <option value="peminjam" <?= $user['role'] === 'peminjam' ? 'selected' : '' ?>>
                                    Peminjam
                                </option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/user" class="btn btn-secondary">
                                ← Kembali
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
