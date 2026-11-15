<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';
require_once __DIR__ . '/../../helpers.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_guru = trim($_POST['nama_guru'] ?? '');
    $jabatan   = trim($_POST['jabatan']   ?? '');
    $gambar_name = '';

    if (empty($nama_guru)) {
        $error = 'Nama guru tidak boleh kosong.';
    } elseif (empty($jabatan)) {
        $error = 'Jabatan tidak boleh kosong.';
    } else {
        // Handle upload foto
        if (!empty($_FILES['gambar']['name'])) {
            $allowed = ['jpg','jpeg','png','webp'];
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = 'Format foto harus JPG, PNG, atau WEBP.';
            } elseif ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
                $error = 'Ukuran foto maksimal 2 MB.';
            } else {
                $gambar_name = 'guru_' . time() . '_' . rand(100, 999) . '.' . $ext;
                $upload_dir  = __DIR__ . '/../../assets/img/guru/';
                if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $gambar_name)) {
                    $error = 'Gagal mengupload foto. Periksa izin folder.';
                    $gambar_name = '';
                }
            }
        }

        if (empty($error)) {
            $nama_esc    = mysqli_real_escape_string($conn, $nama_guru);
            $jabatan_esc = mysqli_real_escape_string($conn, $jabatan);
            $gambar_esc  = mysqli_real_escape_string($conn, $gambar_name);
            $gambar_val  = $gambar_name ? "'$gambar_esc'" : "NULL";

            $sql = "INSERT INTO guru (nama_guru, jabatan, gambar) VALUES ('$nama_esc', '$jabatan_esc', $gambar_val)";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['pesan']    = "Guru \"$nama_guru\" berhasil ditambahkan.";
                $_SESSION['berhasil'] = true;
                header('Location: index.php');
                exit;
            } else {
                $error = 'Gagal menyimpan data: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Guru | Admin SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <style>
        :root {
            --adm-bg: #F8F9FA;
            --adm-card: #FFFFFF;
            --adm-gold: #C5A059;
            --adm-gold-hover: #D4AF37;
            --adm-gold-tint: rgba(197,160,89,0.12);
            --adm-charcoal: #18181B;
            --adm-slate-dark: #1E293B;
            --adm-slate-body: #475569;
            --adm-slate-muted: #94A3B8;
            --adm-border: #E2E8F0;
            --adm-shadow: 0 8px 24px -8px rgba(0,0,0,0.05);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: var(--adm-bg);
            display: flex;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--adm-slate-body);
            min-height: 100vh;
        }
        .main-content {
            flex: 1; min-width: 0;
            padding: 32px 36px 60px;
            display: flex; flex-direction: column; gap: 24px;
        }
        .page-header { display: flex; align-items: center; gap: 12px; }
        .btn-back {
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--adm-slate-muted); text-decoration: none;
            font-size: 14px; font-weight: 500;
            transition: color 0.15s ease;
        }
        .btn-back:hover { color: var(--adm-slate-dark); }
        .btn-back svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
        .page-title { font-size: 24px; font-weight: 800; color: var(--adm-slate-dark); letter-spacing: -0.02em; }
        .page-sub { font-size: 14px; color: var(--adm-slate-muted); margin-top: 2px; }

        .content-card {
            background: var(--adm-card);
            border: 1px solid var(--adm-border);
            border-radius: 16px;
            box-shadow: var(--adm-shadow);
            padding: 32px;
            max-width: 680px;
        }

        .alert { padding: 14px 18px; border-radius: 10px; font-size: 14px; font-weight: 500; border: 1px solid; margin-bottom: 24px; }
        .alert-error { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.3); color: #991b1b; }

        .form-group { margin-bottom: 22px; }
        label {
            display: block;
            font-size: 13px; font-weight: 700;
            color: var(--adm-slate-dark);
            margin-bottom: 8px;
            letter-spacing: 0.01em;
        }
        label span { color: #ef4444; margin-left: 2px; }
        .form-control {
            width: 100%;
            padding: 11px 16px;
            border: 1.5px solid var(--adm-border);
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            color: var(--adm-slate-dark);
            background: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            outline: none;
        }
        .form-control:focus {
            border-color: var(--adm-gold);
            box-shadow: 0 0 0 3px rgba(197,160,89,0.15);
        }
        .form-hint { font-size: 12px; color: var(--adm-slate-muted); margin-top: 6px; }

        /* Avatar preview */
        .upload-area {
            border: 2px dashed var(--adm-border);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s ease, background 0.2s ease;
            position: relative;
        }
        .upload-area:hover { border-color: var(--adm-gold); background: var(--adm-gold-tint); }
        .upload-area input[type="file"] {
            position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
        }
        .upload-preview {
            width: 80px; height: 80px; border-radius: 50%;
            object-fit: cover; margin: 0 auto 12px;
            border: 3px solid var(--adm-border);
            display: none;
        }
        .upload-icon svg { width: 36px; height: 36px; stroke: var(--adm-slate-muted); fill: none; stroke-width: 1.5; }
        .upload-text { font-size: 13px; color: var(--adm-slate-muted); margin-top: 8px; }
        .upload-text strong { color: var(--adm-gold); }

        .form-actions { display: flex; gap: 12px; margin-top: 32px; }
        .btn-submit {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--adm-gold); color: var(--adm-charcoal);
            padding: 12px 28px; border-radius: 10px;
            font-size: 14px; font-weight: 700;
            border: none; cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-submit:hover { background: var(--adm-gold-hover); box-shadow: 0 4px 14px rgba(197,160,89,0.35); transform: translateY(-1px); }
        .btn-submit svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
        .btn-cancel-link {
            display: inline-flex; align-items: center;
            padding: 12px 24px; border-radius: 10px;
            border: 1.5px solid var(--adm-border);
            font-size: 14px; font-weight: 600;
            color: var(--adm-slate-body); text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-cancel-link:hover { background: #F8FAFC; border-color: #CBD5E1; }
    </style>
</head>
<body>

<?php
$root  = '../../';
$aktif = 'guru';
include __DIR__ . '/../sidebar.php';
?>

<main class="main-content">

    <div>
        <a href="index.php" class="btn-back">
            <svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            Kembali ke Daftar Guru
        </a>
        <div class="page-title" style="margin-top: 10px;">Tambah Guru Baru</div>
        <div class="page-sub">Isi formulir berikut untuk menambahkan data guru ke daftar pengajar.</div>
    </div>

    <div class="content-card">
        <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Foto Guru <span style="color: var(--adm-slate-muted); font-weight:400">(opsional)</span></label>
                <div class="upload-area" id="uploadArea">
                    <input type="file" name="gambar" id="gambarInput" accept="image/jpeg,image/png,image/webp" onchange="previewFoto(this)">
                    <img id="fotoPreview" class="upload-preview" src="" alt="Preview">
                    <div class="upload-icon" id="uploadIcon">
                        <svg viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                    </div>
                    <div class="upload-text">Klik untuk pilih foto &bull; <strong>JPG, PNG, WEBP</strong> &bull; Maks 2 MB</div>
                </div>
            </div>

            <div class="form-group">
                <label for="nama_guru">Nama Lengkap <span>*</span></label>
                <input type="text" id="nama_guru" name="nama_guru" class="form-control"
                    placeholder="Contoh: Budi Santoso, S.Pd."
                    value="<?= htmlspecialchars($_POST['nama_guru'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="jabatan">Jabatan / Peran <span>*</span></label>
                <input type="text" id="jabatan" name="jabatan" class="form-control"
                    placeholder="Contoh: Wali Kelas X SP 1, Guru Pengajar"
                    value="<?= htmlspecialchars($_POST['jabatan'] ?? '') ?>" required>
                <div class="form-hint">Contoh: Ketua Program Keahlian, Wali Kelas, Guru Pengajar</div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Data Guru
                </button>
                <a href="index.php" class="btn-cancel-link">Batal</a>
            </div>

        </form>
    </div>

</main>

<script>
function previewFoto(input) {
    const preview = document.getElementById('fotoPreview');
    const icon    = document.getElementById('uploadIcon');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            icon.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</body>
</html>
