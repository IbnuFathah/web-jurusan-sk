<?php
// partials/footer.php — footer "Kontak Kami" untuk semua halaman publik.
// Alamat, email, telepon & link sosmed diambil dari tabel pengaturan_web.
// Dipanggil dari tiap halaman dengan include (lihat bagian bawah halaman).
$kontak = ambil_pengaturan($conn);
$sosmed = [
    // [kolom di database, warna ikon, nama, huruf ikon]
    ['link_facebook',  '#1877f2', 'Facebook',  'f'],
    ['link_twitter',   '#1da1f2', 'Twitter',   't'],
    ['link_instagram', '#e1306c', 'Instagram', 'ig'],
    ['link_linkedin',  '#0a66c2', 'LinkedIn',  'in'],
];
$telepon_link = preg_replace('/[^0-9+]/', '', $kontak['telepon'] ?? '');
?>
<footer id="kontak">
    <h2>KONTAK KAMI</h2>
    <div class="footer-grid">
        <div class="footer-about">
            <div class="footer-logo"><img src="assets/img/logo.png" alt="Logo"><span>SMKN 1 KAWALI</span></div>
            <p>Jurusan Seni Pertunjukan &amp; Seni Karawitan SMKN 1 Kawali. Mewadahi kreativitas, melestarikan budaya, dan menciptakan seniman muda berbakat.</p>
            <div class="sosmed">
                <?php foreach ($sosmed as [$kolom, $warna, $nama, $huruf]) : ?>
                    <?php $url = url_aman($kontak[$kolom] ?? ''); ?>
                    <?php if ($url !== '') : ?>
                <a href="<?= e($url) ?>" target="_blank" rel="noopener" style="background:<?= $warna ?>" aria-label="<?= $nama ?>"><?= $huruf ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div>
            <h4>Hubungi Kami</h4>
            <?php if (!empty($kontak['alamat'])) : ?>
            <a href="https://www.google.com/maps/search/?api=1&amp;query=<?= urlencode($kontak['alamat']) ?>" target="_blank" rel="noopener"><?= e($kontak['alamat']) ?></a>
            <?php endif; ?>
            <?php if (!empty($kontak['email_sekolah'])) : ?>
            <a href="mailto:<?= e($kontak['email_sekolah']) ?>"><?= e($kontak['email_sekolah']) ?></a>
            <?php endif; ?>
            <?php if (!empty($kontak['telepon'])) : ?>
            <a href="tel:<?= e($telepon_link) ?>"><?= e($kontak['telepon']) ?></a>
            <?php endif; ?>
            <?php if (!$kontak) : ?>
            <a href="#kontak">Informasi kontak belum diisi.</a>
            <?php endif; ?>
        </div>
        <div>
            <h4>Link Cepat</h4>
            <a href="index.php">Beranda</a>
            <a href="profile.php">Profile</a>
            <a href="galeri.php">Galeri</a>
            <a href="berita.php">Berita</a>
            <a href="#kontak">Kontak</a>
        </div>
    </div>
    <p class="copy">&copy; 2026 SMKN 1 Kawali - Jurusan SP &amp; SK. All rights reserved.</p>
</footer>
<?php include __DIR__ . '/login_modal.php'; ?>
