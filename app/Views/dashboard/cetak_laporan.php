<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Barang</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }

        .header h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header h3 {
            font-size: 16px;
            font-weight: normal;
            margin-bottom: 10px;
        }

        .info-laporan {
            margin-bottom: 20px;
        }

        .info-laporan table {
            width: 100%;
            max-width: 400px;
        }

        .info-laporan td {
            padding: 3px 0;
        }

        .info-laporan td:first-child {
            width: 150px;
            font-weight: bold;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data th,
        table.data td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        table.data th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        table.data td {
            vertical-align: top;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-menunggu {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-disetujui {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-dikembalikan {
            background-color: #d4edda;
            color: #155724;
        }

        .status-ditolak {
            background-color: #f8d7da;
            color: #721c24;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 5px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }

            @page {
                margin: 1cm;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .print-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<button class="print-button no-print" onclick="window.print()">
    🖨️ Cetak Laporan
</button>

<div class="header">
    <h2>LAPORAN PEMINJAMAN BARANG</h2>
    <h3>Sistem Peminjaman Inventaris</h3>
</div>

<div class="info-laporan">
    <table>
        <tr>
            <td>Tanggal Cetak</td>
            <td>: <?= date('d F Y, H:i') ?> WIB</td>
        </tr>
        <tr>
            <td>Dicetak Oleh</td>
            <td>: <?= esc($namaPetugas) ?></td>
        </tr>
        <?php if ($status): ?>
        <tr>
            <td>Filter Status</td>
            <td>: <?= ucfirst(esc($status)) ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($tglMulai || $tglSelesai): ?>
        <tr>
            <td>Periode</td>
            <td>: 
                <?= $tglMulai ? date('d/m/Y', strtotime($tglMulai)) : '...' ?> 
                s/d 
                <?= $tglSelesai ? date('d/m/Y', strtotime($tglSelesai)) : '...' ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td>Total Data</td>
            <td>: <?= count($dataPinjam) ?> peminjaman</td>
        </tr>
    </table>
</div>

<?php if (empty($dataPinjam)): ?>
    <div class="no-data">
        Tidak ada data peminjaman untuk ditampilkan
    </div>
<?php else: ?>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Tgl Pengajuan</th>
                <th>Tgl Disetujui</th>
                <th>Tgl Kembali</th>
                <th style="width: 100px;">Status</th>
                <th style="width: 100px;">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dataPinjam as $index => $pinjam): ?>
            <tr>
                <td style="text-align: center;"><?= $index + 1 ?></td>
                <td>
                    <strong><?= esc($pinjam['nama'] ?? $pinjam['email']) ?></strong><br>
                    <small><?= esc($pinjam['email']) ?></small>
                </td>
                <td>
                    <strong><?= esc($pinjam['nama_kategori']) ?></strong><br>
                    <?= esc($pinjam['merek_barang']) ?> - <?= esc($pinjam['tipe_barang']) ?><br>
                    <small>Kode: <?= esc($pinjam['kode_barang']) ?></small>
                </td>
                <td style="text-align: center;">
                    <?= $pinjam['tgl_pengajuan'] ? date('d/m/Y', strtotime($pinjam['tgl_pengajuan'])) : '-' ?>
                </td>
                <td style="text-align: center;">
                    <?= $pinjam['tgl_disetujui'] ? date('d/m/Y', strtotime($pinjam['tgl_disetujui'])) : '-' ?>
                </td>
                <td style="text-align: center;">
                    <?= $pinjam['tgl_disetujui_kembali'] ? date('d/m/Y', strtotime($pinjam['tgl_disetujui_kembali'])) : '-' ?>
                </td>
                <td style="text-align: center;">
                    <span class="status-badge status-<?= esc($pinjam['status']) ?>">
                        <?= ucfirst(esc($pinjam['status'])) ?>
                    </span>
                </td>
                <td style="text-align: center;">
                    <?php if ($pinjam['status'] === 'dikembalikan'): ?>
                        <?php if (!empty($pinjam['kondisi_barang'])): ?>
                            <?php if ($pinjam['kondisi_barang'] === 'baik'): ?>
                                <span style="color: #155724; font-weight: bold;">✓ Baik</span>
                            <?php else: ?>
                                <span style="color: #721c24; font-weight: bold;">✗ Rusak</span>
                                <?php if (!empty($pinjam['keterangan_kondisi'])): ?>
                                    <br><small style="color: #856404;"><?= esc($pinjam['keterangan_kondisi']) ?></small>
                                <?php endif ?>
                            <?php endif ?>
                        <?php else: ?>
                            <span style="color: #666;">-</span>
                        <?php endif ?>
                    <?php else: ?>
                        <span style="color: #666;">-</span>
                    <?php endif ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div class="footer">
    <div class="signature">
        <p>Mengetahui,</p>
        <div class="signature-line">
            <strong>Kepala Bagian</strong>
        </div>
    </div>
    <div class="signature">
        <p>Petugas,</p>
        <div class="signature-line">
            <strong><?= esc($namaPetugas) ?></strong>
        </div>
    </div>
</div>

<script>
    // Auto print saat halaman dimuat (opsional)
    // window.onload = function() { window.print(); }
</script>

</body>
</html>
