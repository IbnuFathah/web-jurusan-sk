<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';

$id = (int) ($_GET['id'] ?? 0);
$query = "SELECT * FROM fasilitas WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
}

if (isset($_POST['submit'])) {
    $nama_fasilitas = mysqli_real_escape_string($conn, $_POST['nama_fasilitas']);
    $jumlah = filter_var($_POST['jumlah'] ?? '', FILTER_VALIDATE_INT);
    if ($jumlah === false || $jumlah < 0) {
        echo "<script>alert('Jumlah harus berupa angka bulat minimal 0.'); history.back();</script>";
        exit;
    }
    $jumlah = mysqli_real_escape_string($conn, (string) $jumlah);
    $gambar_lama = basename($_POST['gambar_lama']);

    if ($_FILES['gambar']['error'] === 4) {
        $nama_file_baru = $gambar_lama;
    } else {
        $nama_file = $_FILES['gambar']['name'];
        $tmp_file = $_FILES['gambar']['tmp_name'];
        $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];
        $ekstensi_gambar = explode('.', $nama_file);
        $ekstensi_gambar = strtolower(end($ekstensi_gambar));

        if (!in_array($ekstensi_gambar, $ekstensi_valid)) {
            echo "<script>alert('Format file tidak didukung!');</script>";
            return;
        }

        $nama_file_baru = uniqid() . '.' . $ekstensi_gambar;
        move_uploaded_file($tmp_file, '../../assets/img/fasilitas/' . $nama_file_baru);

        if (file_exists('../../assets/img/fasilitas/' . $gambar_lama)) {
            unlink('../../assets/img/fasilitas/' . $gambar_lama);
        }
    }

    $query_update = "UPDATE fasilitas SET nama_fasilitas = '$nama_fasilitas', jumlah = '$jumlah', gambar = '$nama_file_baru' WHERE id = $id";
    if (mysqli_query($conn, $query_update)) {
        echo "<script>alert('Data fasilitas berhasil diubah!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Fasilitas | Admin SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; margin: 0; padding: 40px; }
        .container { max-width: 600px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin: auto; }
        h2 { margin-top: 0; color: #333; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 500; color: #555; }
        input[type="text"], input[type="number"], input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-family: 'Poppins', sans-serif; font-size: 15px; }
        input[type="number"] { appearance: textfield; }
        .btn-submit { background-color: #EEB006; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: 500; }
        .btn-submit:hover { background-color: #D9A000; }
        .btn-back { background-color: #6c757d; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block; margin-left: 10px; }
        img.preview { width: 100px; margin-top: 10px; display: block; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Data Fasilitas</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="gambar_lama" value="<?= $row['gambar']; ?>">
        <?php preg_match('/\d+/', (string) $row['jumlah'], $jumlah_angka); ?>
        <div class="form-group">
            <label>Nama Fasilitas</label>
            <input type="text" name="nama_fasilitas" value="<?= htmlspecialchars($row['nama_fasilitas']); ?>" required>
        </div>
        <div class="form-group">
            <label for="jumlah">Jumlah (Unit)</label>
            <input type="number" id="jumlah" name="jumlah" min="0" step="1" value="<?= htmlspecialchars($jumlah_angka[0] ?? '0'); ?>" required>
        </div>
        <div class="form-group">
            <label>Gambar Fasilitas</label>
            <img src="../../assets/img/fasilitas/<?= htmlspecialchars($row['gambar']); ?>" class="preview" alt="Preview">
            <small style="color: #777;">Biarkan kosong jika tidak ingin mengubah gambar.</small>
            <input type="file" name="gambar" style="margin-top: 8px;">
        </div>
        <button type="submit" name="submit" class="btn-submit">Perbarui</button>
        <a href="index.php" class="btn-back">Kembali</a>
    </form>
</div>
</body>
</html>