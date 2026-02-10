<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peminjaman - <?= esc($pinjam['kode_barang']) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 30px;
            background: #f8f9fa;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0d6efd;
        }

        .header h1 {
            color: #0d6efd;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            color: #6c757d;
            font-size: 14px;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-section h2 {
            color: #495057;
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-item {
            padding: 12px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .info-value {
            color: #212529;
            font-size: 15px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-menunggu { background: #6c757d; color: white; }
        .status-disetujui { background: #0d6efd; color: white; }
        .status-pengembalian { background: #ffc107; color: #000; }
        .status-dikembalikan { background: #198754; color: white; }
        .status-ditolak { background: #dc3545; color: white; }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }

        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-print {
            background: #0d6efd;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
        }

        .btn-print:hover {
            background: #0b5ed7;
        }

        .btn-back {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }

        .btn-back:hover {
            background: #5a6268;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                padding: 20px;
            }

            .print-button {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="print-button">
        <button class="btn-print" onclick="window.print()">
            🖨️ Cetak Laporan
        </button>
        <a href="<?= site_url('pinjam') ?>" class="btn-back">
            ← Kembali
        </a>
    </div>

    <div class="container">
        <div class="header">
            <h1>DETAIL PEMINJAMAN BARANG</h1>
            <p>Sistem Informasi Peminjaman Barang</p>
            <p style="margin-top: 10px; font-size: 12px;">
                Dicetak pada: <?= date('d F Y, H:i') ?> WIB
            </p>
        </div>

        <!-- Informasi Barang -->
        <div class="info-section">
            <h2>📦 Informasi Barang</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Kode Barang</div>
                    <div class="info-value"><?= esc($pinjam['kode_barang']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Jenis Barang</div>
                    <div class="info-value"><?= esc($pinjam['jenis_barang']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Merek</div>
                    <div class="info-value"><?= esc($pinjam['merek_barang']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tipe</div>
                    <div class="info-value"><?= esc($pinjam['tipe_barang']) ?></div>
                </div>
            </div>
        </div>

        <!-- Informasi Peminjam -->
        <div class="info-section">
            <h2>👤 Informasi Peminjam</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nama Peminjam</div>
                    <div class="info-value">
                        <?php 
                            $displayName = !empty($pinjam['nama']) ? $pinjam['nama'] : explode('@', $pinjam['email'])[0];
                            echo esc($displayName);
                        ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?= esc($pinjam['email']) ?></div>
                </div>
            </div>
        </div>

        <!-- Timeline Peminjaman -->
        <div class="info-section">
            <h2>📅 Timeline Peminjaman</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Tanggal Pengajuan</div>
                    <div class="info-value">
                        <?= $pinjam['tgl_pengajuan'] 
                            ? date('d F Y, H:i', strtotime($pinjam['tgl_pengajuan'])) . ' WIB'
                            : '-' ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Disetujui</div>
                    <div class="info-value">
                        <?= $pinjam['tgl_disetujui'] 
                            ? date('d F Y, H:i', strtotime($pinjam['tgl_disetujui'])) . ' WIB'
                            : '-' ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Pengajuan Pengembalian</div>
                    <div class="info-value">
                        <?= $pinjam['tgl_pengajuan_kembali'] 
                            ? date('d F Y, H:i', strtotime($pinjam['tgl_pengajuan_kembali'])) . ' WIB'
                            : '-' ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Pengembalian Disetujui</div>
                    <div class="info-value">
                        <?= $pinjam['tgl_disetujui_kembali'] 
                            ? date('d F Y, H:i', strtotime($pinjam['tgl_disetujui_kembali'])) . ' WIB'
                            : '-' ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="info-section">
            <h2>📊 Status Peminjaman</h2>
            <div class="info-grid">
                <div class="info-item full-width">
                    <div class="info-label">Status Saat Ini</div>
                    <div class="info-value">
                        <?php
                            $status = strtolower(trim($pinjam['status']));
                            $statusClass = match ($status) {
                                'disetujui'     => 'status-disetujui',
                                'pengembalian'  => 'status-pengembalian',
                                'dikembalikan'  => 'status-dikembalikan',
                                'ditolak'       => 'status-ditolak',
                                default         => 'status-menunggu'
                            };
                        ?>
                        <span class="status-badge <?= $statusClass ?>">
                            <?= ucfirst($pinjam['status']) ?>
                        </span>
                    </div>
                </div>

                <?php if ($status === 'ditolak' && !empty($pinjam['alasan_ditolak'])): ?>
                <div class="info-item full-width">
                    <div class="info-label">Alasan Ditolak</div>
                    <div class="info-value" style="color: #dc3545;">
                        <?= esc($pinjam['alasan_ditolak']) ?>
                    </div>
                </div>
                <?php endif ?>
            </div>
        </div>

        <!-- Kondisi Pengembalian -->
        <?php if ($status === 'dikembalikan'): ?>
        <div class="info-section">
            <h2>🔍 Kondisi Pengembalian</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Kondisi Barang</div>
                    <div class="info-value">
                        <?php if (!empty($pinjam['kondisi_barang'])): ?>
                            <?php if ($pinjam['kondisi_barang'] === 'baik'): ?>
                                <span class="status-badge" style="background: #198754; color: white;">
                                    ✓ Baik (Normal)
                                </span>
                            <?php else: ?>
                                <span class="status-badge" style="background: #dc3545; color: white;">
                                    ✗ Rusak
                                </span>
                            <?php endif ?>
                        <?php else: ?>
                            <span style="color: #6c757d;">Data tidak tersedia</span>
                        <?php endif ?>
                    </div>
                </div>

                <?php if (!empty($pinjam['keterangan_kondisi'])): ?>
                <div class="info-item full-width">
                    <div class="info-label">Keterangan Kerusakan</div>
                    <div class="info-value" style="color: #dc3545; background: #fff3cd; padding: 10px; border-radius: 5px; border-left: 4px solid #dc3545;">
                        <?= nl2br(esc($pinjam['keterangan_kondisi'])) ?>
                    </div>
                </div>
                <?php endif ?>
            </div>
        </div>
        <?php endif ?>

        <div class="footer">
            <p><strong>Sistem Informasi Peminjaman Barang</strong></p>
            <p>Dokumen ini dicetak secara otomatis oleh sistem</p>
        </div>
    </div>

</body>
</html>
