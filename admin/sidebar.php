<?php
/*
 * admin/sidebar.php — sidebar BERSAMA untuk semua halaman admin.
 *
 * Cara pakai (taruh tepat setelah <body>):
 *
 *     <?php
 *     $root  = '../../';   // path dari halaman ini ke folder utama website
 *     $aktif = 'berita';   // dashboard | guru | berita | galeri | fasilitas | pengaturan
 *     include __DIR__ . '/../sidebar.php';
 *     ?>
 *
 * Di <head> halaman wajib ada:
 *     <link rel="stylesheet" href="<path>/assets/css/sidebar.css">
 */
$root  = $root  ?? '../';
$aktif = $aktif ?? '';
?>
<aside class="sidebar" id="adminSidebar">

  <!-- Header (klik = kembali ke website publik) -->
  <a class="sidebar__header" href="<?= $root ?>index.php" title="Lihat website publik">
    <img class="sidebar__logo" src="<?= $root ?>assets/img/logo.png" alt="Logo SMKN 1 Kawali">
    <div class="sidebar__brand">
      <h1 class="sidebar__title">SMKN 1 KAWALI</h1>
      <p class="sidebar__subtitle">JURUSAN SP &amp; SK</p>
    </div>
  </a>

  <!-- Tombol toggle (<>) -->
  <button class="sidebar__toggle" type="button" aria-label="Toggle minimize sidebar" title="Minimize / Maximize Sidebar">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 7-5 5 5 5"/><path d="m15 7 5 5-5 5"/></svg>
  </button>

  <!-- Menu Navigasi -->
  <nav class="sidebar__nav">

    <p class="sidebar__label">MAIN</p>
    <ul class="sidebar__menu">
      <li>
        <a class="sidebar__link<?= $aktif === 'dashboard' ? ' active' : '' ?>" href="<?= $root ?>admin/index.php" data-tooltip="Dashboard">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a class="sidebar__link<?= $aktif === 'guru' ? ' active' : '' ?>" href="<?= $root ?>admin/guru/index.php" data-tooltip="Data Guru">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span>Data Guru</span>
        </a>
      </li>
      <li>
        <a class="sidebar__link<?= $aktif === 'berita' ? ' active' : '' ?>" href="<?= $root ?>admin/berita/admin_berita.php" data-tooltip="Data Berita">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
          <span>Data Berita</span>
        </a>
      </li>
      <li>
        <a class="sidebar__link<?= $aktif === 'galeri' ? ' active' : '' ?>" href="<?= $root ?>admin/galeri/admin_galeri.php" data-tooltip="Data Galeri">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
          <span>Data Galeri</span>
        </a>
      </li>
      <li>
        <a class="sidebar__link<?= $aktif === 'fasilitas' ? ' active' : '' ?>" href="<?= $root ?>admin/fasilitas/index.php" data-tooltip="Data Fasilitas">
          <svg viewBox="0 0 24 24" aria-hidden="true"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
          <span>Data Fasilitas</span>
        </a>
      </li>
      <li>
        <a class="sidebar__link<?= $aktif === 'pengaturan' ? ' active' : '' ?>" href="<?= $root ?>admin/pengaturan/index.php" data-tooltip="Pengaturan Web">
          <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
          <span>Pengaturan Web</span>
        </a>
      </li>
      <li>
        <a class="sidebar__link" href="<?= $root ?>logout.php" data-tooltip="Keluar">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>
          <span>Keluar</span>
        </a>
      </li>
    </ul>

    <!-- <p class="sidebar__label sidebar__label--spaced">SETTINGS</p>
    <ul class="sidebar__menu">
      <li>
        <a class="sidebar__link" href="#" data-tooltip="Notification">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
          <span>Notification</span>
        </a>
      </li>
      <li>
        <a class="sidebar__link<?= $aktif === 'pengaturan' ? ' active' : '' ?>" href="<?= $root ?>admin/pengaturan/index.php" data-tooltip="Settings">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
          <span>Settings</span>
        </a>
      </li>
      <li>
        <a class="sidebar__link" href="<?= $root ?>logout.php" data-tooltip="Keluar">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>
          <span>Keluar</span>
        </a>
      </li>
    </ul> -->

  </nav>

  <!-- Footer: Light / Dark Toggle
  <div class="sidebar__footer">
    <button class="sidebar__theme" type="button" id="btnThemeLight" title="Mode Terang">
      <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
      <span>Light</span>
    </button>
    <button class="sidebar__theme sidebar__theme--active" type="button" id="btnThemeDark" title="Mode Gelap">
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
      <span>Dark</span>
    </button>
  </div> -->

</aside>

<script>
(function() {
  const sidebar = document.getElementById('adminSidebar');
  const toggleBtn = sidebar ? sidebar.querySelector('.sidebar__toggle') : null;
  const btnLight = document.getElementById('btnThemeLight');
  const btnDark = document.getElementById('btnThemeDark');

  // 1. Inisialisasi status collapse dari localStorage
  if (sidebar && toggleBtn) {
    const isCollapsed = localStorage.getItem('admin_sidebar_collapsed') === 'true';
    if (isCollapsed) {
      sidebar.classList.add('sidebar--collapsed');
      document.body.classList.add('sidebar-collapsed');
    }

    toggleBtn.addEventListener('click', function(e) {
      e.preventDefault();
      const collapsed = sidebar.classList.toggle('sidebar--collapsed');
      document.body.classList.toggle('sidebar-collapsed', collapsed);
      localStorage.setItem('admin_sidebar_collapsed', collapsed ? 'true' : 'false');
    });
  }

  // 2. Inisialisasi tema Light/Dark dari localStorage
  if (btnLight && btnDark) {
    const savedTheme = localStorage.getItem('admin_theme') || 'dark';

    function setTheme(theme) {
      if (theme === 'light') {
        document.body.classList.add('admin-theme-light');
        btnLight.classList.add('sidebar__theme--active');
        btnDark.classList.remove('sidebar__theme--active');
      } else {
        document.body.classList.remove('admin-theme-light');
        btnDark.classList.add('sidebar__theme--active');
        btnLight.classList.remove('sidebar__theme--active');
      }
      localStorage.setItem('admin_theme', theme);
    }

    setTheme(savedTheme);

    btnLight.addEventListener('click', function() { setTheme('light'); });
    btnDark.addEventListener('click', function() { setTheme('dark'); });
  }
})();
</script>
<script src="<?= $root ?>assets/js/admin.js"></script>
