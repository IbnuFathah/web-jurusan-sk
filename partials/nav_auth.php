<?php
// partials/nav_auth.php — menu Login / Keluar di navbar halaman publik.
// Dipanggil dari dalam <nav> di halaman yang ada di folder utama.
$u = user_login();
if ($u) :
    $nama_depan = strtok($u['nama'], ' ');
    if ($u['role'] === 'admin') : ?>
        <a href="admin/index.php">Admin</a>
    <?php endif; ?>
        <a href="logout.php">Keluar (<?= e($nama_depan) ?>)</a>
<?php else : ?>
        <a href="login.php" class="login-modal-trigger">Login</a>
<?php endif; ?>
