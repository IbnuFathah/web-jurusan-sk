<?php
// helpers.php — fungsi kecil yang dipakai halaman publik.

// Amankan teks sebelum dicetak ke HTML (cegah XSS).
function e($teks) {
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

function format_jumlah_fasilitas($jumlah) {
    $jumlah = trim((string) $jumlah);
    if ($jumlah === '') {
        return '0';
    }

    return ctype_digit($jumlah) ? $jumlah . ' Unit' : $jumlah;
}

// 2026-08-01  ->  1 Agustus 2026
function tanggal_indo($tanggal) {
    if (!$tanggal) return '';
    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
              'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $t = strtotime($tanggal);
    if (!$t) return e($tanggal);
    return date('j', $t) . ' ' . $bulan[(int) date('n', $t)] . ' ' . date('Y', $t);
}

// Isi berita dari <textarea>: baris kosong = paragraf baru.
function paragraf($teks) {
    $hasil = '';
    foreach (preg_split('/\R{2,}/', trim((string) $teks)) as $p) {
        $p = trim($p);
        if ($p !== '') {
            $hasil .= '<p>' . nl2br(e($p)) . "</p>\n";
        }
    }
    return $hasil;
}
?>
