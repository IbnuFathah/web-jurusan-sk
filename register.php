<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';

if (user_login()) {
    header('Location: index.php');
    exit;
}

$error = '';
$nama  = '';
$email = '';

if (isset($_POST['daftar'])) {
    $nama     = trim($_POST['nama_lengkap'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $ulangi   = $_POST['ulangi_password'] ?? '';

    if ($nama === '' || $email === '' || $password === '') {
        $error = 'Semua kolom wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($password !== $ulangi) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        // role SELALU 'user' — tidak boleh datang dari form
        $stmt = mysqli_prepare($conn, "INSERT INTO users (nama_lengkap, email, password, role) VALUES (?, ?, ?, 'user')");
        mysqli_stmt_bind_param($stmt, 'sss', $nama, $email, $hash);
        try {
            mysqli_stmt_execute($stmt);
            header('Location: login.php?daftar=1');
            exit;
        } catch (mysqli_sql_exception $ex) {
            $error = ($ex->getCode() == 1062) ? 'Email sudah terdaftar.' : 'Pendaftaran gagal, coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar | SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <a href="index.php" class="auth-brand">
            <img src="assets/img/logo.png" alt="Logo SMKN 1 Kawali">
            <div><strong>SMKN 1 KAWALI</strong><small>JURUSAN SP &amp; SK</small></div>
        </a>
        <h2>Daftar Akun</h2>
        <?php if ($error) : ?><p class="auth-error"><?= e($error) ?></p><?php endif; ?>
        <form method="POST" action="">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= e($nama) ?>" required autofocus>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= e($email) ?>" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" minlength="6" required>
            <label for="ulangi_password">Ulangi Password</label>
            <input type="password" id="ulangi_password" name="ulangi_password" minlength="6" required>
            <button type="submit" name="daftar">Daftar</button>
        </form>
        <p class="auth-foot">Sudah punya akun? <a href="login.php">Login</a> &middot; <a href="index.php">Kembali ke beranda</a></p>
    </div>
</body>
</html>
