<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/koneksi.php';

$fasilitas = mysqli_query($conn, "SELECT nama_fasilitas, jumlah, gambar FROM fasilitas ORDER BY id ASC");
if (!$fasilitas) {
    die('Gagal memuat data fasilitas: ' . mysqli_error($conn));
}

// Sejarah singkat (tabel pengaturan_web) & daftar guru (tabel guru)
$pengaturan = ambil_pengaturan($conn);
$guru_query = mysqli_query($conn, "SELECT id, nama_guru, jabatan, gambar FROM guru ORDER BY id ASC");
$semua_guru = [];
if ($guru_query) {
    while ($g = mysqli_fetch_assoc($guru_query)) {
        $semua_guru[] = $g;
    }
}
$guru_preview = array_slice($semua_guru, 0, 4);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Jurusan &amp; Data Guru | SMKN 1 Kawali</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= filemtime(__DIR__ . '/assets/css/style.css') ?>">
    <link rel="stylesheet" href="assets/css/auth.css?v=<?= filemtime(__DIR__ . '/assets/css/auth.css') ?>">
</head>
<body>

<!-- Header / Navigation -->
<header class="navbar">
    <a href="index.php" class="brand">
        <img src="assets/img/logo.png" alt="Logo SMKN 1 Kawali">
        <div>
            <strong>SMKN 1 KAWALI</strong>
            <small>JURUSAN SP &amp; SK</small>
        </div>
    </a>
    <button class="menu-btn" aria-label="Buka navigasi menu" type="button">
        <svg class="icon-svg" viewBox="0 0 24 24"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
    </button>
    <nav id="menu">
        <a href="index.php">Home</a>
        <a href="profile.php" class="active">Profile</a>
        <a href="berita.php">Berita</a>
        <a href="galeri.php">Galeri</a>
        <a href="fasilitas.php">Fasilitas</a>
        <a href="#kontak">Kontak</a>
        <?php include __DIR__ . '/partials/nav_auth.php'; ?>
    </nav>
</header>

<!-- Main Page Header Banner -->
<header class="page-header-editorial">
    <div class="container">
        <span class="badge-editorial badge-teal">Profil Program Keahlian</span>
        <h1>Seni Pertunjukan &amp; Karawitan</h1>
        <p>Mewadahi talenta muda dalam melestarikan seni gamelan, karawitan Sunda, dan eksplorasi seni pertunjukan berdaya saing global.</p>
    </div>
</header>

