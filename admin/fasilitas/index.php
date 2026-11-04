<?php
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
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            display: flex;
        }
        .main-content {
            flex: 1;
            padding: 30px;
            box-sizing: border-box;
            background-color: #ffffff;
            min-height: 100vh;
        }
        .main-content h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #222;
        }
        .btn-tambah {
            display: inline-block;
            background-color: #eeb006;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 25px;
            transition: background 0.2s;
        }
        .btn-tambah:hover {
            background-color: #f5bf0f;
        }
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }
        .card-admin {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            position: relative;
        }
        .card-admin img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }
        .card-admin .card-body {
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-admin h3 {
            font-size: 16px;
            margin: 0;
            color: #333;
            font-weight: 600;
        }
        .card-actions {
            display: flex;
            gap: 8px;
        }
        .btn-action {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 13px;
        }
        .btn-edit {
            background-color: #ffc107;
            color: #000;
        }
        .btn-hapus {
            background-color: #dc3545;
            color: #fff;
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 6px 10px;
            border-radius: 50%;
            font-size: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<?php
$root  = '../../';
$aktif = 'fasilitas';
include __DIR__ . '/../sidebar.php';
?>

<main class="main-content">
    <h2>DATA FASILITAS PRAKTIK JURUSAN SP</h2>
    <a href="tambah.php" class="btn-tambah">+ Tambah</a>

    <div class="card-grid">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <div class="card-admin">
            <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-action btn-hapus" onclick="return confirm('Yakin ingin menghapus fasilitas ini?')">&#128465;</a>
            <img src="../../assets/img/fasilitas/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['nama_fasilitas']); ?>">
            <div class="card-body">
                <h3><?= htmlspecialchars($row['nama_fasilitas']); ?></h3>
                <div class="card-actions">
                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn-action btn-edit" title="Edit">&#9998; Edit</a>
                </div>
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