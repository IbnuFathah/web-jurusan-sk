<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';

if (isset($_POST['submit'])) {$judul_gambar = mysqli_real_escape_string($conn,$_POST['judul_gambar']);
    
    $nama_file =$_FILES['file_gambar']['name'];
    $ukuran_file =$_FILES['file_gambar']['size'];
    $error =$_FILES['file_gambar']['error'];
    $tmp_name =$_FILES['file_gambar']['tmp_name'];

    $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];
    $ekstensi_file = explode('.',$nama_file);
    $ekstensi_file = strtolower(end($ekstensi_file));

    if (!in_array($ekstensi_file,$ekstensi_valid)) {
        echo "<script>alert('Yang Anda pilih bukan file gambar!'); window.location='tambah_galeri.php';</script>";
        return;
    }

    $nama_file_baru = uniqid() . '.' .$ekstensi_file;

    // Gambar disimpan di assets/img/galeri/ (dua tingkat di atas folder ini)
    if (move_uploaded_file($tmp_name, '../../assets/img/galeri/' . $nama_file_baru)) {$query = "INSERT INTO galeri (judul_gambar, file_gambar) VALUES ('$judul_gambar', '$nama_file_baru')";
        if (mysqli_query($conn,$query)) {
            echo "<script>alert('Data galeri berhasil ditambahkan!'); window.location='admin_galeri.php';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan ke database!');</script>";
        }
    } else {
        echo "<script>alert('Gagal mengunggah gambar!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Galeri - SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f6f9; padding: 40px; }
        .form-container { background: white; max-width: 500px; margin: 0 auto; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; font-size: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; font-size: 14px; }
        input[type="text"], input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: 500; }
        button:hover { background: #0056b3; }
        .btn-kembali { background: #6c757d; text-decoration: none; color: white; padding: 9px 20px; border-radius: 5px; display: inline-block; font-size: 14px; margin-left: 10px;}
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Tambah Data Galeri</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Judul Gambar / Kegiatan</label>
                <input type="text" name="judul_gambar" required>
            </div>
            <div class="form-group">
                <label>Pilih File Gambar</label>
                <input type="file" name="file_gambar" required>
            </div>
            <button type="submit" name="submit">Simpan</button>
            <a href="admin_galeri.php" class="btn-kembali">Kembali</a>
        </form>
    </div>
</body>
</html>