<main class="container" style="padding-bottom: 80px;">

    <!-- 1. BENTO CARD: SEJARAH SINGKAT (FIRST LOOK) -->
    <section class="bento-card">
        <div class="profile-bento-grid">
            <div class="profile-bento-img">
                <img src="assets/img/profile.jpg" alt="Dokumentasi Siswa Seni Pertunjukan SMKN 1 Kawali">
                <div class="profile-bento-caption">
                    <svg class="icon-svg" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                    <span>Dokumentasi Prestasi &amp; Kegiatan Karawitan SMKN 1 Kawali</span>
                </div>
            </div>
            <div class="profile-bento-text">
                <span class="badge-editorial">Sejarah &amp; Filosofi</span>
                <h3>Jejak Langkah &amp; Sejarah Singkat</h3>
                <?php if (trim($pengaturan['sejarah_singkat'] ?? '') !== '') : ?>
                    <?= paragraf($pengaturan['sejarah_singkat']) ?>
                <?php else : ?>
                    <p style="color: var(--text-muted); font-style: italic;">Informasi sejarah singkat belum tersedia.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- 2. BENTO CARD: DAFTAR GURU PENGAJAR (PREVIEW SLIDER & MODAL FLOW) -->
    <section class="bento-card teachers-section-card" id="daftar-guru">
        <div class="teachers-header-row">
            <div>
                <span class="badge-editorial badge-teal">Tenaga Pendidik</span>
                <h3 style="font-size: 26px; font-weight: 800; color: var(--text-heading); margin-bottom: 6px;">Daftar Guru Pengajar</h3>
                <p style="color: var(--text-body); font-size: 14.5px;">Daftar Guru yang mengisi kelas di jurusan Seni Karawitan/Seni Pertunjukan</p>
            </div>
            <div class="teachers-slider-controls">
                <button type="button" class="slider-nav-btn" id="teacherSliderPrev" aria-label="Guru sebelumnya" title="Sebelumnya">
                    <svg class="icon-svg" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button type="button" class="slider-nav-btn" id="teacherSliderNext" aria-label="Guru berikutnya" title="Berikutnya">
                    <svg class="icon-svg" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
        </div>

        <!-- 4-Card Horizontal Carousel / Slider -->
        <div class="teachers-slider-container">
            <div class="teachers-slider-track" id="teacherSliderTrack">
                <?php foreach ($guru_preview as $idx => $g) : ?>
                    <?php 
                        $is_alt = ($idx % 2 === 1);
                        $avatar_path = !empty($g['gambar']) ? 'assets/img/guru/' . $g['gambar'] : '';
                        $has_photo = ($avatar_path !== '' && file_exists(__DIR__ . '/' . $avatar_path));
                    ?>
                    <article class="teacher-card<?= $is_alt ? ' teacher-card--alt' : '' ?>" data-teacher-id="<?= (int) $g['id'] ?>">
                        <!-- Top Header Block (Two-Tone Split) -->
                        <div class="teacher-card-header"></div>
                        
                        <!-- Circular Avatar Framed by Crisp White Ring -->
                        <div class="teacher-card-avatar-wrap">
                            <div class="teacher-card-avatar">
                                <?php if ($has_photo) : ?>
                                    <img src="<?= e($avatar_path) ?>" alt="<?= e($g['nama_guru']) ?>">
                                <?php else : ?>
                                    <div class="teacher-card-avatar-placeholder">
                                        <svg class="icon-svg" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="teacher-card-body">
                            <h4 class="teacher-card-name"><?= e($g['nama_guru']) ?></h4>
                            <p class="teacher-card-role"><?= e($g['jabatan']) ?></p>
                            <button type="button" class="teacher-card-btn btn-view-teacher" data-id="<?= (int) $g['id'] ?>" data-name="<?= e($g['nama_guru']) ?>" data-role="<?= e($g['jabatan']) ?>">
                                <span>Lihat Profil</span>
                                <svg class="icon-svg" style="width: 13px; height: 13px;" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                            </button>
                        </div>
                    </article>
                <?php endforeach; ?>

                <?php if (empty($guru_preview)) : ?>
                    <p style="padding: 40px; color: var(--text-muted); text-align: center; width: 100%;">Belum ada data guru pengajar yang terdaftar.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pagination Dots -->
        <div class="teachers-slider-dots" id="teacherSliderDots">
            <?php foreach ($guru_preview as $idx => $g) : ?>
                <button type="button" class="slider-dot<?= $idx === 0 ? ' active' : '' ?>" data-index="<?= $idx ?>" aria-label="Slide <?= $idx + 1 ?>"></button>
            <?php endforeach; ?>
        </div>

        <!-- "Lihat Daftar Guru Selengkapnya" Flow (Gold Primary CTA) -->
        <div class="teachers-cta-wrap">
            <button type="button" class="btn btn-gold" id="btnOpenTeacherDirectory">
                <span>Lihat Daftar Guru Selengkapnya</span>
                <svg class="icon-svg" viewBox="0 0 24 24"><path d="m5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
        </div>
    </section>

    <!-- 3. BENTO CARD: FASILITAS PRAKTIK JURUSAN -->
    <section class="bento-card fasilitas-bento-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
            <div>
                <span class="badge-editorial">Sarana &amp; Prasarana</span>
                <h3 style="font-size: 26px; font-weight: 800; color: var(--text-heading); margin-bottom: 6px;">Fasilitas Praktik Seni</h3>
                <p style="color: var(--text-body); font-size: 14.5px;">Instrumen dan peralatan standar industri seni pertunjukan untuk menunjang kompetensi siswa.</p>
            </div>
            <a href="fasilitas.php" class="btn btn-outline" style="font-size: 13.5px;">
                <span>Lihat Semua Fasilitas</span>
                <svg class="icon-svg" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>

        <div class="fasilitas-grid-bento">
            <?php while ($f = mysqli_fetch_assoc($fasilitas)) : ?>
                <div class="fasilitas-item-bento">
                    <div class="fasilitas-info">
                        <strong><?= e($f['nama_fasilitas']) ?></strong>
                        <span>Sarana Praktik Seni</span>
                    </div>
                    <span class="fasilitas-unit-pill"><?= e(format_jumlah_fasilitas($f['jumlah'])) ?></span>
                </div>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($fasilitas) === 0) : ?>
                <p style="color: var(--text-muted); grid-column: 1 / -1;">Belum ada data fasilitas.</p>
            <?php endif; ?>
        </div>
    </section>

</main>

<!-- ===================================================
     FULL TEACHER DIRECTORY MODAL (ALL TEACHERS DIRECTORY)
