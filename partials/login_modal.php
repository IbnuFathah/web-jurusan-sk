<div class="login-overlay" id="loginOverlay" aria-hidden="true">
    <section class="login-modal" role="dialog" aria-modal="true" aria-labelledby="loginModalTitle">
        <button type="button" class="login-modal-close" aria-label="Tutup login">&times;</button>
        <a href="index.php" class="auth-brand">
            <img src="assets/img/logo.png" alt="Logo SMKN 1 Kawali">
            <div><strong>SMKN 1 KAWALI</strong><small>JURUSAN SP &amp; SK</small></div>
        </a>
        <h2 id="loginModalTitle">Login</h2>
        <p class="auth-error login-modal-error" role="alert" hidden></p>
        <form class="login-modal-form" method="POST" action="login.php">
            <label for="loginModalEmail">Email</label>
            <input type="email" id="loginModalEmail" name="email" required>
            <label for="loginModalPassword">Password</label>
            <input type="password" id="loginModalPassword" name="password" required>
            <button type="submit" name="login">Masuk</button>
        </form>
        <p class="auth-foot">Belum punya akun? <a href="register.php">Daftar</a></p>
    </section>
</div>
