<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';

// Jika data sudah ada, langsung arahkan ke halaman edit
$cek = mysqli_query($conn, "SELECT id FROM pengaturan_web ORDER BY id ASC LIMIT 1");
if ($cek && mysqli_num_rows($cek) > 0) {
    $row_exist = mysqli_fetch_assoc($cek);
    header("Location: edit.php?id=" . $row_exist['id']);
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sejarah_singkat = trim($_POST['sejarah_singkat'] ?? '');
    $alamat          = trim($_POST['alamat'] ?? '');
    $link_maps       = trim($_POST['link_maps'] ?? '');
    $email_sekolah   = trim($_POST['email_sekolah'] ?? '');
    $telepon         = trim($_POST['telepon'] ?? '');
    $link_facebook   = trim($_POST['link_facebook'] ?? '');
    $link_instagram  = trim($_POST['link_instagram'] ?? '');
    $link_lms        = trim($_POST['link_lms'] ?? '');
    $link_linkedin   = trim($_POST['link_linkedin'] ?? '');

    $rapikan_url = function($url) {
        if ($url === '') return null;
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url;
        }
        return $url;
    };

    $link_maps      = $rapikan_url($link_maps);
    $link_facebook  = $rapikan_url($link_facebook);
    $link_instagram = $rapikan_url($link_instagram);
    $link_lms       = $rapikan_url($link_lms);
    $link_linkedin  = $rapikan_url($link_linkedin);

    if ($sejarah_singkat === '') {
        $error = 'Sejarah singkat profil jurusan wajib diisi!';
    } elseif ($alamat === '') {
        $error = 'Alamat sekolah wajib diisi!';
    } elseif ($email_sekolah === '' || !filter_var($email_sekolah, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email sekolah tidak valid!';
    } elseif ($telepon === '') {
        $error = 'Nomor telepon wajib diisi!';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO pengaturan_web (sejarah_singkat, alamat, link_maps, email_sekolah, telepon, link_facebook, link_instagram, link_lms, link_linkedin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssssss", $sejarah_singkat, $alamat, $link_maps, $email_sekolah, $telepon, $link_facebook, $link_instagram, $link_lms, $link_linkedin);
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Data pengaturan web berhasil dibuat!'); window.location='index.php';</script>";
                exit;
            } else {
                $error = 'Gagal menyimpan data: ' . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Gagal menyiapkan query database: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengaturan Web | Admin SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background: #f4f6f9; display: flex; color: #333; min-height: 100vh; }
        .main-content { flex: 1; padding: 30px; }
        
        .header-title { font-size: 24px; font-weight: 700; color: #111; margin-bottom: 6px; }
        .header-sub { font-size: 14px; color: #666; margin-bottom: 25px; }

        .form-card { background: #fff; max-width: 860px; border-radius: 12px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        .alert-error { background: #ffebee; border-left: 4px solid #f44336; color: #c62828; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; font-size: 14px; }

        .section-box { border-bottom: 1px solid #eee; padding-bottom: 22px; margin-bottom: 22px; }
        .section-box:last-of-type { border-bottom: none; margin-bottom: 10px; }
        .section-title { font-size: 16px; font-weight: 600; color: #222; margin-bottom: 6px; display: flex; align-items: center; gap: 8px; }
        .section-desc { font-size: 13px; color: #777; margin-bottom: 16px; }

        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 6px; }
        .form-group label span.req { color: #f44336; }
        
        .form-control { width: 100%; padding: 10px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; font-family: inherit; color: #333; transition: border-color 0.2s; }
        .form-control:focus { outline: none; border-color: #EEB006; box-shadow: 0 0 0 3px rgba(238, 176, 6, 0.15); }
        textarea.form-control { resize: vertical; line-height: 1.6; }
        
        .form-hint { font-size: 12px; color: #888; margin-top: 4px; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 640px) {
            .grid-2 { grid-template-columns: 1fr; }
        }

        .btn-group { display: flex; gap: 12px; margin-top: 25px; flex-wrap: wrap; }
        .btn-submit { background: #EEB006; color: white; border: none; padding: 11px 24px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; transition: 0.2s; }
        .btn-submit:hover { background: #D9A000; }
        .btn-kembali { background: #6c757d; text-decoration: none; color: white; padding: 11px 20px; border-radius: 6px; display: inline-block; font-size: 14px; font-weight: 500; transition: 0.2s; }
        .btn-kembali:hover { background: #5a6268; }
    </style>
</head>
<body>

<?php
$root  = '../../';
$aktif = 'pengaturan';
include __DIR__ . '/../sidebar.php';
?>

<main class="main-content">
    <div class="header-title">TAMBAH PENGATURAN WEB (PROFIL &amp; KONTAK)</div>
    <p class="header-sub">Isi informasi profil jurusan (first look) dan kontak resmi yang tampil di website publik.</p>

    <div class="form-card">
        <?php if (!empty($error)) : ?>
            <div class="alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <!-- 1. Bagian Profil Jurusan (First Look) -->
            <div class="section-box">
                <div class="section-title">
                    Bagian Profil Jurusan — First Look
                </div>
                <div class="section-desc">
                    Teks ini adalah sejarah singkat atau deskripsi pembuka jurusan. Tampil di halaman <strong>Profile</strong> dan section profil di <strong>Beranda</strong>.
                </div>

                <div class="form-group">
                    <label for="sejarah_singkat">Sejarah Singkat Jurusan <span class="req">*</span></label>
                    <textarea id="sejarah_singkat" name="sejarah_singkat" class="form-control" rows="8" placeholder="Tuliskan sejarah singkat jurusan di sini..." required><?= htmlspecialchars($_POST['sejarah_singkat'] ?? '') ?></textarea>
                    <div class="form-hint">Tip: Pisahkan paragraf dengan menekan Enter 2 kali (baris kosong).</div>
                </div>
            </div>

            <!-- 2. Bagian Kontak Sekolah -->
            <div class="section-box">
                <div class="section-title">
                    Informasi Kontak Kami
                </div>
                <div class="section-desc">
                    Informasi ini tampil pada footer seluruh halaman website (kolom "Hubungi Kami").
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat Sekolah / Jurusan <span class="req">*</span></label>
                    <input type="text" id="alamat" name="alamat" class="form-control" value="<?= htmlspecialchars($_POST['alamat'] ?? '') ?>" placeholder="Contoh: Kawali, Ciamis, Jawa Barat" required>
                </div>

                <div class="form-group">
                    <label for="link_maps">Link URL Google Maps</label>
                    <input type="text" id="link_maps" name="link_maps" class="form-control" value="<?= htmlspecialchars($_POST['link_maps'] ?? '') ?>" placeholder="https://maps.app.goo.gl/...">
                    <div class="form-hint">Opsional: Tempel tautan titik lokasi Google Maps sekolah. Jika dikosongkan, sistem otomatis mengarahkan ke pencarian Google Maps berdasarkan teks alamat.</div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="email_sekolah">Email Sekolah <span class="req">*</span></label>
                        <input type="email" id="email_sekolah" name="email_sekolah" class="form-control" value="<?= htmlspecialchars($_POST['email_sekolah'] ?? '') ?>" placeholder="nama@sekolah.sch.id" required>
                    </div>

                    <div class="form-group">
                        <label for="telepon">Nomor Telepon / WhatsApp <span class="req">*</span></label>
                        <input type="text" id="telepon" name="telepon" class="form-control" value="<?= htmlspecialchars($_POST['telepon'] ?? '') ?>" placeholder="(0265) 000000 atau 08123456789" required>
                    </div>
                </div>
            </div>

            <!-- 3. Bagian Tautan Media Sosial -->
            <div class="section-box">
                <div class="section-title">
                    Tautan Media Sosial (Footer)
                </div>
                <div class="section-desc">
                    Opsional: Ikon sosial media di footer hanya akan muncul jika tautan diisi.
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="link_facebook">Link Facebook</label>
                        <input type="text" id="link_facebook" name="link_facebook" class="form-control" value="<?= htmlspecialchars($_POST['link_facebook'] ?? '') ?>" placeholder="https://facebook.com/namahalaman">
                    </div>

                    <div class="form-group">
                        <label for="link_instagram">Link Instagram</label>
                        <input type="text" id="link_instagram" name="link_instagram" class="form-control" value="<?= htmlspecialchars($_POST['link_instagram'] ?? '') ?>" placeholder="https://instagram.com/namaakun">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="link_lms">Link LMS</label>
                        <input type="text" id="link_lms" name="link_lms" class="form-control" value="<?= htmlspecialchars($_POST['link_lms'] ?? '') ?>" placeholder="https://lms.com/namaakun">
                    </div>

                    <div class="form-group">
                        <label for="link_linkedin">Link LinkedIn</label>
                        <input type="text" id="link_linkedin" name="link_linkedin" class="form-control" value="<?= htmlspecialchars($_POST['link_linkedin'] ?? '') ?>" placeholder="https://linkedin.com/in/namaakun">
                    </div>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-submit">Simpan Pengaturan</button>
                <a href="index.php" class="btn-kembali">Kembali</a>
            </div>
        </form>
    </div>
</main>

</body>
</html>