<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';

$galeri = mysqli_query($conn, "SELECT * FROM galeri ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri | SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css?v=<?= filemtime(__DIR__ . '/assets/css/auth.css') ?>">
</head>
<body>
<header class="navbar">
    <a href="index.php" class="brand">
        <img src="assets/img/logo.png" alt="Logo SMKN 1 Kawali">
        <div><strong>SMKN 1 KAWALI</strong><small>JURUSAN SP &amp; SK</small></div>
    </a>
    <button class="menu-btn" aria-label="Buka menu">&#9776;</button>
    <nav id="menu">
        <a href="index.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="berita.php">Berita</a>
        <a href="galeri.php" class="active">Galeri</a>
         <a href="fasilitas.php">Fasilitas</a>
<?php include __DIR__ . '/partials/nav_auth.php'; ?>
        <!-- <a href="#kontak">Kontak</a> -->
    </nav>
</header>
<h2 class="judul judul-galeri">Galeri Jurusan Seni Karawitan</h2>
<section class="galeri">
    <?php while ($g = mysqli_fetch_assoc($galeri)) : ?>
    <img src="assets/img/galeri/<?= e($g['file_gambar']) ?>" alt="<?= e($g['judul_gambar']) ?>">
    <?php endwhile; ?>
    <?php if (mysqli_num_rows($galeri) == 0) : ?>
    <p style="grid-column: 1 / -1; text-align: center;">Belum ada foto galeri.</p>
    <?php endif; ?>
</section>
<div id="lightbox"><img src="" alt="Foto diperbesar"></div>
<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/script.js?v=<?= filemtime(__DIR__ . '/assets/js/script.js') ?>"></script>
</body>
</html>
