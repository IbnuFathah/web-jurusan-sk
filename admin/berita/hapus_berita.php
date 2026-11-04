<?php
require_once __DIR__ . '/../../koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

// Ambil data gambar untuk dihapus dari folder assets/img/berita/
$result = mysqli_query($conn, "SELECT gambar FROM berita WHERE id = $id");
$row = mysqli_fetch_assoc($result);

if ($row) {
    $file_gambar = "../../assets/img/berita/" . basename($row['gambar']);
    if (is_file($file_gambar)) {
        unlink($file_gambar);
    }

    // Hapus data dari database
    $query = "DELETE FROM berita WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: admin_berita.php");
        exit;
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
} else {
    header("Location: admin_berita.php");
    exit;
}
?>