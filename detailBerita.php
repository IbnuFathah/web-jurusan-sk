<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';

// ambil berita berdasarkan ?id=... dari URL
$id  = (int) ($_GET['id'] ?? 0);
$res = mysqli_query($conn, "SELECT * FROM berita WHERE id = $id");
$row = mysqli_fetch_assoc($res);
if (!$row) {
    http_response_code(404);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $row ? e($row['judul']) : 'Berita Tidak Ditemukan' ?> | SMKN 1 Kawali</title>
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
        <a href="berita.php" class="active">Berita</a>
        <a href="galeri.php">Galeri</a>
         <a href="fasilitas.php">Fasilitas</a>
<?php include __DIR__ . '/partials/nav_auth.php'; ?>
    </nav>
</header>

<main class="detail-berita" style="max-width: 800px; margin: 40px auto; padding: 0 20px;">

    <a href="berita.php" class="btn btn-sm" style="display: inline-block; margin-bottom: 20px; text-decoration: none; background-color: #f0f0f0; color: #333; padding: 8px 15px; border-radius: 4px;">&larr; Kembali ke Berita</a>

    <?php if ($row) : ?>
    <h1 style="font-size: 2em; margin-bottom: 10px;"><?= e($row['judul']) ?></h1>

    <div class="meta" style="color: #666; margin-bottom: 20px; font-size: 0.9em;">
        <span>&#128204; <?= tanggal_indo($row['tanggal_publikasi']) ?></span> |
        <span>Oleh: Admin Humas SP &amp; SK</span>
    </div>

    <div class="gambar-utama" style="margin-bottom: 30px;">
        <img src="assets/img/berita/<?= e($row['gambar']) ?>" alt="<?= e($row['judul']) ?>" style="width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
    </div>

    <article class="artikel-konten" style="line-height: 1.8; color: #444; font-size: 1.05em;">
        <?= paragraf($row['konten']) ?>
    </article>

    <div class="share-artikel" style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd;">
        <h4 style="margin-bottom: 10px;">Bagikan berita ini:</h4>
        <div class="sosmed-share">
            <a href="#" style="display:inline-block; padding:8px 12px; background:#1877f2; color:#fff; border-radius:4px; text-decoration:none; margin-right:5px; font-size: 0.9em;">Facebook</a>
            <a href="#" style="display:inline-block; padding:8px 12px; background:#25D366; color:#fff; border-radius:4px; text-decoration:none; font-size: 0.9em;">WhatsApp</a>
        </div>
    </div>
    <?php else : ?>
    <h1 style="font-size: 2em; margin-bottom: 10px;">Berita Tidak Ditemukan</h1>
    <p style="color: #666;">Maaf, artikel yang Anda cari tidak tersedia atau URL tidak valid.</p>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/script.js?v=<?= filemtime(__DIR__ . '/assets/js/script.js') ?>"></script>
</body>
</html>