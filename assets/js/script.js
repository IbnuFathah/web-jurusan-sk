/**
 * script.js — Editorial Minimalist Public Interactions
 * SMKN 1 Kawali - Jurusan Seni Pertunjukan & Karawitan
 */

document.addEventListener('DOMContentLoaded', () => {

  /* ----------------------------------------------------
   * 1. Mobile Menu Toggle
   * ---------------------------------------------------- */
  const menuBtn = document.querySelector('.menu-btn');
  const menu = document.getElementById('menu');
  if (menuBtn && menu) {
    menuBtn.addEventListener('click', () => {
      menu.classList.toggle('open');
      const isOpen = menu.classList.contains('open');
      menuBtn.setAttribute('aria-expanded', isOpen);
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
      if (!menu.contains(e.target) && !menuBtn.contains(e.target) && menu.classList.contains('open')) {
        menu.classList.remove('open');
        menuBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }

  /* ----------------------------------------------------
   * 2. Lightbox Galeri
   * ---------------------------------------------------- */
  const lb = document.getElementById('lightbox');
  if (lb) {
    const lbImg = lb.querySelector('img');
    document.querySelectorAll('.gallery-grid-editorial img, .galeri img').forEach(img => {
      img.addEventListener('click', () => {
        if (lbImg) lbImg.src = img.src;
        lb.classList.add('show');
        document.body.style.overflow = 'hidden';
      });
    });

    const closeLightbox = () => {
      lb.classList.remove('show');
      document.body.style.overflow = '';
    };

    lb.addEventListener('click', closeLightbox);
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && lb.classList.contains('show')) closeLightbox();
    });
  }

  /* ----------------------------------------------------
   * 3. Teacher Preview Slider (Profile Page)
   * ---------------------------------------------------- */
  const track = document.getElementById('teacherSliderTrack');
  const prevBtn = document.getElementById('teacherSliderPrev');
  const nextBtn = document.getElementById('teacherSliderNext');
  const dotsContainer = document.getElementById('teacherSliderDots');

  if (track && prevBtn && nextBtn) {
    const cards = Array.from(track.querySelectorAll('.teacher-card'));
    let dots = dotsContainer ? Array.from(dotsContainer.querySelectorAll('.slider-dot')) : [];
    let currentIdx = 0;

    function getVisibleCount() {
      if (!cards.length) return 4;
      const containerWidth = track.parentElement.offsetWidth;
      const gap = parseFloat(getComputedStyle(track).gap) || 20;
      const cardWidth = cards[0].offsetWidth;
      return Math.max(1, Math.round((containerWidth + gap) / (cardWidth + gap)));
    }

    function updateSlider() {
      if (!cards.length) return;
      const visibleCount = getVisibleCount();
      const maxIdx = Math.max(0, cards.length - visibleCount);
      const isSlideable = cards.length > visibleCount;

      prevBtn.style.display = isSlideable ? 'inline-flex' : 'none';
      nextBtn.style.display = isSlideable ? 'inline-flex' : 'none';
      if (dotsContainer) {
        dotsContainer.style.display = isSlideable ? 'flex' : 'none';
      }

      if (dotsContainer && isSlideable) {
        const neededDots = maxIdx + 1;
        if (dots.length !== neededDots) {
          dotsContainer.innerHTML = '';
          for (let i = 0; i < neededDots; i++) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'slider-dot';
            btn.setAttribute('aria-label', `Slide ${i + 1}`);
            btn.addEventListener('click', () => goToSlide(i));
            dotsContainer.appendChild(btn);
          }
          dots = Array.from(dotsContainer.querySelectorAll('.slider-dot'));
        }
      }

      currentIdx = Math.max(0, Math.min(currentIdx, maxIdx));

      const gap = parseFloat(getComputedStyle(track).gap) || 20;
      const stepWidth = cards[0].offsetWidth + gap;
      track.style.transform = `translateX(-${currentIdx * stepWidth}px)`;

      prevBtn.disabled = currentIdx === 0;
      nextBtn.disabled = currentIdx >= maxIdx;

      dots.forEach((d, i) => d.classList.toggle('active', i === currentIdx));
    }

    function goToSlide(idx) {
      currentIdx = idx;
      updateSlider();
    }

    prevBtn.addEventListener('click', () => goToSlide(currentIdx - 1));
    nextBtn.addEventListener('click', () => goToSlide(currentIdx + 1));

    updateSlider();
    window.addEventListener('resize', updateSlider);
  }

  /* ----------------------------------------------------
   * 4. Full Teacher Directory Modal
   * ---------------------------------------------------- */
  const btnOpenDir  = document.getElementById('btnOpenTeacherDirectory');
  const dirModal    = document.getElementById('teacherDirectoryModal');
  const btnCloseDir = document.getElementById('btnCloseTeacherDirectory');

  if (btnOpenDir && dirModal) {
    const openDir = () => {
      dirModal.setAttribute('aria-hidden', 'false');
      dirModal.classList.add('is-active');
      document.body.style.overflow = 'hidden';
    };
    const closeDir = () => {
      dirModal.setAttribute('aria-hidden', 'true');
      dirModal.classList.remove('is-active');
      document.body.style.overflow = '';
    };

    btnOpenDir.addEventListener('click', openDir);
    if (btnCloseDir) btnCloseDir.addEventListener('click', closeDir);
    dirModal.addEventListener('click', (e) => { if (e.target === dirModal) closeDir(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && dirModal.classList.contains('is-active')) closeDir(); });
  }

  /* ----------------------------------------------------
   * 5. Teacher Search & Filter (inside Directory Modal)
   * ---------------------------------------------------- */
  const searchInput = document.getElementById('teacherSearchInput');
  const dirGrid     = document.getElementById('teacherDirectoryGrid');
  const dirEmpty    = document.getElementById('teacherDirectoryEmpty');
  const filterChips = document.querySelectorAll('.filter-chip');

  let activeFilter = 'all';

  function filterTeachers() {
    if (!dirGrid) return;
    const q = searchInput ? searchInput.value.toLowerCase() : '';
    const cards = dirGrid.querySelectorAll('.teacher-card');
    let visible = 0;

    cards.forEach(card => {
      const name = (card.dataset.teacherName || '').toLowerCase();
      const role = (card.dataset.teacherRole || '').toLowerCase();
      const matchSearch = q === '' || name.includes(q) || role.includes(q);
      const matchFilter =
        activeFilter === 'all' ||
        (activeFilter === 'wali'    && role.includes('wali')) ||
        (activeFilter === 'pengajar' && role.includes('pengajar')) ||
        (activeFilter === 'ketua'   && role.includes('ketua'));

      const show = matchSearch && matchFilter;
      card.style.display = show ? '' : 'none';
      if (show) visible++;
    });

    if (dirEmpty) dirEmpty.style.display = visible === 0 ? 'flex' : 'none';
  }

  if (searchInput) searchInput.addEventListener('input', filterTeachers);

  filterChips.forEach(chip => {
    chip.addEventListener('click', () => {
      filterChips.forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      activeFilter = chip.dataset.filter || 'all';
      filterTeachers();
    });
  });

  /* ----------------------------------------------------
   * 6. Teacher Detail Modal
   * ---------------------------------------------------- */
  const detailModal    = document.getElementById('teacherDetailModal');
  const modalName      = document.getElementById('modalTeacherName');
  const modalRole      = document.getElementById('modalTeacherRole');
  const btnCloseDetail = document.getElementById('btnCloseTeacherDetail');

  function openDetailModal(name, role) {
    if (!detailModal) return;
    if (modalName) modalName.textContent = name;
    if (modalRole) modalRole.textContent = role;
    detailModal.setAttribute('aria-hidden', 'false');
    detailModal.classList.add('is-active');
    document.body.style.overflow = 'hidden';
  }

  function closeDetailModal() {
    if (!detailModal) return;
    detailModal.setAttribute('aria-hidden', 'true');
    detailModal.classList.remove('is-active');
    if (!dirModal || !dirModal.classList.contains('is-active')) {
      document.body.style.overflow = '';
    }
  }

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-view-teacher');
    if (btn) openDetailModal(btn.dataset.name || '-', btn.dataset.role || '-');
  });

  if (btnCloseDetail) btnCloseDetail.addEventListener('click', closeDetailModal);
  if (detailModal) {
    detailModal.addEventListener('click', (e) => { if (e.target === detailModal) closeDetailModal(); });
  }
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && detailModal && detailModal.classList.contains('is-active')) closeDetailModal();
  });

  /* ----------------------------------------------------
   * 7. Login Modal (if present)
   * ---------------------------------------------------- */
  const loginModalOverlay = document.getElementById('loginModalOverlay');
  const loginModalCard    = document.getElementById('loginModalCard');
  const loginError        = document.getElementById('loginError');
  const loginForm         = document.getElementById('loginForm');

  if (loginModalOverlay) {
    const openModal = () => {
      loginModalOverlay.style.opacity  = '0';
      loginModalOverlay.style.display  = 'flex';
      requestAnimationFrame(() => { loginModalOverlay.style.opacity = '1'; });
      document.body.style.overflow = 'hidden';
    };

    const closeModal = () => {
      loginModalOverlay.style.opacity = '0';
      setTimeout(() => { loginModalOverlay.style.display = 'none'; }, 250);
      document.body.style.overflow = '';
    };

    document.querySelectorAll('.login-modal-trigger').forEach(el => {
      el.addEventListener('click', (e) => { e.preventDefault(); openModal(); });
    });

    loginModalOverlay.addEventListener('click', (e) => {
      if (loginModalCard && !loginModalCard.contains(e.target)) closeModal();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && loginModalOverlay.style.display === 'flex') closeModal();
    });

    if (loginForm) {
      loginForm.addEventListener('submit', async event => {
        event.preventDefault();
        if (loginError) loginError.hidden = true;
        const submitButton = loginForm.querySelector('button[type="submit"]');
        if (submitButton) submitButton.disabled = true;

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
            if (loginError) {
              loginError.textContent = result.error || 'Email atau password salah.';
              loginError.hidden = false;
            }
            return;
          }

          window.location.href = result.redirect;
        } catch (error) {
          if (loginError) {
            loginError.textContent = 'Login gagal diproses. Silakan coba lagi.';
            loginError.hidden = false;
          }
        } finally {
          if (submitButton) submitButton.disabled = false;
        }
      });
    }
  }

});

