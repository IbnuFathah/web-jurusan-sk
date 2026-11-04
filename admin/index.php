<?php
// admin/index.php — Dashboard (halaman awal admin)
require_once __DIR__ . '/../koneksi.php';

function hitung($conn, $tabel) {
    $r = mysqli_query($conn, "SELECT COUNT(*) AS total FROM $tabel");
    $d = mysqli_fetch_assoc($r);
    return (int) $d['total'];
}

$modul = [
    ['Berita',    hitung($conn, 'berita'),    'berita/admin_berita.php'],
    ['Galeri',    hitung($conn, 'galeri'),    'galeri/admin_galeri.php'],
    ['Fasilitas', hitung($conn, 'fasilitas'), 'fasilitas/index.php'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Admin SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background: #f4f6f9; display: flex; }
        .main-content { flex: 1; padding: 30px; }
        h2 { font-size: 24px; margin-bottom: 8px; color: #222; }
        .sub { color: #777; margin-bottom: 25px; font-size: 14px; }
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
        .stat { background: #fff; border-radius: 12px; padding: 22px; box-shadow: 0 2px 8px rgba(0,0,0,.06); text-decoration: none; color: #333; display: block; }
        .stat strong { display: block; font-size: 34px; color: #b79d63; }
        .stat span { font-size: 14px; color: #666; }
    </style>
</head>
<body>
<?php
$root  = '../';
$aktif = 'dashboard';
include __DIR__ . '/sidebar.php';
?>
    <div class="main-content">
        <h2>DASHBOARD</h2>
        <p class="sub">Ringkasan data website Jurusan SP &amp; SK. Klik kartu untuk mengelola datanya.</p>
        <div class="stat-grid">
            <?php foreach ($modul as [$nama, $jumlah, $link]) : ?>
            <a class="stat" href="<?= $link ?>">
                <strong><?= $jumlah ?></strong>
                <span>Data <?= $nama ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
