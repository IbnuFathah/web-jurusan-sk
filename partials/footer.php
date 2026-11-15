<?php
// partials/footer.php — Footer "Kontak Kami" Editorial Minimalist
$kontak = ambil_pengaturan($conn);
$telepon_link = preg_replace('/[^0-9+]/', '', $kontak['telepon'] ?? '');

$sosmed = [
    [
        'col'  => 'link_facebook',
        'name' => 'Facebook',
        'svg'  => '<svg class="icon-svg" style="width:16px;height:16px;" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>'
    ],
    [
        'col'  => 'link_twitter',
        'name' => 'Twitter / X',
        'svg'  => '<svg class="icon-svg" style="width:16px;height:16px;" viewBox="0 0 24 24"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>'
    ],
    [
        'col'  => 'link_instagram',
        'name' => 'Instagram',
        'svg'  => '<svg class="icon-svg" style="width:16px;height:16px;" viewBox="0 0 24 24"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>'
    ],
    [
        'col'  => 'link_linkedin',
        'name' => 'LinkedIn',
        'svg'  => '<svg class="icon-svg" style="width:16px;height:16px;" viewBox="0 0 24 24"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>'
    ],
];
?>
<footer id="kontak">
    <div class="footer-heading-editorial">
        <span class="badge-editorial" style="background: rgba(184, 216, 224, 0.2); color: var(--brand-ice);">Hubungi Kami</span>
        <h2>Kontak Resmi Jurusan</h2>
        <p>SMKN 1 Kawali — Program Keahlian Seni Pertunjukan &amp; Seni Karawitan</p>
    </div>

    <div class="footer-grid">
        <!-- Kolom 1: Profil Singkat & Sosmed -->
        <div class="footer-about">
            <div class="footer-brand">
                <img src="assets/img/logo.png" alt="Logo SMKN 1 Kawali">
                <span>SMKN 1 KAWALI</span>
            </div>
            <p>Mewadahi talenta muda, melestarikan nilai adiluhung seni karawitan, dan membentuk seniman profesional berdaya cipta unggul.</p>
            <div class="footer-sosmed">
                <?php foreach ($sosmed as $sm) : ?>
                    <?php $url = url_aman($kontak[$sm['col']] ?? ''); ?>
                    <?php if ($url !== '') : ?>
                        <a href="<?= e($url) ?>" target="_blank" rel="noopener" aria-label="<?= e($sm['name']) ?>">
                            <?= $sm['svg'] ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Kolom 2: Kontak Sekolah -->
        <div class="footer-col">
            <h4>Informasi Kontak</h4>
            <?php if (!empty($kontak['alamat'])) : ?>
            <div class="footer-contact-item">
                <svg class="icon-svg" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                <a href="https://www.google.com/maps/search/?api=1&amp;query=<?= urlencode($kontak['alamat']) ?>" target="_blank" rel="noopener"><?= e($kontak['alamat']) ?></a>
            </div>
            <?php endif; ?>

            <?php if (!empty($kontak['email_sekolah'])) : ?>
            <div class="footer-contact-item">
                <svg class="icon-svg" viewBox="0 0 24 24"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                <a href="mailto:<?= e($kontak['email_sekolah']) ?>"><?= e($kontak['email_sekolah']) ?></a>
            </div>
            <?php endif; ?>

            <?php if (!empty($kontak['telepon'])) : ?>
            <div class="footer-contact-item">
                <svg class="icon-svg" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <a href="tel:<?= e($telepon_link) ?>"><?= e($kontak['telepon']) ?></a>
            </div>
            <?php endif; ?>

            <?php if (!$kontak) : ?>
            <p style="color: #94A3B8; font-size: 13.5px;">Informasi kontak sekolah belum diatur.</p>
            <?php endif; ?>
        </div>

        <!-- Kolom 3: Navigasi Cepat -->
        <div class="footer-col">
            <h4>Tautan Cepat</h4>
            <div class="footer-links-list">
                <a href="index.php">Beranda</a>
                <a href="profile.php">Profil Jurusan &amp; Guru</a>
                <a href="berita.php">Berita &amp; Prestasi</a>
                <a href="galeri.php">Galeri Kegiatan</a>
                <a href="fasilitas.php">Fasilitas Praktik</a>
                <a href="#kontak">Kontak Kami</a>
            </div>
        </div>
    </div>

    <div class="footer-copy">
        <p>&copy; 2026 SMKN 1 Kawali — Jurusan Seni Pertunjukan &amp; Seni Karawitan. All rights reserved.</p>
    </div>
</footer>
<?php include __DIR__ . '/login_modal.php'; ?>
