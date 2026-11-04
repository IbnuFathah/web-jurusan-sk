<?php
require_once __DIR__ . '/../../koneksi.php';

if (isset($_POST['submit'])) {
    $nama_fasilitas = mysqli_real_escape_string($conn, $_POST['nama_fasilitas']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);

    // Proses upload gambar
    $nama_file = $_FILES['gambar']['name'];
    $ukuran_file = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmp_file = $_FILES['gambar']['tmp_name'];

    if ($error === 4) {
        echo "<script>alert('Pilih gambar terlebih dahulu!');</script>";
    } else {
        $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];
        $ekstensi_gambar = explode('.', $nama_file);
        $ekstensi_gambar = strtolower(end($ekstensi_gambar));

        if (!in_array($ekstensi_gambar, $ekstensi_valid)) {
            echo "<script>alert('Format file tidak didukung! Gunakan JPG, JPEG, PNG, atau WEBP.');</script>";
        } else {
            $nama_file_baru = uniqid() . '.' . $ekstensi_gambar;
            move_uploaded_file($tmp_file, '../../assets/img/fasilitas/' . $nama_file_baru);

            $query = "INSERT INTO fasilitas (nama_fasilitas, jumlah, gambar) VALUES ('$nama_fasilitas', '$jumlah', '$nama_file_baru')";
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Data fasilitas berhasil ditambahkan!'); window.location='index.php';</script>";
            } else {
                echo "<script>alert('Gagal menambahkan data!');</script>";
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
    <title>Tambah Fasilitas | Admin SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; margin: 0; padding: 40px; }
        .container { max-width: 600px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin: auto; }
        h2 { margin-top: 0; color: #333; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 500; color: #555; }
        input[type="text"], input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        .btn-submit { background-color: #3206f8; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: 500; }
        .btn-submit:hover { background-color: #3106ee; }
        .btn-back { background-color: #6d0bec; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block; margin-left: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Tambah Data Fasilitas</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama Fasilitas</label>
            <input type="text" name="nama_fasilitas" required>
        </div>
        <div class="form-group">
            <label>Jumlah</label>
            <input type="text" name="jumlah" placeholder="Contoh: 5 Unit">
        </div>
        <div class="form-group">
            <label>Gambar Fasilitas</label>
            <input type="file" name="gambar" required>
        </div>
        <button type="submit" name="submit" class="btn-submit">Simpan</button>
        <a href="index.php" class="btn-back">Kembali</a>
    </form>
</div>
</body>
</html>