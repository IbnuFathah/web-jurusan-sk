<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/helpers.php';

// 3 berita terbaru & 4 foto galeri terbaru untuk ditampilkan di beranda
$berita = mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal_publikasi DESC, id DESC LIMIT 3");
$galeri = mysqli_query($conn, "SELECT * FROM galeri ORDER BY id DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beranda | SMKN 1 Kawali</title>
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
        <a href="index.php" class="active">Home</a>
        <a href="profile.html">Profile</a>
        <a href="berita.php">Berita</a>
        <a href="galeri.php">Galeri</a>
         <a href="fasilitas.php">Fasilitas</a>
        <!-- <a href="#kontak">Kontak</a> -->
    </nav>
</header>
<section class="hero">
    <h1>Selamat Datang di Jurusan Kami</h1>
    <p>Mewadahi kreativitas, melestarikan budaya, dan menciptakan seniman muda berbakat di bidang Seni Pertunjukan dan Seni Karawitan.</p>
    <a href="profile.html" class="btn">Kenali Kami Lebih Dekat</a>
</section>
<!-- SECTION PROFIL SINGKAT -->
<section class="home-section bg-light">
    <div class="container">
        <h2 class="section-title">PROFILE JURUSAN</h2>
        <div class="profile-preview">
            <div class="profile-img">
                <!-- Gunakan foto dari halaman profile -->
                <img src="assets/img/profile.jpg" alt="Kegiatan Siswa SP & SK">
            </div>
            <div class="profile-text">
                <h3>Sejarah Singkat</h3>
                <p>Jurusan Seni Pertunjukan (SP) dan Seni Karawitan (SK) di SMKN 1 Kawali berdiri pada tahun 2016 didirikan oleh Pak Tisno. Awalnya 1 kelas berisi 18 siswa, kini berkembang menjadi 6 kelas dengan jumlah 25-30 siswa per kelas.</p>
                <a href="profile.html" class="btn-outline">Lihat Profile Lengkap</a>
            </div>
        </div>
    </div>
</section>

<!-- SECTION BERITA & PRESTASI -->
<section class="home-section">
    <div class="container">
        <h2 class="section-title">PRESTASI & BERITA TERKINI</h2>
        <div class="news-grid">
            <?php while ($b = mysqli_fetch_assoc($berita)) : ?>
            <div class="news-card">
                <img src="assets/img/berita/<?= e($b['gambar']) ?>" alt="<?= e($b['judul']) ?>">
                <div class="news-content">
                    <h3 class="gold-text"><?= e($b['judul']) ?></h3>
                    <small><?= tanggal_indo($b['tanggal_publikasi']) ?></small>
                    <p><?= e($b['ringkasan']) ?></p>
                    <a href="detailBerita.php?id=<?= (int) $b['id'] ?>" class="btn-gold">Baca Selengkapnya</a>
                </div>
            </div>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($berita) == 0) : ?>
            <p>Belum ada berita.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- SECTION GALERI SINGKAT -->
<section class="home-section bg-light">
    <div class="container">
        <h2 class="section-title">GALERI JURUSAN</h2>
        <div class="gallery-grid-home">
            <?php while ($g = mysqli_fetch_assoc($galeri)) : ?>
            <img src="assets/img/galeri/<?= e($g['file_gambar']) ?>" alt="<?= e($g['judul_gambar']) ?>">
            <?php endwhile; ?>
        </div>
        <div class="text-center" style="margin-top: 2rem;">
            <a href="galeri.php" class="btn">Lihat Semua Galeri</a>
        </div>
    </div>
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
            <a href="profile.html">Seni Pertunjukan</a>
            <a href="profile.html">Seni Karawitan</a>
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
            <a href="profile.html">Profile</a>
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
