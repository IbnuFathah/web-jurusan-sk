<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';
$query = "SELECT * FROM fasilitas ORDER BY id DESC";
$result = mysqli_query($conn,$query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Fasilitas | Admin SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { display: flex; background-color: #f4f6f9; color: #333; }
        .main-content { flex: 1; padding: 30px; }
        .header-title { font-size: 24px; font-weight: 700; margin-bottom: 20px; color: #111; }
        .btn-tambah { display: inline-block; background-color: #EEB006; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: 500; margin-bottom: 25px; transition: 0.3s; }
        .btn-tambah:hover { background-color: #D9A000; }
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .gallery-card { background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); position: relative; }
        .gallery-card img { width: 100%; height: 200px; object-fit: cover; display: block; }
        .gallery-card .card-body { padding: 15px; }
        .gallery-card h3 { font-size: 15px; font-weight: 600; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 5px; }
        .gallery-card p { color: #777; font-size: 13px; }
        .btn-hapus, .btn-edit { position: absolute; top: 10px; color: white; border: none; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.3s; text-decoration: none; background: rgba(0, 0, 0, 0.6); }
        .btn-hapus { right: 10px; }
        .btn-hapus:hover { background: #dc3545; }
        .btn-edit { right: 52px; }
        .btn-edit:hover { background: #EEB006; }
    </style>
</head>
<body>

<?php
$root  = '../../';
$aktif = 'fasilitas';
include __DIR__ . '/../sidebar.php';
?>

<main class="main-content">
    <div class="header-title">DATA FASILITAS PRAKTIK JURUSAN SP</div>
    <a href="tambah.php" class="btn-tambah">+ Tambah</a>

    <div class="gallery-grid">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <div class="gallery-card">
            <a href="edit.php?id=<?= (int) $row['id']; ?>" class="btn-edit" title="Edit" aria-label="Edit fasilitas">&#9998;</a>
            <a href="hapus.php?id=<?= (int) $row['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus fasilitas ini?')" title="Hapus" aria-label="Hapus fasilitas">&#128465;</a>
            <img src="../../assets/img/fasilitas/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['nama_fasilitas']); ?>">
            <div class="card-body">
                <h3><?= htmlspecialchars($row['nama_fasilitas']); ?></h3>
                <p>Jumlah: <?= htmlspecialchars($row['jumlah']); ?> unit</p>
            </div>
        </div>
        <?php endwhile; ?>
        <?php if (mysqli_num_rows($result) == 0) : ?>
            <p style="color: #777;">Belum ada data fasilitas.</p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>