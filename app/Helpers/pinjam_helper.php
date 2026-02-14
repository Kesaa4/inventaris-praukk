<?php

if (!function_exists('isLate')) {
    function isLate($tglJatuhTempo, $status, $tglDikembalikan = null)
    {
        if (!$tglJatuhTempo) {
            return false;
        }

        if ($status === 'dikembalikan' && $tglDikembalikan) {
            return strtotime($tglDikembalikan) > strtotime($tglJatuhTempo);
        }
        
        if ($status === 'disetujui') {
            return strtotime('now') > strtotime($tglJatuhTempo);
        }
        
        return false;
    }
}

if (!function_exists('hitungHariTerlambat')) {
    function hitungHariTerlambat($tglJatuhTempo, $status, $tglDikembalikan = null)
    {
        if (!isLate($tglJatuhTempo, $status, $tglDikembalikan)) {
            return 0;
        }
        
        $tglBatas = strtotime($tglJatuhTempo);
        $tglAkhir = ($status === 'dikembalikan' && $tglDikembalikan) 
            ? strtotime($tglDikembalikan) 
            : strtotime('now');
        
        $selisih = $tglAkhir - $tglBatas;
        return ceil($selisih / (60 * 60 * 24));
    }
}

if (!function_exists('sisaWaktu')) {
    function sisaWaktu($tglJatuhTempo, $status)
    {
        if ($status !== 'disetujui' || !$tglJatuhTempo) {
            return '-';
        }
        
        $now = strtotime('now');
        $deadline = strtotime($tglJatuhTempo);
        $selisih = $deadline - $now;
        
        if ($selisih < 0) {
            $hari = abs(ceil($selisih / (60 * 60 * 24)));
            return 'Terlambat ' . $hari . ' hari';
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
}
