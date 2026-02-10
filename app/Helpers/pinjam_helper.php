<?php

/**
 * Helper untuk cek status keterlambatan peminjaman
 */
function isLate($tglJatuhTempo, $status, $tglDikembalikan = null)
{
    // Jika sudah dikembalikan, cek apakah terlambat saat dikembalikan
    if ($status === 'dikembalikan' && $tglDikembalikan) {
        return strtotime($tglDikembalikan) > strtotime($tglJatuhTempo);
    }
    
    // Jika belum dikembalikan dan status disetujui, cek dengan tanggal sekarang
    if ($status === 'disetujui' && $tglJatuhTempo) {
        return strtotime('now') > strtotime($tglJatuhTempo);
    }
    
    return false;
}

/**
 * Helper untuk hitung hari keterlambatan
 */
function hitungHariTerlambat($tglJatuhTempo, $status, $tglDikembalikan = null)
{
    if (!isLate($tglJatuhTempo, $status, $tglDikembalikan)) {
        return 0;
    }
    
    $tglBatas = strtotime($tglJatuhTempo);
    
    if ($status === 'dikembalikan' && $tglDikembalikan) {
        $tglAkhir = strtotime($tglDikembalikan);
    } else {
        $tglAkhir = strtotime('now');
    }
    
    $selisih = $tglAkhir - $tglBatas;
    return ceil($selisih / (60 * 60 * 24)); // Convert ke hari
}

/**
 * Helper untuk format sisa waktu
 */
function sisaWaktu($tglJatuhTempo, $status)
{
    if ($status !== 'disetujui' || !$tglJatuhTempo) {
        return '-';
    }
    
    $now = strtotime('now');
    $deadline = strtotime($tglJatuhTempo);
    $selisih = $deadline - $now;
    
    if ($selisih < 0) {
        return 'Terlambat ' . abs(ceil($selisih / (60 * 60 * 24))) . ' hari';
    }
    
    $hari = ceil($selisih / (60 * 60 * 24));
    
    if ($hari == 0) {
        return 'Hari ini';
    } elseif ($hari == 1) {
        return 'Besok';
    } else {
        return $hari . ' hari lagi';
    }
}
