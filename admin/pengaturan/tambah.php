<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';
require_once __DIR__ . '/../../helpers.php';

// Ambil data pengaturan web
$query = mysqli_query($conn, "SELECT * FROM pengaturan_web ORDER BY id ASC LIMIT 1");
$data = mysqli_fetch_assoc($query);

$maps_preview = url_aman($data['link_maps'] ?? '');
if ($maps_preview === '' && !empty($data['alamat'])) {
    $maps_preview = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($data['alamat']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Web (Profil &amp; Kontak) | Admin SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background: #f4f6f9; display: flex; color: #333; min-height: 100vh; }
        .main-content { flex: 1; padding: 30px; }
        
        .header-section { margin-bottom: 25px; }
        .header-title { font-size: 24px; font-weight: 700; color: #111; margin-bottom: 6px; }
        .header-sub { font-size: 14px; color: #666; }

        .action-bar { display: flex; gap: 12px; margin-bottom: 25px; flex-wrap: wrap; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500; cursor: pointer; border: none; transition: 0.2s; }
        .btn-primary { background: #EEB006; color: #fff; }
        .btn-primary:hover { background: #D9A000; }
        .btn-warning { background: #ff9800; color: #fff; }
        .btn-warning:hover { background: #e68900; }
        .btn-danger { background: #f44336; color: #fff; }
        .btn-danger:hover { background: #d32f2f; }
        .btn-outline { background: #fff; color: #555; border: 1px solid #ccc; }
        .btn-outline:hover { background: #f9f9f9; color: #111; }

        .content-grid { display: grid; grid-template-columns: 1fr; gap: 20px; margin-bottom: 30px; }
        
        .card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .card-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 14px; margin-bottom: 18px; flex-wrap: wrap; gap: 10px; }
        .card-title { font-size: 17px; font-weight: 600; color: #222; display: flex; align-items: center; gap: 8px; }
        .card-badge { display: inline-block; font-size: 12px; background: #f0f3f8; color: #2d5a88; padding: 4px 10px; border-radius: 20px; font-weight: 500; }
        
        .profile-content { background: #fbfbfb; border: 1px solid #eaeaea; border-radius: 8px; padding: 16px 20px; line-height: 1.7; color: #444; font-size: 14px; }
        .profile-content p { margin-bottom: 10px; }
        .profile-content p:last-child { margin-bottom: 0; }

        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
        .info-item { background: #fcfcfc; border: 1px solid #eee; border-radius: 8px; padding: 16px; }
        .info-label { font-size: 12px; color: #888; text-transform: uppercase; font-weight: 600; margin-bottom: 6px; letter-spacing: 0.5px; }
        .info-val { font-size: 15px; font-weight: 500; color: #222; word-break: break-word; }
        .info-val a { color: #EEB006; text-decoration: none; }
        .info-val a:hover { text-decoration: underline; }

        .sosmed-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; }
        .sosmed-card { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-radius: 8px; background: #fafafa; border: 1px solid #eee; }
        .sosmed-icon { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold; font-size: 16px; flex-shrink: 0; }
        .sosmed-text { min-width: 0; }
        .sosmed-name { font-size: 13px; font-weight: 600; color: #333; margin-bottom: 2px; }
        .sosmed-link { font-size: 12px; color: #666; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; text-decoration: none; }
        .sosmed-link:hover { color: #EEB006; text-decoration: underline; }
        .sosmed-empty { font-size: 12px; color: #999; font-style: italic; }

        .empty-state { background: #fff; border-radius: 12px; padding: 50px 30px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .empty-state h3 { font-size: 18px; margin-bottom: 8px; color: #333; }
        .empty-state p { color: #777; font-size: 14px; margin-bottom: 20px; }
    </style>
</head>
<body>

<?php
$root  = '../../';
$aktif = 'pengaturan';
include __DIR__ . '/../sidebar.php';
?>

<main class="main-content">
    <div class="header-section">
        <h1 class="header-title">PENGATURAN WEB (PROFIL &amp; KONTAK)</h1>
        <p class="header-sub">Kelola teks profil jurusan (first look) dan informasi kontak resmi yang tampil di website publik.</p>
    </div>

    <?php if ($data) : ?>
        <div class="action-bar">
            <a href="edit.php?id=<?= (int) $data['id'] ?>" class="btn btn-warning">
                Edit Pengaturan
            </a>
            <a href="../../profile.php" target="_blank" class="btn btn-outline" title="Lihat di Halaman Profile Publik">
                Lihat Halaman Profile
            </a>
            <a href="../../index.php#kontak" target="_blank" class="btn btn-outline" title="Lihat Footer Kontak Publik">
                Lihat Footer Kontak
            </a>
            <a href="hapus.php?id=<?= (int) $data['id'] ?>" class="btn btn-danger" onclick="return confirm('Peringatan: Yakin ingin mereset/menghapus data pengaturan web ini? Halaman profil dan kontak akan kosong!')">
                Reset / Hapus
            </a>
        </div>

        <div class="content-grid">
            <!-- 1. Profil Jurusan (First Look) -->
            <section class="card">
                <div class="card-header">
                    <div class="card-title">
                        Profil Jurusan — Sejarah Singkat (First Look)
                    </div>
                    <span class="card-badge">Tampil di: Halaman Profile &amp; Preview Beranda</span>
                </div>
                <div class="profile-content">
                    <?php if (trim($data['sejarah_singkat'] ?? '') !== '') : ?>
                        <?= paragraf($data['sejarah_singkat']) ?>
                    <?php else : ?>
                        <p style="color: #999; font-style: italic;">Belum ada sejarah singkat yang diisi.</p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- 2. Kontak Kami Sekolah -->
            <section class="card">
                <div class="card-header">
                    <div class="card-title">
                        Informasi Kontak Kami
                    </div>
                    <span class="card-badge">Tampil di: Footer Website ("Hubungi Kami")</span>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Alamat Sekolah</div>
                        <div class="info-val">
                            <?php if (!empty($data['alamat'])) : ?>
                                <a href="<?= e($maps_preview) ?>" target="_blank" rel="noopener">
                                    <?= e($data['alamat']) ?>
                                </a>
                            <?php else : ?>
                                <span style="color: #999;">Belum diisi</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Link Google Maps</div>
                        <div class="info-val">
                            <?php if (!empty($data['link_maps'])) : ?>
                                <a href="<?= e(url_aman($data['link_maps'])) ?>" target="_blank" rel="noopener">
                                    <?= e($data['link_maps']) ?>
                                </a>
                            <?php else : ?>
                                <span style="color: #999; font-size: 13px; font-style: italic;">Otomatis dari pencarian alamat</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email Resmi</div>
                        <div class="info-val">
                            <?php if (!empty($data['email_sekolah'])) : ?>
                                <a href="mailto:<?= e($data['email_sekolah']) ?>">
                                    <?= e($data['email_sekolah']) ?>
                                </a>
                            <?php else : ?>
                                <span style="color: #999;">Belum diisi</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Nomor Telepon</div>
                        <div class="info-val">
                            <?php if (!empty($data['telepon'])) : ?>
                                <?php $tel_link = preg_replace('/[^0-9+]/', '', $data['telepon']); ?>
                                <a href="tel:<?= e($tel_link) ?>">
                                    <?= e($data['telepon']) ?>
                                </a>
                            <?php else : ?>
                                <span style="color: #999;">Belum diisi</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. Tautan Media Sosial -->
            <section class="card">
                <div class="card-header">
                    <div class="card-title">
                        Tautan Media Sosial
                    </div>
                    <span class="card-badge">Tampil di: Ikon Footer ("Sosial Media")</span>
                </div>
                <div class="sosmed-grid">
                    <div class="sosmed-card">
                        <div class="sosmed-icon" style="background: #1877f2;">f</div>
                        <div class="sosmed-text">
                            <div class="sosmed-name">Facebook</div>
                            <?php if (!empty($data['link_facebook'])) : ?>
                                <a class="sosmed-link" href="<?= e(url_aman($data['link_facebook'])) ?>" target="_blank" rel="noopener">
                                    <?= e($data['link_facebook']) ?>
                                </a>
                            <?php else : ?>
                                <span class="sosmed-empty">Belum diatur</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="sosmed-card">
                        <div class="sosmed-icon" style="background: #e1306c;">ig</div>
                        <div class="sosmed-text">
                            <div class="sosmed-name">Instagram</div>
                            <?php if (!empty($data['link_instagram'])) : ?>
                                <a class="sosmed-link" href="<?= e(url_aman($data['link_instagram'])) ?>" target="_blank" rel="noopener">
                                    <?= e($data['link_instagram']) ?>
                                </a>
                            <?php else : ?>
                                <span class="sosmed-empty">Belum diatur</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="sosmed-card">
                        <div class="sosmed-icon" style="background: #1da1f2;">lms</div>
                        <div class="sosmed-text">
                            <div class="sosmed-name">LMS Sekolah</div>
                            <?php if (!empty($data['link_lms'])) : ?>
                                <a class="sosmed-link" href="<?= e(url_aman($data['link_lms'])) ?>" target="_blank" rel="noopener">
                                    <?= e($data['link_lms']) ?>
                                </a>
                            <?php else : ?>
                                <span class="sosmed-empty">Belum diatur</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="sosmed-card">
                        <div class="sosmed-icon" style="background: #0a66c2;">in</div>
                        <div class="sosmed-text">
                            <div class="sosmed-name">LinkedIn</div>
                            <?php if (!empty($data['link_linkedin'])) : ?>
                                <a class="sosmed-link" href="<?= e(url_aman($data['link_linkedin'])) ?>" target="_blank" rel="noopener">
                                    <?= e($data['link_linkedin']) ?>
                                </a>
                            <?php else : ?>
                                <span class="sosmed-empty">Belum diatur</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    <?php else : ?>
        <div class="empty-state">
            <h3>Belum Ada Data Pengaturan Web</h3>
            <p>Data tabel <code>pengaturan_web</code> masih kosong. Buat data pengaturan untuk mengatur profil jurusan dan kontak kami.</p>
            <a href="tambah.php" class="btn btn-primary">+ Tambah Data Pengaturan</a>
        </div>
    <?php endif; ?>
</main>

</body>
</html>