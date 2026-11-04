<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

$result = mysqli_query($conn, "SELECT * FROM galeri WHERE id = $id");
$data = mysqli_fetch_assoc($result);

// Hapus file fisik di assets/img/galeri/
if ($data) {
    $file = "../../assets/img/galeri/" . basename($data['file_gambar']);
    if (is_file($file)) {
        unlink($file);
    }
}

$query = mysqli_query($conn, "DELETE FROM galeri WHERE id = $id");

if ($query) {
    echo "<script>alert('Data galeri berhasil dihapus!'); window.location='admin_galeri.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data!'); window.location='admin_galeri.php';</script>";
}
?>