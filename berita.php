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
    <title>Berita | SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
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
        <a href="berita.php" class="active">Berita</a>
        <a href="galeri.php">Galeri</a>
         <a href="fasilitas.php">Fasilitas</a>
<?php include __DIR__ . '/partials/nav_auth.php'; ?>
    </nav>
</header>
<h2 class="judul">Prestasi &amp; Berita Terkini</h2>
<section class="berita">
    <?php while ($b = mysqli_fetch_assoc($berita)) : ?>
    <article class="card">
        <img src="assets/img/berita/<?= e($b['gambar']) ?>" alt="<?= e($b['judul']) ?>">
        <div class="card-body">
            <h3><?= e($b['judul']) ?></h3>
            <small>&#128204; <?= tanggal_indo($b['tanggal_publikasi']) ?></small>
            <p><?= e($b['ringkasan']) ?></p>
            <a href="detailBerita.php?id=<?= (int) $b['id'] ?>" class="btn btn-sm">Baca Selengkapnya</a>
        </div>
    </article>
    <?php endwhile; ?>
    <?php if (mysqli_num_rows($berita) == 0) : ?>
    <p style="grid-column: 1 / -1; text-align: center;">Belum ada berita.</p>
    <?php endif; ?>
</section>
<footer id="kontak">
    <h2>KONTAK KAMI</h2>
    <div class="footer-grid">
        <div class="footer-about">
            <div class="footer-logo"><img src="assets/img/logo.png" alt="Logo"><span>SMKN 1 KAWALI</span></div>
            <p>Jurusan Seni Pertunjukan &amp; Seni Karawitan SMKN 1 Kawali. Mewadahi kreativitas, melestarikan budaya, dan menciptakan seniman muda berbakat.</p>
            <div class="sosmed">
                <a href="#" style="background:#1877f2" aria-label="Facebook">f</a>
                <a href="#" style="background:#1da1f2" aria-label="Twitter">t</a>
                <a href="#" style="background:#e1306c" aria-label="Instagram">ig</a>
                <a href="#" style="background:#0a66c2" aria-label="LinkedIn">in</a>
            </div>
        </div>
        <!-- <div>
            <h4>Jurusan</h4>
            <a href="profile.php">Seni Pertunjukan</a>
            <a href="profile.php">Seni Karawitan</a>
        </div> -->
        <div>
            <h4>Hubungi Kami</h4>
            <a href="#">Kawali, Ciamis, Jawa Barat</a>
            <a href="#">email@sekolah.sch.id</a>
            <a href="#">(0265) 000000</a>
        </div>
        <div>
            <h4>Link Cepat</h4>
            <a href="index.php">Beranda</a>
            <a href="profile.php">Profile</a>
            <a href="galeri.php">Galeri</a>
            <a href="berita.php">Berita</a>
            <a href="#kontak">Kontak</a>
        </div>
    </div>
    <p class="copy">&copy; 2026 SMKN 1 Kawali - Jurusan SP &amp; SK. All rights reserved.</p>
</footer>
<script src="assets/js/script.js"></script>
</body>
</html>