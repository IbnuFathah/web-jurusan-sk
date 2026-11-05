// --- BAGIAN 1: SCRIPT LAMA ---

// Menu mobile
const menuBtn = document.querySelector('.menu-btn');
const menu = document.getElementById('menu');
if (menuBtn && menu) {
    menuBtn.addEventListener('click', () => menu.classList.toggle('open'));
}

// Lightbox galeri
const lb = document.getElementById('lightbox');
if (lb) {
    document.querySelectorAll('.galeri img').forEach(img => {
        img.addEventListener('click', () => {
            lb.querySelector('img').src = img.src;
            lb.classList.add('show');
        });
    });
    lb.addEventListener('click', () => lb.classList.remove('show'));
    document.addEventListener('keydown', e => { if (e.key === 'Escape') lb.classList.remove('show'); });
}

// Daftar guru
const btnGuru = document.getElementById('btnGuru');
if (btnGuru) {
    const list = document.getElementById('listGuru');
    btnGuru.addEventListener('click', () => {
        const buka = list.classList.toggle('show');
        btnGuru.textContent = buka ? 'Tutup Daftar Guru' : 'Lihat Daftar Guru';
    });
}

// Login modal
const loginOverlay = document.getElementById('loginOverlay');
if (loginOverlay) {
    const loginModal = loginOverlay.querySelector('.login-modal');
    const closeLogin = loginOverlay.querySelector('.login-modal-close');
    const loginForm = loginOverlay.querySelector('.login-modal-form');
    const loginError = loginOverlay.querySelector('.login-modal-error');
    let previousFocus = null;

    const openLogin = event => {
        event.preventDefault();
        previousFocus = menu && menu.classList.contains('open') ? menuBtn : document.activeElement;
        if (menu) menu.classList.remove('open');
        loginOverlay.classList.add('is-open');
        loginOverlay.setAttribute('aria-hidden', 'false');
        document.body.classList.add('login-modal-open');
        loginError.hidden = true;
        loginModal.querySelector('input[type="email"]').focus();
    };

    const dismissLogin = () => {
        loginOverlay.classList.remove('is-open');
        loginOverlay.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('login-modal-open');
        if (previousFocus) previousFocus.focus();
    };

    document.querySelectorAll('.login-modal-trigger').forEach(link => {
        link.addEventListener('click', openLogin);
    });

    closeLogin.addEventListener('click', dismissLogin);
    loginOverlay.addEventListener('click', event => {
        if (event.target === loginOverlay) dismissLogin();
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && loginOverlay.classList.contains('is-open')) dismissLogin();
    });

    loginForm.addEventListener('submit', async event => {
        event.preventDefault();
        loginError.hidden = true;
        const submitButton = loginForm.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        try {
            const formData = new FormData(loginForm);
            formData.append('login', '1');
            const response = await fetch(loginForm.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const result = await response.json();

            if (!response.ok) {
                loginError.textContent = result.error || 'Email atau password salah.';
                loginError.hidden = false;
                return;
            }

            window.location.href = result.redirect;
        } catch (error) {
            loginError.textContent = 'Login gagal diproses. Silakan coba lagi.';
            loginError.hidden = false;
        } finally {
            submitButton.disabled = false;
        }
    });
}
