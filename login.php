<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';

// Sudah login? langsung arahkan sesuai role
if (user_login()) {
    header('Location: ' . (is_admin() ? 'admin/index.php' : 'index.php'));
    exit;
}

$error = '';
$email = '';

if (isset($_POST['login'])) {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($conn, "SELECT id, nama_lengkap, email, password, role FROM users WHERE email = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $u = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($u && password_verify($password, $u['password'])) {
        session_regenerate_id(true);   // cegah session fixation
        $_SESSION['user'] = [
            'id'    => (int) $u['id'],
            'nama'  => $u['nama_lengkap'],
            'email' => $u['email'],
            'role'  => $u['role'],
        ];
        if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['redirect' => $u['role'] === 'admin' ? 'admin/index.php' : 'index.php']);
            exit;
        }
        header('Location: ' . ($u['role'] === 'admin' ? 'admin/index.php' : 'index.php'));
        exit;
    }
    $error = 'Email atau password salah.';
    if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest') {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => $error]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css?v=<?= filemtime(__DIR__ . '/assets/css/auth.css') ?>">
</head>
<body class="auth-page">
    <div class="auth-card">
        <a href="index.php" class="auth-close" aria-label="Tutup dan kembali ke beranda" title="Kembali ke beranda">&times;</a>
        <a href="index.php" class="auth-brand">
            <img src="assets/img/logo.png" alt="Logo SMKN 1 Kawali">
            <div><strong>SMKN 1 KAWALI</strong><small>JURUSAN SP &amp; SK</small></div>
        </a>
        <h2>Login</h2>
        <?php if ($error) : ?><p class="auth-error"><?= e($error) ?></p><?php endif; ?>
        <?php if (isset($_GET['daftar'])) : ?><p class="auth-ok">Pendaftaran berhasil, silakan login.</p><?php endif; ?>
        <form method="POST" action="">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= e($email) ?>" required autofocus>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="login">Masuk</button>
        </form>
        <p class="auth-foot">Belum punya akun? <a href="register.php">Daftar</a></p>
    </div>
</body>
</html>
