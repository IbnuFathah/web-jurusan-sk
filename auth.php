<?php
// auth.php — sesi login & penjaga halaman.
// Panggil di baris paling atas halaman (sebelum ada output HTML apa pun).

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Data user yang sedang login, atau null kalau guest.
// Isi: ['id' => .., 'nama' => .., 'email' => .., 'role' => 'admin'|'user']
function user_login() {
    return $_SESSION['user'] ?? null;
}

function is_admin() {
    $u = user_login();
    return $u !== null && $u['role'] === 'admin';
}

// Taruh di paling atas SETIAP halaman admin.
//   $root = path dari halaman itu ke folder utama website (contoh '../../')
//   - guest            -> dialihkan ke login.php
//   - user biasa       -> ditolak (403)
//   - admin            -> lolos
function wajib_admin($root) {
    if (is_admin()) {
        return;
    }
    if (user_login() === null) {
        header('Location: ' . $root . 'login.php');
        exit;
    }
    http_response_code(403);
    echo '<!DOCTYPE html><meta charset="UTF-8"><title>Akses ditolak</title>'
       . '<body style="font-family:sans-serif;text-align:center;margin-top:80px">'
       . '<h2>Akses ditolak</h2><p>Halaman ini khusus admin.</p>'
       . '<a href="' . $root . 'index.php">Kembali ke beranda</a></body>';
    exit;
}
?>
