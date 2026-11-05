<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';

if (isset($_POST['submit'])) {
    $nama_fasilitas = mysqli_real_escape_string($conn, $_POST['nama_fasilitas']);
    $jumlah = filter_var($_POST['jumlah'] ?? '', FILTER_VALIDATE_INT);

    if ($jumlah === false || $jumlah < 0) {
        echo "<script>alert('Jumlah harus berupa angka bulat minimal 0.');</script>";
    } else {
        $jumlah = mysqli_real_escape_string($conn, (string) $jumlah);

        // Proses upload gambar
        $nama_file = $_FILES['gambar']['name'];
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
        * { box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f4f6f9; padding: 40px 20px; margin: 0; }
        .form-container { background: white; max-width: 500px; margin: 0 auto; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { margin: 0 0 20px; font-size: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; font-size: 14px; }
        input[type="text"], input[type="number"], input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; font-family: inherit; }
        .btn-submit { background: #EEB006; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: 500; }
        .btn-submit:hover { background: #D9A000; }
        .btn-kembali { background: #6c757d; text-decoration: none; color: white; padding: 9px 20px; border-radius: 5px; display: inline-block; font-size: 14px; margin-left: 10px; }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Tambah Data Fasilitas</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nama_fasilitas">Nama Fasilitas</label>
            <input type="text" id="nama_fasilitas" name="nama_fasilitas" required>
        </div>
        <div class="form-group">
            <label for="jumlah">Jumlah (Unit)</label>
            <input type="number" id="jumlah" name="jumlah" min="0" step="1" value="0" required>
        </div>
        <div class="form-group">
            <label for="gambar">Gambar Fasilitas</label>
            <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.webp" required>
        </div>
        <button type="submit" name="submit" class="btn-submit">Simpan</button>
        <a href="index.php" class="btn-kembali">Kembali</a>
    </form>
</div>
</body>
</html>