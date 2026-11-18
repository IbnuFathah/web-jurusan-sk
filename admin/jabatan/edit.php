<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';
require_once __DIR__ . '/../../helpers.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

$res = mysqli_query($conn, "SELECT * FROM jabatan WHERE id = $id LIMIT 1");
if (!$res || mysqli_num_rows($res) === 0) {
    $_SESSION['pesan']    = 'Data jabatan tidak ditemukan.';
    $_SESSION['berhasil'] = false;
    header('Location: index.php');
    exit;
}
$jabatan = mysqli_fetch_assoc($res);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_jabatan = trim($_POST['nama_jabatan'] ?? '');

    if (empty($nama_jabatan)) {
        $error = 'Nama jabatan tidak boleh kosong.';
    } else {
        $nama_esc = mysqli_real_escape_string($conn, $nama_jabatan);
        $cek = mysqli_query($conn, "SELECT id FROM jabatan WHERE nama_jabatan = '$nama_esc' AND id != $id LIMIT 1");
        if ($cek && mysqli_num_rows($cek) > 0) {
            $error = 'Nama jabatan tersebut sudah digunakan.';
        } else {
            $sql = "UPDATE jabatan SET nama_jabatan = '$nama_esc' WHERE id = $id";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['pesan']    = "Jabatan \"$nama_jabatan\" berhasil diperbarui.";
                $_SESSION['berhasil'] = true;
                header('Location: index.php');
                exit;
            } else {
                $error = 'Gagal memperbarui data: ' . mysqli_error($conn);
            }
        }
    }
    $jabatan['nama_jabatan'] = $_POST['nama_jabatan'] ?? $jabatan['nama_jabatan'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Jabatan | Admin SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <style>
        :root {
            --adm-bg: #F8F9FA; --adm-card: #FFFFFF;
            --adm-gold: #C5A059; --adm-gold-hover: #D4AF37;
            --adm-charcoal: #18181B; --adm-slate-dark: #1E293B;
            --adm-slate-body: #475569; --adm-slate-muted: #94A3B8;
            --adm-border: #E2E8F0; --adm-shadow: 0 8px 24px -8px rgba(0,0,0,0.05);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--adm-bg); display: flex; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--adm-slate-body); min-height: 100vh; }
        .main-content { flex: 1; min-width: 0; padding: 32px 36px 60px; display: flex; flex-direction: column; gap: 24px; }
        .btn-back { display: inline-flex; align-items: center; gap: 8px; color: var(--adm-slate-muted); text-decoration: none; font-size: 14px; font-weight: 500; transition: color 0.15s; }
        .btn-back:hover { color: var(--adm-slate-dark); }
        .btn-back svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
        .page-title { font-size: 24px; font-weight: 800; color: var(--adm-slate-dark); letter-spacing: -0.02em; margin-top: 10px; }
        .page-sub { font-size: 14px; color: var(--adm-slate-muted); margin-top: 2px; }
        .content-card { background: var(--adm-card); border: 1px solid var(--adm-border); border-radius: 16px; box-shadow: var(--adm-shadow); padding: 32px; max-width: 680px; }
        .alert { padding: 14px 18px; border-radius: 10px; font-size: 14px; font-weight: 500; border: 1px solid; margin-bottom: 24px; }
        .alert-error { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.3); color: #991b1b; }
        .form-group { margin-bottom: 22px; }
        label { display: block; font-size: 13px; font-weight: 700; color: var(--adm-slate-dark); margin-bottom: 8px; }
        label span { color: #ef4444; }
        .form-control { width: 100%; padding: 11px 16px; border: 1.5px solid var(--adm-border); border-radius: 10px; font-size: 14px; font-family: inherit; color: var(--adm-slate-dark); background: #fff; outline: none; transition: border-color 0.2s, box-shadow 0.2s; }
        .form-control:focus { border-color: var(--adm-gold); box-shadow: 0 0 0 3px rgba(197,160,89,0.15); }
        .form-actions { display: flex; gap: 12px; margin-top: 32px; }
        .btn-submit { display: inline-flex; align-items: center; gap: 8px; background: var(--adm-gold); color: var(--adm-charcoal); padding: 12px 28px; border-radius: 10px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-submit:hover { background: var(--adm-gold-hover); box-shadow: 0 4px 14px rgba(197,160,89,0.35); transform: translateY(-1px); }
        .btn-submit svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
        .btn-cancel-link { display: inline-flex; align-items: center; padding: 12px 24px; border-radius: 10px; border: 1.5px solid var(--adm-border); font-size: 14px; font-weight: 600; color: var(--adm-slate-body); text-decoration: none; }
        .btn-cancel-link:hover { background: #F8FAFC; }
    </style>
</head>
<body>

<?php
$root  = '../../';
$aktif = 'jabatan';
include __DIR__ . '/../sidebar.php';
?>

<main class="main-content">
    <div>
        <a href="index.php" class="btn-back">
            <svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            Kembali ke Daftar Jabatan
        </a>
        <div class="page-title">Edit Jabatan</div>
        <div class="page-sub">Perbarui nama jabatan: <strong><?= htmlspecialchars($jabatan['nama_jabatan']) ?></strong></div>
    </div>

    <div class="content-card">
        <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="nama_jabatan">Nama Jabatan / Peran <span>*</span></label>
                <input type="text" id="nama_jabatan" name="nama_jabatan" class="form-control"
                    value="<?= htmlspecialchars($jabatan['nama_jabatan']) ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Perubahan
                </button>
                <a href="index.php" class="btn-cancel-link">Batal</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>