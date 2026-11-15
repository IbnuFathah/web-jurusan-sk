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
    <title>Galeri Kegiatan | SMKN 1 Kawali</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= filemtime(__DIR__ . '/assets/css/style.css') ?>">
    <link rel="stylesheet" href="assets/css/auth.css?v=<?= filemtime(__DIR__ . '/assets/css/auth.css') ?>">
</head>
<body>

<header class="navbar">
    <a href="index.php" class="brand">
        <img src="assets/img/logo.png" alt="Logo SMKN 1 Kawali">
        <div>
            <strong>SMKN 1 KAWALI</strong>
            <small>JURUSAN SP &amp; SK</small>
        </div>
    </a>
    <button class="menu-btn" aria-label="Buka menu navigasi" type="button">
        <svg class="icon-svg" viewBox="0 0 24 24"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
    </button>
    <nav id="menu">
        <a href="index.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="berita.php">Berita</a>
        <a href="galeri.php" class="active">Galeri</a>
        <a href="fasilitas.php">Fasilitas</a>
        <a href="#kontak">Kontak</a>
        <?php include __DIR__ . '/partials/nav_auth.php'; ?>
    </nav>
</header>

<header class="page-header-editorial">
    <div class="container">
        <span class="badge-editorial badge-teal">Arsip &amp; Dokumentasi</span>
        <h1>Galeri Seni Pertunjukan &amp; Karawitan</h1>
        <p>Dokumentasi ragam pementasan panggung, festival seni budaya, dan aktivitas pembelajaran praktik siswa.</p>
    </div>
</header>

<main class="container" style="padding-bottom: 80px;">
    <div class="gallery-grid-editorial">
        <?php while ($g = mysqli_fetch_assoc($galeri)) : ?>
        <img src="assets/img/galeri/<?= e($g['file_gambar']) ?>" alt="<?= e($g['judul_gambar']) ?>" loading="lazy">
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($galeri) == 0) : ?>
            <p style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 60px 20px;">Belum ada dokumentasi galeri yang diunggah.</p>
        <?php endif; ?>
    </div>
</main>

<div id="lightbox"><img src="" alt="Foto diperbesar"></div>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/script.js?v=<?= filemtime(__DIR__ . '/assets/js/script.js') ?>"></script>
</body>
</html>
