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

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/script.js?v=<?= filemtime(__DIR__ . '/assets/js/script.js') ?>"></script>
</body>
</html>