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

// Data tabel pengaturan_web (cukup 1 baris: sejarah, alamat, email, telepon, sosmed).
// Dipanggil berkali-kali pun query-nya hanya sekali. Kalau tabel kosong -> array kosong.
function ambil_pengaturan($conn) {
    static $cache = null;
    if ($cache === null) {
        $res   = mysqli_query($conn, "SELECT * FROM pengaturan_web ORDER BY id ASC LIMIT 1");
        $cache = ($res ? mysqli_fetch_assoc($res) : null) ?: [];
    }
    return $cache;
}

// Hanya izinkan link http/https (cegah "javascript:" di link sosmed). Selain itu -> ''.
function url_aman($url) {
    $url = trim((string) $url);
    return preg_match('#^https?://#i', $url) ? $url : '';
}
?>
