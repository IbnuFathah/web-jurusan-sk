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

  if (track) {
    const cards = track.querySelectorAll('.teacher-card');
    const dots = dotsContainer ? dotsContainer.querySelectorAll('.slider-dot') : [];

    const updateControls = () => {
      const scrollLeft = track.scrollLeft;
      const maxScroll = track.scrollWidth - track.clientWidth;
      
      if (prevBtn) prevBtn.disabled = scrollLeft <= 4;
      if (nextBtn) nextBtn.disabled = scrollLeft >= maxScroll - 4;

      // Update active dot
      if (cards.length > 0 && dots.length > 0) {
        const cardWidth = cards[0].offsetWidth + 20; // 20px gap
        const activeIdx = Math.round(scrollLeft / cardWidth);
        dots.forEach((dot, idx) => {
          dot.classList.toggle('active', idx === activeIdx);
        });
      }
    };

    if (prevBtn && nextBtn) {
      prevBtn.addEventListener('click', () => {
        const cardWidth = cards[0] ? cards[0].offsetWidth + 20 : 260;
        track.scrollBy({ left: -cardWidth, behavior: 'smooth' });
      });

      nextBtn.addEventListener('click', () => {
        const cardWidth = cards[0] ? cards[0].offsetWidth + 20 : 260;
        track.scrollBy({ left: cardWidth, behavior: 'smooth' });
      });

      track.addEventListener('scroll', updateControls, { passive: true });
      window.addEventListener('resize', updateControls);
      updateControls();
    }

    if (dots.length > 0) {
      dots.forEach((dot) => {
        dot.addEventListener('click', () => {
          const idx = parseInt(dot.getAttribute('data-index') || '0', 10);
          const cardWidth = cards[0] ? cards[0].offsetWidth + 20 : 260;
          track.scrollTo({ left: idx * cardWidth, behavior: 'smooth' });
        });
      });
    }
  }

  /* ----------------------------------------------------
   * 4. Full Teacher Directory Modal & Filter
   * ---------------------------------------------------- */
  const openDirectoryBtn = document.getElementById('btnOpenTeacherDirectory');
  const directoryModal = document.getElementById('teacherDirectoryModal');
  const closeDirectoryBtn = document.getElementById('btnCloseTeacherDirectory');
  const searchInput = document.getElementById('teacherSearchInput');
  const filterChips = document.querySelectorAll('.filter-chip');
  const directoryGrid = document.getElementById('teacherDirectoryGrid');
  const directoryEmpty = document.getElementById('teacherDirectoryEmpty');

  const openDirectory = () => {
    if (!directoryModal) return;
    directoryModal.classList.add('is-active');
    directoryModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    if (searchInput) {
      searchInput.value = '';
      setTimeout(() => searchInput.focus(), 100);
    }
    filterTeachers();
  };

  const closeDirectory = () => {
    if (!directoryModal) return;
    directoryModal.classList.remove('is-active');
    directoryModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  };

  if (openDirectoryBtn && directoryModal) {
    openDirectoryBtn.addEventListener('click', openDirectory);
  }

  if (closeDirectoryBtn && directoryModal) {
    closeDirectoryBtn.addEventListener('click', closeDirectory);
  }

  if (directoryModal) {
    directoryModal.addEventListener('click', (e) => {
      if (e.target === directoryModal) closeDirectory();
    });
  }

  // Filter & Search Logic
  function filterTeachers() {
    if (!directoryGrid) return;
    const cards = directoryGrid.querySelectorAll('.teacher-card');
    const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const activeChip = document.querySelector('.filter-chip.active');
    const filterType = activeChip ? activeChip.getAttribute('data-filter') : 'all';

    let matchCount = 0;

    cards.forEach(card => {
      const name = (card.getAttribute('data-teacher-name') || '').toLowerCase();
      const role = (card.getAttribute('data-teacher-role') || '').toLowerCase();

      // Check search match
      const matchesSearch = query === '' || name.includes(query) || role.includes(query);

      // Check category match
      let matchesCategory = true;
      if (filterType === 'wali') {
        matchesCategory = role.includes('wali');
      } else if (filterType === 'pengajar') {
        matchesCategory = role.includes('pengajar');
      } else if (filterType === 'ketua') {
        matchesCategory = role.includes('ketua');
      }

      const isVisible = matchesSearch && matchesCategory;
      card.style.display = isVisible ? '' : 'none';
      if (isVisible) matchCount++;
    });

    if (directoryEmpty) {
      directoryEmpty.style.display = matchCount === 0 ? 'block' : 'none';
    }
  }

  if (searchInput) {
    searchInput.addEventListener('input', filterTeachers);
  }

  if (filterChips.length > 0) {
    filterChips.forEach(chip => {
      chip.addEventListener('click', () => {
        filterChips.forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
        filterTeachers();
      });
    });
  }

  /* ----------------------------------------------------
   * 5. Teacher Detail Modal
   * ---------------------------------------------------- */
  const teacherDetailModal = document.getElementById('teacherDetailModal');
  const closeTeacherDetailBtn = document.getElementById('btnCloseTeacherDetail');
  const modalTeacherName = document.getElementById('modalTeacherName');
  const modalTeacherRole = document.getElementById('modalTeacherRole');

  const openTeacherDetail = (name, role) => {
    if (!teacherDetailModal) return;
    if (modalTeacherName) modalTeacherName.textContent = name;
    if (modalTeacherRole) modalTeacherRole.textContent = role;
    teacherDetailModal.classList.add('is-active');
    teacherDetailModal.setAttribute('aria-hidden', 'false');
  };

  const closeTeacherDetail = () => {
    if (!teacherDetailModal) return;
    teacherDetailModal.classList.remove('is-active');
    teacherDetailModal.setAttribute('aria-hidden', 'true');
  };

  // Bind to all "Lihat Profil" buttons on cards
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-view-teacher');
    if (btn) {
      e.preventDefault();
      const name = btn.getAttribute('data-name') || '';
      const role = btn.getAttribute('data-role') || '';
      openTeacherDetail(name, role);
    }
  });

  if (closeTeacherDetailBtn && teacherDetailModal) {
    closeTeacherDetailBtn.addEventListener('click', closeTeacherDetail);
  }

  if (teacherDetailModal) {
    teacherDetailModal.addEventListener('click', (e) => {
      if (e.target === teacherDetailModal) closeTeacherDetail();
    });
  }

  // Global ESC key handler for modals
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      if (teacherDetailModal && teacherDetailModal.classList.contains('is-active')) {
        closeTeacherDetail();
      } else if (directoryModal && directoryModal.classList.contains('is-active')) {
        closeDirectory();
      }
    }
  });

  /* ----------------------------------------------------
   * 6. Login Modal Handler
   * ---------------------------------------------------- */
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
      if (loginError) loginError.hidden = true;
      const emailInput = loginModal.querySelector('input[type="email"]');
      if (emailInput) emailInput.focus();
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

    if (closeLogin) closeLogin.addEventListener('click', dismissLogin);
    loginOverlay.addEventListener('click', event => {
      if (event.target === loginOverlay) dismissLogin();
    });
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape' && loginOverlay.classList.contains('is-open')) dismissLogin();
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
