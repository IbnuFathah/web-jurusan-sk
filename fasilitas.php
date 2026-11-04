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
    <title>Fasilitas | SMKN 1 Kawali</title>
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
        <a href="berita.php">Berita</a>
        <a href="galeri.php">Galeri</a>
         <a href="fasilitas.php" class="active">Fasilitas</a>
<?php include __DIR__ . '/partials/nav_auth.php'; ?>
    </nav>
</header>

<h2 class="judul">Fasilitas Praktik Jurusan SP</h2>
<section class="berita">
    <?php while ($f = mysqli_fetch_assoc($fasilitas)) : ?>
    <div class="card" style="width: 18rem;">
        <img src="assets/img/fasilitas/<?= e($f['gambar']) ?>" class="card-img-top" alt="<?= e($f['nama_fasilitas']) ?>">
        <div class="card-body">
            <h3 class="card-title"><?= e($f['nama_fasilitas']) ?></h3>
            <small>Jumlah: <?= e(format_jumlah_fasilitas($f['jumlah'])) ?></small>
        </div>
    </div>
    <?php endwhile; ?>
    <?php if (mysqli_num_rows($fasilitas) == 0) : ?>
    <p style="grid-column: 1 / -1; text-align: center;">Belum ada data fasilitas.</p>
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