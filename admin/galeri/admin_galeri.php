<?php
require_once __DIR__ . '/../../koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Galeri - Admin SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { display: flex; background-color: #f4f6f9; color: #333; }
        .main-content { flex: 1; padding: 30px; }
        .header-title { font-size: 24px; font-weight: 700; margin-bottom: 20px; color: #111; }
        .btn-tambah { display: inline-block; background-color: #007bff; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: 500; margin-bottom: 25px; transition: 0.3s; }
        .btn-tambah:hover { background-color: #0056b3; }
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .gallery-card { background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); position: relative; }
        .gallery-card img { width: 100%; height: 200px; object-fit: cover; display: block; }
        .gallery-card .card-body { padding: 15px; }
        .gallery-card h4 { font-size: 15px; font-weight: 600; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .btn-hapus { position: absolute; top: 10px; right: 10px; background: rgba(0, 0, 0, 0.6); color: white; border: none; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.3s; text-decoration: none; }
        .btn-hapus:hover { background: #dc3545; }
    </style>
</head>
<body>

<?php
$root  = '../../';
$aktif = 'galeri';
include __DIR__ . '/../sidebar.php';
?>

    <div class="main-content">
        <div class="header-title">DATA GALERI JURUSAN SENI KARAWITAN</div>
        <a href="tambah_galeri.php" class="btn-tambah">+ Tambah</a>

        <div class="gallery-grid">
            <?php
            $query = mysqli_query($conn, "SELECT * FROM galeri ORDER BY id DESC");
            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
            ?>
                <div class="gallery-card">
                    <a href="hapus_galeri.php?id=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus gambar ini?');" title="Hapus">🗑</a>
                    <img src="../../assets/img/galeri/<?= htmlspecialchars($row['file_gambar']); ?>" alt="<?= htmlspecialchars($row['judul_gambar']); ?>">
                    <div class="card-body">
                        <h4><?= htmlspecialchars($row['judul_gambar']); ?></h4>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo "<p style='color: #777;'>Belum ada data galeri.</p>";
            }
            ?>
        </div>
    </div>

</body>
</html>