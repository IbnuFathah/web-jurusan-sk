<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$cek_guru = mysqli_query($conn, "SELECT COUNT(*) AS total FROM guru WHERE jabatan_id = $id");
$total_guru = $cek_guru ? (int)mysqli_fetch_assoc($cek_guru)['total'] : 0;

if ($total_guru > 0) {
    $_SESSION['pesan']    = "Jabatan tidak dapat dihapus karena masih digunakan oleh $total_guru guru.";
    $_SESSION['berhasil'] = false;
    header('Location: index.php');
    exit;
}

if (mysqli_query($conn, "DELETE FROM jabatan WHERE id = $id")) {
    $_SESSION['pesan']    = 'Jabatan berhasil dihapus.';
    $_SESSION['berhasil'] = true;
} else {
    $_SESSION['pesan']    = 'Gagal menghapus jabatan: ' . mysqli_error($conn);
    $_SESSION['berhasil'] = false;
}

header('Location: index.php');
exit;