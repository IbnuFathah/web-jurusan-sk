<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';

$id = (int) ($_GET['id'] ?? 0);
$result = mysqli_query($conn, "SELECT id, judul_gambar, file_gambar FROM galeri WHERE id = $id");
$row = $result ? mysqli_fetch_assoc($result) : null;

if (!$row) {
    header('Location: admin_galeri.php');
    exit;
}

$error = '';

if (isset($_POST['submit'])) {
    $judul_gambar = trim($_POST['judul_gambar'] ?? '');
    $nama_file_baru = $row['file_gambar'];
    $file_baru_diupload = false;

    if ($judul_gambar === '') {
        $error = 'Judul gambar wajib diisi.';
    } elseif (!isset($_FILES['file_gambar']) || $_FILES['file_gambar']['error'] === UPLOAD_ERR_NO_FILE) {
        // Gambar lama tetap digunakan jika tidak ada file baru.
    } elseif ($_FILES['file_gambar']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Gagal mengunggah gambar.';
    } else {
        $ekstensi = strtolower(pathinfo($_FILES['file_gambar']['name'], PATHINFO_EXTENSION));
        $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];
        $info_gambar = @getimagesize($_FILES['file_gambar']['tmp_name']);

        if (!in_array($ekstensi, $ekstensi_valid, true) || $info_gambar === false) {
            $error = 'Pilih file gambar JPG, JPEG, PNG, atau WEBP yang valid.';
        } else {
            $nama_file_baru = uniqid() . '.' . $ekstensi;
            $path_file_baru = __DIR__ . '/../../assets/img/galeri/' . $nama_file_baru;

            if (!move_uploaded_file($_FILES['file_gambar']['tmp_name'], $path_file_baru)) {
                $error = 'Gagal menyimpan file gambar yang diunggah.';
            } else {
                $file_baru_diupload = true;
            }
        }
    }

    if ($error === '') {
        $stmt = mysqli_prepare($conn, 'UPDATE galeri SET judul_gambar = ?, file_gambar = ? WHERE id = ?');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssi', $judul_gambar, $nama_file_baru, $id);
            $berhasil = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            $berhasil = false;
        }

        if ($berhasil) {
            if ($file_baru_diupload) {
                $file_lama = __DIR__ . '/../../assets/img/galeri/' . basename($row['file_gambar']);
                if (is_file($file_lama)) {
                    unlink($file_lama);
                }
            }
            header('Location: admin_galeri.php');
            exit;
        }

        if ($file_baru_diupload) {
            unlink($path_file_baru);
        }
        $error = 'Gagal memperbarui data galeri.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Galeri - SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f4f6f9; padding: 40px 20px; margin: 0; }
        .form-container { background: white; max-width: 500px; margin: 0 auto; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { margin: 0 0 20px; font-size: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; font-size: 14px; }
        input[type="text"], input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; }
        .preview { width: 100%; max-height: 240px; object-fit: cover; display: block; margin: 0 0 10px; border-radius: 5px; }
        .error { color: #b02a37; margin: 0 0 15px; font-size: 14px; }
        button { background: #EEB006; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: 500; }
        button:hover { background: #D9A000; }
        .btn-kembali { background: #6c757d; text-decoration: none; color: white; padding: 9px 20px; border-radius: 5px; display: inline-block; font-size: 14px; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Data Galeri</h2>
        <?php if ($error !== ''): ?>
            <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul_gambar">Judul Gambar / Kegiatan</label>
                <input type="text" id="judul_gambar" name="judul_gambar" value="<?= htmlspecialchars($_POST['judul_gambar'] ?? $row['judul_gambar'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="file_gambar">Gambar</label>
                <img src="../../assets/img/galeri/<?= htmlspecialchars($row['file_gambar'], ENT_QUOTES, 'UTF-8'); ?>" class="preview" alt="<?= htmlspecialchars($row['judul_gambar'], ENT_QUOTES, 'UTF-8'); ?>">
                <small>Kosongkan jika tidak ingin mengganti gambar.</small>
                <input type="file" id="file_gambar" name="file_gambar" accept=".jpg,.jpeg,.png,.webp">
            </div>
            <button type="submit" name="submit">Perbarui</button>
            <a href="admin_galeri.php" class="btn-kembali">Kembali</a>
        </form>
    </div>
</body>
</html>
