<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/koneksi.php';

$fasilitas = mysqli_query($conn, "SELECT nama_fasilitas, jumlah FROM fasilitas ORDER BY id ASC");
if (!$fasilitas) {
    die('Gagal memuat data fasilitas: ' . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile | SMKN 1 Kawali</title>
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
        <a href="profile.php" class="active">Profile</a>
        <a href="berita.php">Berita</a>
        <a href="galeri.php">Galeri</a>
        <a href="fasilitas.php">Fasilitas</a>
<?php include __DIR__ . '/partials/nav_auth.php'; ?>
        <!-- <a href="#kontak">Kontak</a> -->
    </nav>
</header>
<h2 class="judul">Profile Jurusan</h2>
<section class="profile-box">
    <img src="assets/img/profile.jpg" alt="Juara Umum FLS2N Kabupaten Ciamis 2024">
    <div>
        <h3>Sejarah Singkat</h3>
        <p>Jurusan Seni Pertunjukan (SP) atau Seni Karawitan (SK) di SMKN 1 Kawali berdiri pada tahun 2016 didirikan oleh Pak Tisno. Awalnya 1 kelas berisi 18 siswa, kini berkembang menjadi 6 kelas dengan jumlah 25-30 siswa per kelas.</p>

        <h3>Daftar Guru Pengajar</h3>
        <p>Jurusan kami dibimbing oleh tenaga pendidik profesional di bidang seni.</p>
        <button class="btn" id="btnGuru">Lihat Daftar Guru</button>
        <ul id="listGuru">
            <li>Nama Guru 1 - Mata pelajaran</li>
            <li>Nama Guru 2 - Mata pelajaran</li>
            <li>Nama Guru 3 - Mata pelajaran</li>
        </ul>

        <h3>Fasilitas Praktik</h3>
        <div class="fasilitas">
            <?php while ($f = mysqli_fetch_assoc($fasilitas)) : ?>
            <div>
                <strong><?= e($f['nama_fasilitas']) ?></strong>
                <small><?= e(format_jumlah_fasilitas($f['jumlah'])) ?></small>
            </div>
            <?php endwhile; ?>
            <a class="fasilitas-selengkapnya" href="fasilitas.php">Selengkapnya ></a>
            <?php if (mysqli_num_rows($fasilitas) === 0) : ?>
            <p>Belum ada data fasilitas.</p>
            <?php endif; ?>
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