/* ============================================================
 * LOGOUT CONFIRMATION MODAL — Semua halaman (publik & admin)
 * Intercept semua link yang menuju logout.php
 * ============================================================ */
(function () {
  const modalHTML = `
    <div id="logoutConfirmModal" style="
      position:fixed;inset:0;z-index:99999;
      background:rgba(0,0,0,0.52);
      display:flex;align-items:center;justify-content:center;
      opacity:0;pointer-events:none;
      transition:opacity 0.22s ease;
      backdrop-filter:blur(4px);
    ">
      <div id="logoutConfirmBox" style="
        background:#ffffff;border-radius:20px;
        padding:36px 32px 28px;
        width:min(380px,90vw);text-align:center;
        box-shadow:0 28px 70px -12px rgba(0,0,0,0.30);
        transform:scale(0.92);
        transition:transform 0.22s cubic-bezier(0.16,1,0.3,1);
      ">
        <div style="
          width:60px;height:60px;border-radius:50%;
          background:rgba(184,160,90,0.12);
          border:1.5px solid rgba(184,160,90,0.3);
          display:flex;align-items:center;justify-content:center;
          margin:0 auto 20px;
        ">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none"
               stroke="#b8a05a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
        </div>
        <h3 style="font-size:19px;font-weight:800;color:#1a1a1a;margin-bottom:8px;">Konfirmasi Keluar</h3>
        <p style="font-size:14px;color:#666;line-height:1.65;margin-bottom:28px;">
          Apakah Anda yakin ingin mengakhiri sesi dan keluar dari akun ini?
        </p>
        <div style="display:flex;gap:12px;">
          <button id="logoutCancelBtn" style="
            flex:1;padding:12px;border-radius:11px;
            border:1.5px solid #e2e8f0;background:#f8fafc;
            font-size:14px;font-weight:600;color:#555;cursor:pointer;
            transition:background 0.15s;font-family:inherit;
          ">Batal</button>
          <a id="logoutConfirmBtn" href="#" style="
            flex:1;padding:12px;border-radius:11px;
            background:#b8a05a;color:#fff;
            font-size:14px;font-weight:700;text-decoration:none;
            display:flex;align-items:center;justify-content:center;gap:8px;
            transition:background 0.15s;
          ">
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

  const overlay    = document.getElementById('logoutConfirmModal');
  const box        = document.getElementById('logoutConfirmBox');
  const cancelBtn  = document.getElementById('logoutCancelBtn');
  const confirmBtn = document.getElementById('logoutConfirmBtn');

  function openLogoutModal(href) {
    confirmBtn.href = href;
    overlay.style.pointerEvents = 'all';
    overlay.style.opacity = '1';
    box.style.transform = 'scale(1)';
    document.body.style.overflow = 'hidden';
  }

  function closeLogoutModal() {
    overlay.style.opacity = '0';
    overlay.style.pointerEvents = 'none';
    box.style.transform = 'scale(0.92)';
    document.body.style.overflow = '';
  }

  // Intercept semua link logout di seluruh halaman
  document.addEventListener('click', function (e) {
    const link = e.target.closest('a[href]');
    if (!link) return;
    const href = link.getAttribute('href') || '';
    if (href.includes('logout.php') || href.endsWith('/logout')) {
      e.preventDefault();
      openLogoutModal(link.href);
    }
  });

  cancelBtn.addEventListener('click', closeLogoutModal);
  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) closeLogoutModal();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeLogoutModal();
  });
})();

/* ============================================================
 * SKELETON LOADING SCREEN — Semua halaman (muncul setelah 2s)
 * ============================================================ */
(function () {
  // Inject CSS
  const css = document.createElement('style');
  css.textContent = `
    #skeletonScreen {
      position: fixed;
      inset: 0;
      z-index: 98000;
      background: #f8f9fa;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 22px;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease;
    }
    #skeletonScreen.sk-visible {
      opacity: 1;
      pointer-events: all;
    }
    .sk-logo-circle {
      width: 56px; height: 56px; border-radius: 50%;
    }
    .sk-bar { border-radius: 7px; }
    .sk-logo-circle, .sk-bar {
      background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
      background-size: 300% 100%;
      animation: skShine 1.5s ease-in-out infinite;
    }
    @keyframes skShine {
      0%   { background-position: 100% 0; }
      100% { background-position: -100% 0; }
    }
    .sk-brand-wrap {
      display: flex; flex-direction: column; align-items: center; gap: 10px;
    }
    .sk-content-wrap {
      display: flex; flex-direction: column; gap: 10px;
      width: min(460px, 78vw);
    }
    .sk-footer-label {
      font-size: 12.5px;
      color: #b0bec5;
      font-family: 'Poppins', sans-serif;
      font-weight: 500;
      letter-spacing: 0.04em;
      margin-top: 4px;
    }
  `;
  document.head.appendChild(css);

  // Inject HTML
  const skHTML = `
    <div id="skeletonScreen">
      <div class="sk-brand-wrap">
        <div class="sk-logo-circle"></div>
        <div class="sk-bar" style="width:150px;height:13px;"></div>
        <div class="sk-bar" style="width:100px;height:10px;opacity:.55;"></div>
      </div>
      <div class="sk-content-wrap">
        <div class="sk-bar" style="height:14px;width:100%;"></div>
        <div class="sk-bar" style="height:14px;width:88%;"></div>
        <div class="sk-bar" style="height:14px;width:72%;"></div>
        <div class="sk-bar" style="height:14px;width:55%;opacity:.6;"></div>
      </div>
      <div class="sk-footer-label">Memuat halaman, harap tunggu...</div>
    </div>`;
  document.body.insertAdjacentHTML('beforeend', skHTML);

  const skEl = document.getElementById('skeletonScreen');
  let skTimer = null;

  // Hanya tampilkan jika loading > 2 detik
  if (document.readyState !== 'complete') {
    skTimer = setTimeout(function () {
      if (document.readyState !== 'complete') {
        skEl.classList.add('sk-visible');
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