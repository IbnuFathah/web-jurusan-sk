<div class="login-overlay" id="loginOverlay" aria-hidden="true">
    <section class="login-modal" role="dialog" aria-modal="true" aria-labelledby="loginModalTitle">
        <button type="button" class="login-modal-close" aria-label="Tutup dialog login">
            <svg class="icon-svg" style="width: 18px; height: 18px;" viewBox="0 0 24 24"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
        </button>
        <a href="index.php" class="auth-brand">
            <img src="assets/img/logo.png" alt="Logo SMKN 1 Kawali">
            <div>
                <strong>SMKN 1 KAWALI</strong>
                <small>JURUSAN SP &amp; SK</small>
            </div>
        </a>
        <h2 id="loginModalTitle">Masuk ke Akun</h2>
        <p class="auth-error login-modal-error" role="alert" hidden></p>
        <form class="login-modal-form" method="POST" action="login.php">
            <label for="loginModalEmail">Alamat Email</label>
            <input type="email" id="loginModalEmail" name="email" placeholder="nama@email.com" required>
            <label for="loginModalPassword">Kata Sandi</label>
            <input type="password" id="loginModalPassword" name="password" placeholder="••••••••" required>
            <button type="submit" name="login">Masuk Sekarang</button>
        </form>
        <p class="auth-foot">Belum memiliki akun? <a href="register.php">Daftar Akun Baru</a></p>
    </section>
</div>
