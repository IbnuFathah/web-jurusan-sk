<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';

$id = (int) ($_GET['id'] ?? 0);
$result = mysqli_query($conn, "SELECT * FROM berita WHERE id = $id");
$row = mysqli_fetch_assoc($result);

if (!$row) {
    header("Location: admin_berita.php");
    exit;
}

if (isset($_POST['update'])) {
    $judul             = mysqli_real_escape_string($conn, $_POST['judul']);
    $ringkasan         = mysqli_real_escape_string($conn, $_POST['ringkasan']);
    $konten            = mysqli_real_escape_string($conn, $_POST['konten']);
    $tanggal_publikasi = mysqli_real_escape_string($conn, $_POST['tanggal_publikasi']);

    $gambar_sql  = "";     // diisi hanya kalau user memilih gambar baru
    $gagal_gambar = false;

    if ($_FILES['gambar']['name'] != "") {
        $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];
        $ekstensi       = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

        if (!in_array($ekstensi, $ekstensi_valid)) {
            $error = "Format gambar harus JPG, JPEG, PNG, atau WEBP.";
            $gagal_gambar = true;
        } else {
            $nama_file = uniqid() . '.' . $ekstensi;
            move_uploaded_file($_FILES['gambar']['tmp_name'], "../../assets/img/berita/" . $nama_file);
            $gambar_sql = ", gambar='$nama_file'";
        }
    }

    if (!$gagal_gambar) {
        $query = "UPDATE berita SET judul='$judul', ringkasan='$ringkasan', konten='$konten',
                  tanggal_publikasi='$tanggal_publikasi'$gambar_sql WHERE id=$id";

        if (mysqli_query($conn, $query)) {
            header("Location: admin_berita.php");
            exit;
        } else {
            $error = "Gagal memperbarui data: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Berita | Admin</title>
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
        .btn { background: #ff9800; color: #fff; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; }
        .btn-back { background: #6c757d; text-decoration: none; display: inline-block; padding: 10px 20px; border-radius: 6px; color: #fff; margin-right: 10px; }
        img.preview { width: 100px; margin-top: 5px; display: block; border-radius: 4px; }
        .error { color: red; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Berita</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Judul Berita</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($row['judul']); ?>" required>
            </div>
            <div class="form-group">
                <label>Tanggal Publikasi</label>
                <input type="date" name="tanggal_publikasi" value="<?= htmlspecialchars($row['tanggal_publikasi']); ?>" required>
            </div>
            <div class="form-group">
                <label>Ringkasan Singkat</label>
                <textarea name="ringkasan" required><?= htmlspecialchars($row['ringkasan']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Konten Lengkap</label>
                <textarea name="konten" style="height: 150px;" required><?= htmlspecialchars($row['konten']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Gambar (Biarkan kosong jika tidak ingin diubah)</label>
                <input type="file" name="gambar">
                <img src="../../assets/img/berita/<?= htmlspecialchars($row['gambar']); ?>" class="preview" alt="Preview Gambar">
            </div>
            <a href="admin_berita.php" class="btn-back">Kembali</a>
            <button type="submit" name="update" class="btn">Perbarui Berita</button>
        </form>
    </div>
</body>
</html>