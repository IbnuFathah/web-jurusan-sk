/**
 * admin.js — Admin Dashboard Interactions
 * SMKN 1 Kawali - Jurusan SP & SK
 * Logout Confirmation + Skeleton Loading Screen (Admin Theme)
 */

/* ============================================================
 * LOGOUT CONFIRMATION MODAL — Admin Sidebar
 * ============================================================ */
(function () {
  const modalHTML = `
    <div id="adminLogoutModal" style="
      position:fixed;inset:0;z-index:99999;
      background:rgba(0,0,0,0.65);
      display:flex;align-items:center;justify-content:center;
      opacity:0;pointer-events:none;
      transition:opacity 0.22s ease;
      backdrop-filter:blur(5px);
    ">
      <div id="adminLogoutBox" style="
        background:#1c1c1f;
        border:1px solid #3f3f46;
        border-radius:20px;
        padding:36px 32px 28px;
        width:min(400px,90vw);text-align:center;
        box-shadow:0 32px 80px -12px rgba(0,0,0,0.6);
        transform:scale(0.92);
        transition:transform 0.22s cubic-bezier(0.16,1,0.3,1);
      ">
        <div style="
          width:62px;height:62px;border-radius:50%;
          background:rgba(197,160,89,0.12);
          border:1.5px solid rgba(197,160,89,0.3);
          display:flex;align-items:center;justify-content:center;
          margin:0 auto 20px;
        ">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
               stroke="#C5A059" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
        </div>
        <h3 style="font-size:19px;font-weight:800;color:#ffffff;margin-bottom:8px;letter-spacing:-0.01em;">
          Konfirmasi Keluar
        </h3>
        <p style="font-size:14px;color:#a1a1aa;line-height:1.65;margin-bottom:28px;">
          Anda akan keluar dari panel admin. Sesi Anda akan diakhiri.<br>Lanjutkan?
        </p>
        <div style="display:flex;gap:12px;">
          <button id="adminLogoutCancelBtn" style="
            flex:1;padding:12px;border-radius:11px;
            border:1.5px solid #3f3f46;background:transparent;
            font-size:14px;font-weight:600;color:#a1a1aa;cursor:pointer;
            transition:background 0.15s,border-color 0.15s;
            font-family:inherit;
          " onmouseover="this.style.background='rgba(255,255,255,0.05)';this.style.borderColor='#71717a';"
             onmouseout="this.style.background='transparent';this.style.borderColor='#3f3f46';">
            Batal
          </button>
          <a id="adminLogoutConfirmBtn" href="#" style="
            flex:1;padding:12px;border-radius:11px;
            background:#C5A059;color:#18181B;
            font-size:14px;font-weight:700;text-decoration:none;
            display:flex;align-items:center;justify-content:center;gap:8px;
            transition:background 0.15s;
          " onmouseover="this.style.background='#D4AF37';"
             onmouseout="this.style.background='#C5A059';">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="16 17 21 12 16 7"/>
              <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Ya, Keluar
          </a>
        </div>
      </div>
    </div>`;

  document.body.insertAdjacentHTML('beforeend', modalHTML);

  const overlay    = document.getElementById('adminLogoutModal');
  const box        = document.getElementById('adminLogoutBox');
  const cancelBtn  = document.getElementById('adminLogoutCancelBtn');
  const confirmBtn = document.getElementById('adminLogoutConfirmBtn');

  function openModal(href) {
    confirmBtn.href = href;
    overlay.style.pointerEvents = 'all';
    overlay.style.opacity = '1';
    box.style.transform = 'scale(1)';
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    overlay.style.opacity = '0';
    overlay.style.pointerEvents = 'none';
    box.style.transform = 'scale(0.92)';
    document.body.style.overflow = '';
  }

  // Intercept semua link logout di halaman admin
  document.addEventListener('click', function (e) {
    const link = e.target.closest('a[href]');
    if (!link) return;
    const href = link.getAttribute('href') || '';
    if (href.includes('logout.php') || href.endsWith('/logout')) {
      e.preventDefault();
      openModal(link.href);
    }
  });

  cancelBtn.addEventListener('click', closeModal);
  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) closeModal();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeModal();
  });
})();

/* ============================================================
 * SKELETON LOADING SCREEN — Admin (tema gelap charcoal)
 * Muncul jika halaman belum selesai load setelah 2 detik
 * ============================================================ */
(function () {
  const css = document.createElement('style');
  css.textContent = `
    #adminSkeletonScreen {
      position: fixed;
      inset: 0;
      z-index: 98000;
      background: #18181b;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      gap: 24px;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease;
    }
    #adminSkeletonScreen.adm-sk-visible {
      opacity: 1;
      pointer-events: all;
    }
    .adm-sk-logo {
      width: 52px; height: 52px; border-radius: 50%;
    }
    .adm-sk-bar { border-radius: 7px; }
    .adm-sk-logo, .adm-sk-bar {
      background: linear-gradient(90deg, #27272a 25%, #3f3f46 50%, #27272a 75%);
      background-size: 300% 100%;
      animation: admSkShine 1.5s ease-in-out infinite;
    }
    @keyframes admSkShine {
      0%   { background-position: 100% 0; }
      100% { background-position: -100% 0; }
    }
    .adm-sk-brand {
      display: flex; flex-direction: column; align-items: center; gap: 10px;
    }
    .adm-sk-content {
      display: flex; flex-direction: column; gap: 10px;
      width: min(400px, 70vw);
    }
    .adm-sk-label {
      font-size: 12.5px;
      color: #52525b;
      font-family: 'Inter', 'Poppins', sans-serif;
      font-weight: 500;
      letter-spacing: 0.04em;
      margin-top: 4px;
    }
    .adm-sk-gold-bar {
      width: 36px; height: 3px; border-radius: 999px;
      background: #C5A059;
      margin: 0 auto;
      opacity: 0.6;
    }
  `;
  document.head.appendChild(css);

  const skHTML = `
    <div id="adminSkeletonScreen">
      <div class="adm-sk-brand">
        <div class="adm-sk-logo"></div>
        <div class="adm-sk-bar" style="width:140px;height:13px;"></div>
        <div class="adm-sk-bar" style="width:90px;height:10px;opacity:.5;"></div>
        <div class="adm-sk-gold-bar"></div>
      </div>
      <div class="adm-sk-content">
        <div class="adm-sk-bar" style="height:13px;width:100%;"></div>
        <div class="adm-sk-bar" style="height:13px;width:82%;"></div>
        <div class="adm-sk-bar" style="height:13px;width:65%;"></div>
        <div class="adm-sk-bar" style="height:13px;width:48%;opacity:.5;"></div>
      </div>
      <div class="adm-sk-label">Memuat panel admin...</div>
    </div>`;
  document.body.insertAdjacentHTML('beforeend', skHTML);

  const skEl = document.getElementById('adminSkeletonScreen');
  let skTimer = null;

  if (document.readyState !== 'complete') {
    skTimer = setTimeout(function () {
      if (document.readyState !== 'complete') {
        skEl.classList.add('adm-sk-visible');
      }
    }, 2000);
  }

  window.addEventListener('load', function () {
    clearTimeout(skTimer);
    skEl.style.opacity = '0';
    skEl.style.pointerEvents = 'none';
    setTimeout(function () {
      if (skEl && skEl.parentNode) skEl.remove();
    }, 400);
  });
})();
