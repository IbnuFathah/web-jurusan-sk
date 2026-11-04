<?php
// koneksi.php — SATU-SATUNYA file koneksi database untuk seluruh website.
// Semua halaman (publik & admin) cukup memanggil file ini.
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_senikarawitan";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
?>
