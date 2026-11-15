<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $query = "DELETE FROM pengaturan_web WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data pengaturan web berhasil dihapus/direset!'); window.location='index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menghapus data: " . addslashes(mysqli_error($conn)) . "'); window.location='index.php';</script>";
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