=================================================== -->
<div class="directory-modal-overlay" id="teacherDirectoryModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="directoryModalTitle">
    <div class="directory-modal-card">
        <!-- Modal Header -->
        <div class="directory-modal-header">
            <div class="directory-modal-title">
                <span class="badge-editorial badge-teal" style="margin-bottom: 6px;">Direktori Lengkap</span>
                <h2 id="directoryModalTitle">Daftar Seluruh Guru &amp; Tenaga Pendidik</h2>
                <p>Jurusan Seni Pertunjukan &amp; Seni Karawitan SMKN 1 Kawali (Total: <?= count($semua_guru) ?> Pengajar)</p>
            </div>
            <button type="button" class="directory-close-btn" id="btnCloseTeacherDirectory" aria-label="Tutup direktori guru">
                <svg class="icon-svg" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
                <span>Kembali ke Profil</span>
            </button>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="directory-toolbar">
            <div class="directory-search-box">
                <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" id="teacherSearchInput" class="directory-search-input" placeholder="Cari nama guru atau jabatan..." autocomplete="off">
            </div>
            <div class="directory-filter-chips">
                <button type="button" class="filter-chip active" data-filter="all">Semua (<?= count($semua_guru) ?>)</button>
                <button type="button" class="filter-chip" data-filter="wali">Wali Kelas</button>
                <button type="button" class="filter-chip" data-filter="pengajar">Guru Pengajar</button>
                <button type="button" class="filter-chip" data-filter="ketua">Ketua Program</button>
            </div>
        </div>

        <!-- Directory Cards Grid -->
        <div class="directory-modal-body">
            <div class="directory-grid" id="teacherDirectoryGrid">
                <?php foreach ($semua_guru as $idx => $g) : ?>
                    <?php 
                        $is_alt = ($idx % 2 === 1);
                        $avatar_path = !empty($g['gambar']) ? 'assets/img/guru/' . $g['gambar'] : '';
                        $has_photo = ($avatar_path !== '' && file_exists(__DIR__ . '/' . $avatar_path));
                    ?>
                    <article class="teacher-card<?= $is_alt ? ' teacher-card--alt' : '' ?>" 
                             data-teacher-name="<?= strtolower(e($g['nama_guru'])) ?>" 
                             data-teacher-role="<?= strtolower(e($g['jabatan'])) ?>">
                        <div class="teacher-card-header"></div>
                        <div class="teacher-card-avatar-wrap">
                            <div class="teacher-card-avatar">
                                <?php if ($has_photo) : ?>
                                    <img src="<?= e($avatar_path) ?>" alt="<?= e($g['nama_guru']) ?>">
                                <?php else : ?>
                                    <div class="teacher-card-avatar-placeholder">
                                        <svg class="icon-svg" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="teacher-card-body">
                            <h4 class="teacher-card-name"><?= e($g['nama_guru']) ?></h4>
                            <p class="teacher-card-role"><?= e($g['jabatan']) ?></p>
                            <button type="button" class="teacher-card-btn btn-view-teacher" data-id="<?= (int) $g['id'] ?>" data-name="<?= e($g['nama_guru']) ?>" data-role="<?= e($g['jabatan']) ?>">
                                <span>Lihat Profil</span>
                                <svg class="icon-svg" style="width: 13px; height: 13px;" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                            </button>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="directory-empty" id="teacherDirectoryEmpty" style="display: none;">
                <svg class="icon-svg" style="width: 48px; height: 48px; stroke: var(--text-muted); margin-bottom: 12px;" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <p>Tidak ada tenaga pendidik yang cocok dengan kata kunci pencarian.</p>
            </div>
        </div>
    </div>
</div>

<!-- ===================================================
     TEACHER DETAIL MODAL (SINGLE TEACHER PROFILE)
=================================================== -->
<div class="directory-modal-overlay" id="teacherDetailModal" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="teacher-detail-card">
        <div class="teacher-detail-header"></div>
        <div class="teacher-detail-avatar-wrap">
            <div class="teacher-detail-avatar">
                <div class="teacher-card-avatar-placeholder" style="background:#F1F5F9; width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                    <svg class="icon-svg" style="width: 44px; height: 44px; stroke: var(--brand-teal);" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
            </div>
        </div>
        <div class="teacher-detail-body">
            <h3 class="teacher-detail-name" id="modalTeacherName">-</h3>
            <p class="teacher-detail-role" id="modalTeacherRole">-</p>
            
            <div class="teacher-detail-meta">
                <div style="margin-bottom: 8px;">
                    <span style="color: var(--text-muted); font-size: 11.5px; text-transform: uppercase; font-weight: 700; display: block;">Unit Pengajar</span>
                    <strong>Seni Pertunjukan (SP) &amp; Seni Karawitan (SK)</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); font-size: 11.5px; text-transform: uppercase; font-weight: 700; display: block;">Institusi</span>
                    <span>SMK Negeri 1 Kawali, Kab. Ciamis</span>
                </div>
            </div>

            <button type="button" class="btn btn-outline" id="btnCloseTeacherDetail" style="width: 100%;">
                <span>Tutup</span>
            </button>
        </div>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/script.js?v=<?= filemtime(__DIR__ . '/assets/js/script.js') ?>"></script>
</body>
</html>
