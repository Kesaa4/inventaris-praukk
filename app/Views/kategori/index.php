<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Barang</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- JS 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-4"><?= $title ?></h3>
        <a href="/dashboard" class="btn btn-secondary btn-sm">Kembali Ke Dashboard</a>
    </div>

    <!-- Card -->
    <div class="row">
        <?php foreach($kategori_list as $k): ?>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title"><?= esc($k['kategori_kondisi']) ?></h5>
                        <a href="<?= site_url('kategori/'.$k['id_kategori']) ?>" class="btn btn-primary mt-3">
                            Lihat Barang
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>    

</body>
</html>