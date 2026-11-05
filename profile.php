<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/koneksi.php';

$fasilitas = mysqli_query($conn, "SELECT nama_fasilitas, jumlah FROM fasilitas ORDER BY id ASC");
if (!$fasilitas) {
    die('Gagal memuat data fasilitas: ' . mysqli_error($conn));
}

// Sejarah singkat (tabel pengaturan_web) & daftar guru (tabel guru)
$pengaturan = ambil_pengaturan($conn);
$guru = mysqli_query($conn, "SELECT nama_guru, jabatan FROM guru ORDER BY id ASC");
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
        <?php if (trim($pengaturan['sejarah_singkat'] ?? '') !== '') : ?>
        <?= paragraf($pengaturan['sejarah_singkat']) ?>
        <?php else : ?>
        <p>Belum ada data sejarah.</p>
        <?php endif; ?>

        <h3>Daftar Guru Pengajar</h3>
        <p>Jurusan kami dibimbing oleh tenaga pendidik profesional di bidang seni.</p>
        <button class="btn" id="btnGuru">Lihat Daftar Guru</button>
        <ul id="listGuru">
            <?php while ($g = mysqli_fetch_assoc($guru)) : ?>
            <li><?= e($g['nama_guru']) ?> - <?= e($g['jabatan']) ?></li>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($guru) === 0) : ?>
            <li>Belum ada data guru.</li>
            <?php endif; ?>
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
<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/script.js"></script>
</body>
</html>
