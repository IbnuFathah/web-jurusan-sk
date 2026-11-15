<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';

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

<main class="container" style="max-width: 860px; padding-top: 48px; padding-bottom: 80px;">

    <a href="berita.php" class="btn btn-outline btn-sm" style="margin-bottom: 24px;">
        <svg class="icon-svg" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
        <span>Kembali ke Daftar Berita</span>
    </a>

    <?php if ($row) : ?>
    <article class="bento-card" style="padding: 40px;">
        <span class="badge-editorial badge-teal" style="margin-bottom: 16px;">Berita Jurusan</span>
        <h1 style="font-size: 32px; font-weight: 800; color: var(--text-heading); line-height: 1.3; margin-bottom: 16px; letter-spacing: -0.02em;">
            <?= e($row['judul']) ?>
        </h1>

        <div style="display: flex; align-items: center; gap: 16px; color: var(--text-muted); font-size: 13.5px; margin-bottom: 28px; padding-bottom: 20px; border-bottom: 1px solid var(--border-crisp); flex-wrap: wrap;">
            <span style="display: inline-flex; align-items: center; gap: 6px;">
                <svg class="icon-svg" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                <?= tanggal_indo($row['tanggal_publikasi']) ?>
            </span>
            <span>&bull;</span>
            <span>Penulis: Tim Humas SP &amp; SK SMKN 1 Kawali</span>
        </div>

        <div style="margin-bottom: 32px; border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--border-crisp);">
            <img src="assets/img/berita/<?= e($row['gambar']) ?>" alt="<?= e($row['judul']) ?>" style="width: 100%; max-height: 460px; object-fit: cover;">
        </div>

        <div class="artikel-konten" style="line-height: 1.85; color: var(--text-body); font-size: 16px;">
            <?= paragraf($row['konten']) ?>
        </div>

        <div style="margin-top: 40px; padding-top: 24px; border-top: 1px solid var(--border-crisp); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
            <span style="font-weight: 700; font-size: 13.5px; color: var(--text-heading);">Bagikan Informasi Ini:</span>
            <div style="display: flex; gap: 10px;">
                <a href="https://api.whatsapp.com/send?text=<?= urlencode($row['judul']) ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                    <span>WhatsApp</span>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                    <span>Facebook</span>
                </a>
            </div>
        </div>
    </article>
    <?php else : ?>
    <div class="bento-card text-center" style="padding: 60px 24px;">
        <h2 style="margin-bottom: 12px;">Artikel Tidak Ditemukan</h2>
        <p style="color: var(--text-muted); margin-bottom: 24px;">Artikel yang Anda cari mungkin telah dipindahkan atau tautan tidak valid.</p>
        <a href="berita.php" class="btn btn-gold">Kembali ke Berita</a>
    </div>
    <?php endif; ?>

</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/script.js?v=<?= filemtime(__DIR__ . '/assets/js/script.js') ?>"></script>
</body>
</html>
