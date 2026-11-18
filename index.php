<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';

// 3 berita terbaru & 4 foto galeri terbaru untuk ditampilkan di beranda
$berita = mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal_publikasi DESC, id DESC LIMIT 3");
$galeri = mysqli_query($conn, "SELECT * FROM galeri ORDER BY id DESC LIMIT 4");
$pengaturan = ambil_pengaturan($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beranda | SMKN 1 Kawali — Jurusan SP &amp; SK</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= filemtime(__DIR__ . '/assets/css/style.css') ?>">
    <link rel="stylesheet" href="assets/css/auth.css?v=<?= filemtime(__DIR__ . '/assets/css/auth.css') ?>">
</head>
<body>

<!-- Navigation -->
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
        <a href="index.php" class="active">Home</a>
        <a href="profile.php">Profile</a>
        <a href="berita.php">Berita</a>
        <a href="galeri.php">Galeri</a>
        <a href="fasilitas.php">Fasilitas</a>
        <a href="#kontak">Kontak</a>
        <?php include __DIR__ . '/partials/nav_auth.php'; ?>
    </nav>
</header>

<!-- Hero Section (Original Background & Gold Accent) -->
<section class="hero">
    <h1>Selamat Datang di Jurusan Kami</h1>
    <p>Mewadahi kreativitas, melestarikan budaya, dan menciptakan seniman muda berbakat di bidang Seni Pertunjukan dan Seni Karawitan SMKN 1 Kawali.</p>
    <div class="hero-actions">
        <a href="profile.php" class="btn btn-gold">
            <span>Kenali Profil &amp; Guru</span>
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="m5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
        <a href="#berita-terkini" class="btn btn-outline" style="border-color: rgba(255,255,255,0.7); color: #ffffff;">
            <span>Prestasi &amp; Berita</span>
        </a>
    </div>
</section>

<main class="container" style="display: flex; flex-direction: column; gap: 48px; padding-top: 56px; padding-bottom: 80px;">

    <!-- SECTION PROFIL JURUSAN (BENTO CARD FIRST LOOK) -->
    <section class="bento-card">
        <div class="profile-bento-grid">
            <div class="profile-bento-img">
                <img src="assets/img/profile.jpg" alt="Kegiatan Siswa SP &amp; SK SMKN 1 Kawali">
                <div class="profile-bento-caption">
                    <svg class="icon-svg" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                    <span>Pusat Pembinaan Bakat Seni Tradisi &amp; Modern</span>
                </div>
            </div>
            <div class="profile-bento-text">
                <span class="badge-editorial">Profil Jurusan</span>
                <h3>Sejarah Singkat &amp; Visi Jurusan</h3>
                <?php if (trim($pengaturan['sejarah_singkat'] ?? '') !== '') : ?>
                    <?= paragraf($pengaturan['sejarah_singkat']) ?>
                <?php else : ?>
                    <p style="color: var(--text-muted); font-style: italic;">Informasi sejarah singkat belum diatur.</p>
                <?php endif; ?>
                <div style="margin-top: 24px; display: flex; gap: 12px; flex-wrap: wrap;">
                    <a href="profile.php" class="btn btn-gold">
                        <span>Lihat Profil Lengkap</span>
                        <svg class="icon-svg" viewBox="0 0 24 24"><path d="m5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                    <a href="profile.php#daftar-guru" class="btn btn-outline">
                        <span>Daftar Guru Pengajar</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION PRESTASI & BERITA TERKINI -->
    <section class="bento-card" id="berita-terkini">
        <div class="section-header" style="margin-bottom: 32px;">
            <span class="badge-editorial badge-teal">Warta &amp; Prestasi</span>
            <h2 class="section-title">Prestasi &amp; Berita Terkini</h2>
            <p class="section-desc">Kabar terbaru seputar karya, pentas panggung, dan prestasi membanggakan siswa jurusan kami.</p>
        </div>

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
                <p style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 40px;">Belum ada berita yang dipublikasikan.</p>
            <?php endif; ?>
        </div>

        <div class="text-center" style="margin-top: 36px;">
            <a href="berita.php" class="btn btn-outline">
                <span>Lihat Seluruh Berita</span>
                <svg class="icon-svg" viewBox="0 0 24 24"><path d="m5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
    </section>

    <!-- SECTION GALERI JURUSAN -->
    <section class="bento-card">
        <div class="section-header" style="margin-bottom: 32px;">
            <span class="badge-editorial">Dokumentasi Visual</span>
            <h2 class="section-title">Galeri Jurusan Seni Karawitan</h2>
            <p class="section-desc">Momen pementasan, proses latihan, dan kebersamaan keluarga besar Seni Pertunjukan.</p>
        </div>

        <div class="gallery-grid-editorial">
            <?php while ($g = mysqli_fetch_assoc($galeri)) : ?>
            <img src="assets/img/galeri/<?= e($g['file_gambar']) ?>" alt="<?= e($g['judul_gambar']) ?>">
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($galeri) == 0) : ?>
                <p style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 40px;">Belum ada foto galeri.</p>
            <?php endif; ?>
        </div>

        <div class="text-center" style="margin-top: 36px;">
            <a href="galeri.php" class="btn btn-outline">
                <span>Jelajahi Semua Foto Galeri</span>
                <svg class="icon-svg" viewBox="0 0 24 24"><path d="m5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
    </section>

</main>

<div id="lightbox"><img src="" alt="Foto diperbesar"></div>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/script.js?v=<?= filemtime(__DIR__ . '/assets/js/script.js') ?>"></script>
</body>
</html>
