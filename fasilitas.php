<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';

$fasilitas = mysqli_query($conn, "SELECT * FROM fasilitas ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fasilitas Praktik | SMKN 1 Kawali</title>
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
        <a href="galeri.php">Galeri</a>
        <a href="fasilitas.php" class="active">Fasilitas</a>
        <a href="#kontak">Kontak</a>
        <?php include __DIR__ . '/partials/nav_auth.php'; ?>
    </nav>
</header>

<header class="page-header-editorial">
    <div class="container">
        <span class="badge-editorial badge-teal">Sarana &amp; Prasarana</span>
        <h1>Fasilitas Praktik Seni Pertunjukan</h1>
        <p>Instrumen musik gamelan lengkap, perangkat tata suara, tata cahaya panggung, dan studio latihan standar industri.</p>
    </div>
</header>

<main class="container" style="padding-bottom: 80px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
        <?php while ($f = mysqli_fetch_assoc($fasilitas)) : ?>
        <article class="news-card">
            <div class="news-card-img-wrap" style="height: 220px;">
                <img src="assets/img/fasilitas/<?= e($f['gambar']) ?>" alt="<?= e($f['nama_fasilitas']) ?>">
            </div>
            <div class="news-content" style="padding: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span class="badge-editorial badge-teal" style="margin-bottom: 0; font-size: 11px;">Instrumen Seni</span>
                    <span class="fasilitas-unit-pill"><?= e(format_jumlah_fasilitas($f['jumlah'])) ?></span>
                </div>
                <h3 style="font-size: 17px; font-weight: 700; color: var(--text-heading); margin-top: 6px;">
                    <?= e($f['nama_fasilitas']) ?>
                </h3>
            </div>
        </article>
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($fasilitas) == 0) : ?>
            <p style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 60px 20px;">Belum ada data fasilitas yang ditambahkan.</p>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/script.js?v=<?= filemtime(__DIR__ . '/assets/js/script.js') ?>"></script>
</body>
</html>
