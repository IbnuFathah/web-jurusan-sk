<?php
/*
 * admin/sidebar.php — sidebar BERSAMA untuk semua halaman admin.
 *
 * Cara pakai (taruh tepat setelah <body>):
 *
 *     <?php
 *     $root  = '../../';   // path dari halaman ini ke folder utama website
 *     $aktif = 'berita';   // dashboard | guru | berita | galeri | fasilitas
 *     include __DIR__ . '/../sidebar.php';
 *     ?>
 *
 * Halaman di admin/berita, admin/galeri, admin/fasilitas -> $root = '../../'
 * Halaman di admin/ (contoh admin/index.php)              -> $root = '../'
 *
 * Di <head> halaman juga wajib ada:
 *     <link rel="stylesheet" href="<path>/assets/css/sidebar.css">
 */
$root  = $root  ?? '../';
$aktif = $aktif ?? '';
?>
<aside class="sidebar">

  <!-- Header (klik = kembali ke website publik) -->
  <a class="sidebar__header" href="<?= $root ?>index.php" title="Lihat website">
    <img class="sidebar__logo" src="<?= $root ?>assets/img/logo.png" alt="Logo SMKN 1 Kawali">
    <div class="sidebar__brand">
      <h1 class="sidebar__title">SMKN 1 KAWALI</h1>
      <p class="sidebar__subtitle">JURUSAN SP &amp; SK</p>
    </div>
  </a>

  <!-- Tombol toggle -->
  <button class="sidebar__toggle" type="button" aria-label="Toggle sidebar">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 7-5 5 5 5"/><path d="m15 7 5 5-5 5"/></svg>
  </button>

  <!-- Menu -->
  <nav class="sidebar__nav">

    <p class="sidebar__label">MAIN</p>
    <ul class="sidebar__menu">
      <li>
        <a class="sidebar__link<?= $aktif === 'dashboard' ? ' active' : '' ?>" href="<?= $root ?>admin/index.php">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <!-- TODO: ganti "#" dengan halaman Data Guru kalau sudah dibuat -->
        <a class="sidebar__link<?= $aktif === 'guru' ? ' active' : '' ?>" href="#">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span>Data Guru</span>
        </a>
      </li>
      <li>
        <a class="sidebar__link<?= $aktif === 'berita' ? ' active' : '' ?>" href="<?= $root ?>admin/berita/admin_berita.php">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
          <span>Data Berita</span>
        </a>
      </li>
      <li>
        <a class="sidebar__link<?= $aktif === 'galeri' ? ' active' : '' ?>" href="<?= $root ?>admin/galeri/admin_galeri.php">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
          <span>Data Galeri</span>
        </a>
      </li>
      <li>
        <a class="sidebar__link<?= $aktif === 'fasilitas' ? ' active' : '' ?>" href="<?= $root ?>admin/fasilitas/index.php">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
          <span>Data Fasilitas</span>
        </a>
      </li>
    </ul>

    <p class="sidebar__label sidebar__label--spaced">SETTINGS</p>
    <ul class="sidebar__menu">
      <li>
        <!-- TODO: halaman Notification belum ada -->
        <a class="sidebar__link" href="#">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
          <span>Notification</span>
        </a>
      </li>
      <li>
        <!-- TODO: halaman Settings belum ada -->
        <a class="sidebar__link" href="#">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
          <span>Settings</span>
          <svg class="sidebar__chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
        </a>
      </li>
    </ul>

  </nav>

  <!-- Footer: Light / Dark (tampilan saja, belum ada fungsinya) -->
  <div class="sidebar__footer">
    <button class="sidebar__theme" type="button">
      <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
      <span>Light</span>
    </button>
    <button class="sidebar__theme sidebar__theme--active" type="button">
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
      <span>Dark</span>
    </button>
  </div>

</aside>
