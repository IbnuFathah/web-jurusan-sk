<?php
require_once __DIR__ . '/../../koneksi.php';

if (isset($_POST['submit'])) {
    $judul             = mysqli_real_escape_string($conn, $_POST['judul']);
    $ringkasan         = mysqli_real_escape_string($conn, $_POST['ringkasan']);
    $konten            = mysqli_real_escape_string($conn, $_POST['konten']);
    $tanggal_publikasi = mysqli_real_escape_string($conn, $_POST['tanggal_publikasi']);

    // Upload gambar ke folder assets/img/berita/
    $nama_file  = $_FILES['gambar']['name'];
    $tmp_file   = $_FILES['gambar']['tmp_name'];
    $dir        = "../../assets/img/berita/";

    // Hanya terima file gambar (mencegah upload file .php)
    $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];
    $ekstensi       = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

    if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
        $error = "Pilih gambar terlebih dahulu.";
    } elseif (!in_array($ekstensi, $ekstensi_valid)) {
        $error = "Format gambar harus JPG, JPEG, PNG, atau WEBP.";
    } else {
        $nama_file_baru = uniqid() . '.' . $ekstensi;

        if (move_uploaded_file($tmp_file, $dir . $nama_file_baru)) {
            $query = "INSERT INTO berita (judul, ringkasan, konten, gambar, tanggal_publikasi)
                      VALUES ('$judul', '$ringkasan', '$konten', '$nama_file_baru', '$tanggal_publikasi')";

            if (mysqli_query($conn, $query)) {
                header("Location: admin_berita.php");
                exit;
            } else {
                $error = "Gagal menyimpan data ke database: " . mysqli_error($conn);
            }
        } else {
            $error = "Gagal mengunggah gambar.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Berita | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background: #f8f9fa; padding: 30px; }
        .form-container { max-width: 600px; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin: auto; }
        h2 { margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; color: #444; }
        input[type="text"], input[type="date"], textarea, input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; }
        textarea { height: 100px; resize: vertical; }
        .btn { background: #0066ff; color: #fff; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; }
        .btn-back { background: #6c757d; text-decoration: none; display: inline-block; padding: 10px 20px; border-radius: 6px; color: #fff; margin-right: 10px; }
        .error { color: red; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Tambah Berita Baru</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Judul Berita</label>
                <input type="text" name="judul" required>
            </div>
            <div class="form-group">
                <label>Tanggal Publikasi</label>
                <input type="date" name="tanggal_publikasi" required>
            </div>
            <div class="form-group">
                <label>Ringkasan Singkat</label>
                <textarea name="ringkasan" required></textarea>
            </div>
            <div class="form-group">
                <label>Konten Lengkap</label>
                <textarea name="konten" style="height: 150px;" required></textarea>
            </div>
            <div class="form-group">
                <label>Gambar Berita</label>
                <input type="file" name="gambar" required>
            </div>
            <a href="admin_berita.php" class="btn-back">Kembali</a>
            <button type="submit" name="submit" class="btn">Simpan Berita</button>
        </form>
    </div>
</body>
</html>