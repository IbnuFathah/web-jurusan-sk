<?php
require_once __DIR__ . '/../../koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

$query = "SELECT * FROM fasilitas WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $gambar = $row['gambar'];
    if (file_exists('../../assets/img/fasilitas/' . $gambar)) {
        unlink('../../assets/img/fasilitas/' . $gambar);
    }

    $query_delete = "DELETE FROM fasilitas WHERE id = $id";
    if (mysqli_query($conn, $query_delete)) {
        echo "<script>alert('Data fasilitas berhasil dihapus!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.location='index.php';</script>";
    }
} else {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
}
?>