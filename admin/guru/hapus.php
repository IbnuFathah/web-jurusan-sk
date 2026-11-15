<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';

$id = (int)($_POST['id'] ?? 0);
if (!$id) {
    $_SESSION['pesan']    = 'Permintaan tidak valid.';
    $_SESSION['berhasil'] = false;
    header('Location: index.php');
    exit;
}

// Ambil data guru dulu (untuk hapus foto lama)
$res = mysqli_query($conn, "SELECT gambar, nama_guru FROM guru WHERE id = $id LIMIT 1");
if (!$res || mysqli_num_rows($res) === 0) {
    $_SESSION['pesan']    = 'Data guru tidak ditemukan.';
    $_SESSION['berhasil'] = false;
    header('Location: index.php');
    exit;
}
$guru = mysqli_fetch_assoc($res);

// Hapus file foto jika ada
if (!empty($guru['gambar'])) {
    $foto_path = __DIR__ . '/../../assets/img/guru/' . $guru['gambar'];
    if (file_exists($foto_path)) {
        @unlink($foto_path);
    }
}

// Hapus record dari database
if (mysqli_query($conn, "DELETE FROM guru WHERE id = $id")) {
    $_SESSION['pesan']    = "Data guru \"{$guru['nama_guru']}\" berhasil dihapus.";
    $_SESSION['berhasil'] = true;
} else {
    $_SESSION['pesan']    = 'Gagal menghapus data: ' . mysqli_error($conn);
    $_SESSION['berhasil'] = false;
}

header('Location: index.php');
exit;
