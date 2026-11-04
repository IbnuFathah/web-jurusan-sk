<?php
require_once __DIR__ . '/../../koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM berita ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Berita | Admin SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background: #f8f9fa; display: flex; }
        /* Main Content */
        .main-content { flex: 1; padding: 30px; }
        h2 { margin-bottom: 20px; color: #333; }
        .btn { display: inline-block; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500; cursor: pointer; border: none; text-align: center; }
        .btn-primary { background: #0066ff; color: #fff; margin-bottom: 20px; }
        .btn-warning { background: #ff9800; color: #fff; }
        .btn-danger { background: #f44336; color: #fff; }
        .btn-secondary { background: #b08d57; color: #fff; }
        /* Cards Grid */
        .berita-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between; }
        .card img { width: 100%; height: 180px; object-fit: cover; }
        .card-body { padding: 15px; }
        .card-body h3 { font-size: 16px; margin-bottom: 5px; color: #222; }
        .card-body small { color: #888; display: block; margin-bottom: 10px; }
        .card-body p { font-size: 13px; color: #555; margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .card-footer { padding: 0 15px 15px 15px; display: flex; gap: 8px; flex-wrap: wrap; }
    </style>
</head>
<body>

    <?php
$root  = '../../';
$aktif = 'berita';
include __DIR__ . '/../sidebar.php';
?>

    <!-- Main Container -->
    <div class="main-content">
        <h2>DATA BERITA</h2>
        <a href="tambah_berita.php" class="btn btn-primary">+ Tambah Berita</a>

        <div class="berita-grid">
            <?php while($row = mysqli_fetch_assoc($query)) : ?>
            <article class="card">
                <div>
                    <img src="../../assets/img/berita/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['judul']); ?>">
                    <div class="card-body">
                        <h3><?= htmlspecialchars($row['judul']); ?></h3>
                        <small>📌 <?= htmlspecialchars($row['tanggal_publikasi']); ?></small>
                        <p><?= htmlspecialchars($row['ringkasan']); ?></p>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="../../detailBerita.php?id=<?= $row['id']; ?>" class="btn btn-secondary">Baca Selengkapnya</a>
                    <a href="hapus_berita.php?id=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus berita ini?')">Hapus</a>
                    <a href="edit_berita.php?id=<?= $row['id']; ?>" class="btn btn-warning">Edit</a>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>