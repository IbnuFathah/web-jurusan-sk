<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';

$berita = mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal_publikasi DESC, id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Berita &amp; Prestasi | SMKN 1 Kawali</title>
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
        <a href="berita.php" class="active">Berita</a>
        <a href="galeri.php">Galeri</a>
        <a href="fasilitas.php">Fasilitas</a>
        <a href="#kontak">Kontak</a>
        <?php include __DIR__ . '/partials/nav_auth.php'; ?>
    </nav>
</header>

<header class="page-header-editorial">
    <div class="container">
        <span class="badge-editorial badge-teal">Warta &amp; Dokumentasi</span>
        <h1>Prestasi &amp; Berita Terkini</h1>
        <p>Liputan kegiatan, prestasi kompetisi seni, dan perkembangan terbaru Jurusan Seni Pertunjukan SMKN 1 Kawali.</p>
    </div>
</header>

<main class="container" style="padding-bottom: 80px;">
    <div class="news-grid">
        <?php while ($b = mysqli_fetch_assoc($berita)) : ?>
        <article class="news-card">
            <div class="news-card-img-wrap">
                <img src="assets/img/berita/<?= e($b['gambar']) ?>" alt="<?= e($b['judul']) ?>">
            </div>
            <div class="news-content">
                <div class="news-meta">
                    <svg class="icon-svg" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    <span><?= tanggal_indo($b['tanggal_publikasi']) ?></span>
                </div>
                <h3 class="news-title"><?= e($b['judul']) ?></h3>
                <p class="news-summary"><?= e($b['ringkasan']) ?></p>
                <div class="news-action">
                    <a href="detailBerita.php?id=<?= (int) $b['id'] ?>" class="btn btn-outline btn-sm">
                        <span>Baca Selengkapnya</span>
                        <svg class="icon-svg" style="width: 12px; height: 12px;" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </div>
            </div>
        </article>
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($berita) == 0) : ?>
            <p style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 60px 20px;">Belum ada berita yang dipublikasikan.</p>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/script.js?v=<?= filemtime(__DIR__ . '/assets/js/script.js') ?>"></script>
</body>
</html>
