<?php
require_once __DIR__ . '/../auth.php';
wajib_admin('../');
require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../helpers.php';

function hitung($conn, $tabel) {
    $r = mysqli_query($conn, "SELECT COUNT(*) AS total FROM $tabel");
    if (!$r) return 0;
    $d = mysqli_fetch_assoc($r);
    return (int) ($d['total'] ?? 0);
}

// Data counts
$count_berita     = hitung($conn, 'berita');
$count_galeri     = hitung($conn, 'galeri');
$count_fasilitas  = hitung($conn, 'fasilitas');
$count_pengaturan = hitung($conn, 'pengaturan_web');
$count_guru       = hitung($conn, 'guru');
$total_data       = $count_guru + $count_berita + $count_galeri + $count_fasilitas + $count_pengaturan;

// Data aktivitas / pembaruan terbaru
$r_berita = mysqli_query($conn, "SELECT judul, tanggal_publikasi FROM berita ORDER BY id DESC LIMIT 1");
$latest_berita = $r_berita ? mysqli_fetch_assoc($r_berita) : null;

$r_galeri = mysqli_query($conn, "SELECT judul_gambar, file_gambar, created_at FROM galeri ORDER BY id DESC LIMIT 1");
$latest_galeri = $r_galeri ? mysqli_fetch_assoc($r_galeri) : null;

$r_fasilitas = mysqli_query($conn, "SELECT nama_fasilitas, jumlah FROM fasilitas ORDER BY id DESC LIMIT 1");
$latest_fasilitas = $r_fasilitas ? mysqli_fetch_assoc($r_fasilitas) : null;

$r_pengaturan = mysqli_query($conn, "SELECT email_sekolah, telepon FROM pengaturan_web ORDER BY id ASC LIMIT 1");
$latest_pengaturan = $r_pengaturan ? mysqli_fetch_assoc($r_pengaturan) : null;

$today_formatted = tanggal_indo(date('Y-m-d'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Admin SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <style>
        :root {
            --adm-bg: #F8F9FA;
            --adm-card: #FFFFFF;
            --adm-gold: #C5A059;
            --adm-gold-hover: #D4AF37;
            --adm-gold-tint: rgba(197, 160, 89, 0.12);
            --adm-charcoal: #18181B;
            --adm-slate-dark: #1E293B;
            --adm-slate-body: #475569;
            --adm-slate-muted: #94A3B8;
            --adm-border: #E2E8F0;
            --adm-shadow: 0 8px 24px -8px rgba(0, 0, 0, 0.05);
            --adm-shadow-hover: 0 12px 28px -6px rgba(0, 0, 0, 0.09);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            background: var(--adm-bg); 
            display: flex; 
            font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif; 
            color: var(--adm-slate-body);
            min-height: 100vh;
        }

        .main-content { 
            /* margin-left diatur oleh sidebar.css (260px / 76px collapsed) */
            flex: 1; 
            min-width: 0; 
            padding: 32px 36px 60px;
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        /* Line Icon Utility */
        .icon-line {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
            flex-shrink: 0;
            display: inline-block;
            vertical-align: middle;
        }

        /* 1. Full-Width Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #18181B 0%, #242429 100%);
            border: 1px solid rgba(197, 160, 89, 0.28);
            border-radius: 16px;
            padding: 30px 36px;
            color: #FFFFFF;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.25);
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            right: -40px;
            top: -40px;
            width: 260px;
            height: 260px;
            background: radial-gradient(circle, rgba(197, 160, 89, 0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .welcome-left {
            position: relative;
            z-index: 2;
        }

        .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(197, 160, 89, 0.16);
            border: 1px solid rgba(197, 160, 89, 0.35);
            color: var(--adm-gold-hover);
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 12px;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            background: #10B981;
            border-radius: 50%;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
        }

        .welcome-title {
            font-size: 26px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
            line-height: 1.25;
        }

        .welcome-sub {
            color: #A1A1AA;
            font-size: 14px;
            max-width: 620px;
            line-height: 1.6;
        }

        .welcome-right {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px;
        }

        .welcome-date {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #D4D4D8;
            background: rgba(255, 255, 255, 0.06);
            padding: 6px 14px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            white-space: nowrap;
        }

        .btn-banner {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--adm-gold);
            color: var(--adm-charcoal);
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-banner:hover {
            background: var(--adm-gold-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(197, 160, 89, 0.35);
        }

        /* 2. Full-Width 4-Column Stat Grid */
        .stat-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            width: 100%;
        }

        .stat-card {
            background: var(--adm-card);
            border: 1px solid var(--adm-border);
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--adm-shadow);
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 148px;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--adm-shadow-hover);
            border-color: rgba(197, 160, 89, 0.5);
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .stat-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--adm-gold-tint);
            color: var(--adm-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .stat-card:hover .stat-icon-wrap {
            background: var(--adm-gold);
            color: #FFFFFF;
        }

        .stat-arrow {
            color: var(--adm-slate-muted);
            font-size: 12px;
            transition: transform 0.2s, color 0.2s;
        }

        .stat-card:hover .stat-arrow {
            color: var(--adm-gold);
            transform: translateX(3px);
        }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: var(--adm-gold);
            line-height: 1;
            margin-bottom: 6px;
            letter-spacing: -0.02em;
        }

        .stat-label {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--adm-slate-dark);
            margin-bottom: 2px;
        }

        .stat-desc {
            font-size: 12px;
            color: var(--adm-slate-muted);
        }

        /* 3. Analytics Chart & Overview Section (2-Column) */
        .analytics-section {
            display: grid;
            grid-template-columns: 1.55fr 1fr;
            gap: 24px;
            width: 100%;
        }

        .content-card {
            background: var(--adm-card);
            border: 1px solid var(--adm-border);
            border-radius: 16px;
            padding: 28px;
            box-shadow: var(--adm-shadow);
            display: flex;
            flex-direction: column;
        }

        .card-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--adm-border);
            gap: 16px;
            flex-wrap: wrap;
        }

        .card-title-group h3 {
            font-size: 18px;
            font-weight: 800;
            color: var(--adm-slate-dark);
            margin-bottom: 4px;
        }

        .card-title-group p {
            font-size: 13px;
            color: var(--adm-slate-muted);
        }

        .chart-legend {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 12.5px;
            color: var(--adm-slate-body);
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .legend-color {
            width: 10px;
            height: 10px;
            border-radius: 3px;
        }

        /* Interactive SVG Bar Chart */
        .chart-container {
            position: relative;
            padding: 10px 0 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .chart-bars-wrap {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 220px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--adm-border);
            position: relative;
        }

        .chart-grid-lines {
            position: absolute;
            inset: 0 0 30px 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            pointer-events: none;
        }

        .grid-line {
            border-bottom: 1px dashed rgba(226, 232, 240, 0.7);
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        .grid-line span {
            font-size: 10px;
            color: #94A3B8;
            margin-right: -24px;
            margin-top: -8px;
        }

        .chart-bar-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 60px;
            position: relative;
            z-index: 2;
            cursor: pointer;
        }

        .bar-tooltip {
            position: absolute;
            top: -34px;
            background: var(--adm-charcoal);
            color: #FFFFFF;
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 6px;
            white-space: nowrap;
            opacity: 0;
            transform: translateY(4px);
            transition: all 0.2s ease;
            pointer-events: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.18);
        }

        .chart-bar-group:hover .bar-tooltip {
            opacity: 1;
            transform: translateY(0);
        }

        .chart-bar-fill {
            width: 38px;
            background: linear-gradient(180deg, var(--adm-gold-hover) 0%, var(--adm-gold) 100%);
            border-radius: 6px 6px 0 0;
            transition: height 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.2s;
            position: relative;
        }

        .chart-bar-group:hover .chart-bar-fill {
            transform: scaleY(1.02);
            filter: brightness(1.1);
        }

        .chart-bar-label {
            position: absolute;
            bottom: -28px;
            font-size: 12px;
            font-weight: 600;
            color: var(--adm-slate-body);
            white-space: nowrap;
            text-align: center;
        }

        .chart-metrics-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            padding-top: 20px;
            margin-top: 10px;
        }

        .metric-pill {
            background: #F8FAFC;
            border: 1px solid var(--adm-border);
            border-radius: 10px;
            padding: 12px 14px;
        }

        .metric-pill small {
            display: block;
            font-size: 11.5px;
            color: var(--adm-slate-muted);
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .metric-pill strong {
            font-size: 16px;
            font-weight: 800;
            color: var(--adm-slate-dark);
        }

        /* Right Column Overview Panels */
        .overview-panel {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px;
            background: #F8FAFC;
            border: 1px solid var(--adm-border);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .activity-item:hover {
            background: #FFFFFF;
            border-color: rgba(197, 160, 89, 0.35);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .activity-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--adm-gold-tint);
            color: var(--adm-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .activity-content {
            flex: 1;
            min-width: 0;
        }

        .activity-title {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--adm-slate-dark);
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .activity-sub {
            font-size: 12px;
            color: var(--adm-slate-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 14px;
        }

        .quick-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 12px;
            background: #F8FAFC;
            border: 1px solid var(--adm-border);
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--adm-slate-dark);
            text-decoration: none;
            transition: all 0.2s ease;
            text-align: center;
        }

        .quick-btn:hover {
            background: var(--adm-gold);
            border-color: var(--adm-gold);
            color: #FFFFFF;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1200px) {
            .stat-grid-4 {
                grid-template-columns: repeat(2, 1fr);
            }
            .analytics-section {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px 16px 40px;
                gap: 20px;
            }
            .welcome-banner {
                flex-direction: column;
                align-items: flex-start;
                padding: 24px 20px;
            }
            .welcome-right {
                align-items: flex-start;
                width: 100%;
            }
            .btn-banner {
                width: 100%;
                justify-content: center;
            }
            .stat-grid-4 {
                grid-template-columns: 1fr;
            }
            .chart-metrics-row {
                grid-template-columns: 1fr;
            }
            .quick-actions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<?php
$root  = '../';
$aktif = 'dashboard';
include __DIR__ . '/sidebar.php';
?>

<main class="main-content">

    <!-- 1. Full-Width Welcome Banner Box -->
    <header class="welcome-banner">
        <div class="welcome-left">
            <!-- <div class="welcome-badge">
                <span class="pulse-dot"></span>
                <span>Pusat Kendali Data Jurusan</span>
            </div> -->
            <h1 class="welcome-title">Selamat Datang, Admin Web SP &amp; SK</h1>
            <p class="welcome-sub">Ringkasan dan pusat kendali data Jurusan Seni Pertunjukan &amp; Seni Karawitan SMKN 1 Kawali.</p>
        </div>
        <div class="welcome-right">
            <div class="welcome-date">
                <svg class="icon-line" style="width: 15px; height: 15px; color: var(--adm-gold);" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                <span><?= e($today_formatted) ?></span>
            </div>
            <a href="../index.php" target="_blank" class="btn-banner">
                <span>Lihat Website Publik</span>
                <svg class="icon-line" viewBox="0 0 24 24"><path d="m5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
    </header>

    <!-- 2. Full-Width 4-Column Stat Grid -->
    <section class="stat-grid-4">
        <!-- Card 1: Data Berita -->
        <a class="stat-card" href="berita/admin_berita.php">
            <div>
                <div class="stat-card-header">
                    <div class="stat-icon-wrap">
                        <svg class="icon-line" viewBox="0 0 24 24"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                    </div>
                    <span class="stat-arrow">Kelola &rarr;</span>
                </div>
                <div class="stat-value"><?= $count_berita ?></div>
                <div class="stat-label">Data Berita</div>
            </div>
            <div class="stat-desc">Artikel prestasi &amp; informasi terkini</div>
        </a>

        <!-- Card 2: Data Galeri -->
        <a class="stat-card" href="galeri/admin_galeri.php">
            <div>
                <div class="stat-card-header">
                    <div class="stat-icon-wrap">
                        <svg class="icon-line" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                    </div>
                    <span class="stat-arrow">Kelola &rarr;</span>
                </div>
                <div class="stat-value"><?= $count_galeri ?></div>
                <div class="stat-label">Data Galeri</div>
            </div>
            <div class="stat-desc">Foto dokumentasi &amp; panggung seni</div>
        </a>

        <!-- Card 3: Data Fasilitas -->
        <a class="stat-card" href="fasilitas/index.php">
            <div>
                <div class="stat-card-header">
                    <div class="stat-icon-wrap">
                        <svg class="icon-line" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    </div>
                    <span class="stat-arrow">Kelola &rarr;</span>
                </div>
                <div class="stat-value"><?= $count_fasilitas ?></div>
                <div class="stat-label">Data Fasilitas</div>
            </div>
            <div class="stat-desc">Instrumen gamelan &amp; sarana praktik</div>
        </a>

        <!-- Card 4: Pengaturan Web -->
        <a class="stat-card" href="pengaturan/index.php">
            <div>
                <div class="stat-card-header">
                    <div class="stat-icon-wrap">
                        <svg class="icon-line" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    </div>
                    <span class="stat-arrow">Kelola &rarr;</span>
                </div>
                <div class="stat-value"><?= $count_pengaturan ?></div>
                <div class="stat-label">Pengaturan Web</div>
            </div>
            <div class="stat-desc">Profil first look &amp; kontak resmi</div>
        </a>
    </section>

    <!-- 3. Analytics Chart & Overview Section -->
    <section class="analytics-section">
        
        <!-- Left Column: Interactive Data Distribution Chart -->
        <div class="content-card">
            <div class="card-header-bar">
                <div class="card-title-group">
                    <h3>Distribusi Konten &amp; Data Master</h3>
                    <p>Perbandingan volume data aktif yang tersimpan pada basis data jurusan.</p>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-color" style="background: var(--adm-gold);"></span>
                        <span>Item Terdaftar</span>
                    </div>
                </div>
            </div>

            <!-- Responsive Chart Canvas -->
            <div class="chart-container">
                <div class="chart-bars-wrap">
                    <!-- Background Grid Lines -->
                    <div class="chart-grid-lines">
                        <div class="grid-line"><span>Max</span></div>
                        <div class="grid-line"><span>75%</span></div>
                        <div class="grid-line"><span>50%</span></div>
                        <div class="grid-line"><span>25%</span></div>
                        <div class="grid-line"><span>0</span></div>
                    </div>

                    <?php
                    $max_val = max(10, $count_guru, $count_berita, $count_galeri, $count_fasilitas, $count_pengaturan);
                    $chart_items = [
                        ['label' => 'Data Guru',   'count' => $count_guru,       'color' => '#C5A059'],
                        ['label' => 'Berita',      'count' => $count_berita,     'color' => '#C5A059'],
                        ['label' => 'Galeri',      'count' => $count_galeri,     'color' => '#C5A059'],
                        ['label' => 'Fasilitas',   'count' => $count_fasilitas,  'color' => '#C5A059'],
                        ['label' => 'Pengaturan',  'count' => $count_pengaturan, 'color' => '#C5A059'],
                    ];
                    ?>

                    <?php foreach ($chart_items as $item) : ?>
                        <?php 
                            $height_pct = max(12, round(($item['count'] / $max_val) * 100));
                        ?>
                        <div class="chart-bar-group">
                            <div class="bar-tooltip"><?= e($item['label']) ?>: <?= $item['count'] ?> Data</div>
                            <div class="chart-bar-fill" style="height: <?= $height_pct ?>%;"></div>
                            <span class="chart-bar-label"><?= e($item['label']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Summary Metrics Below Chart -->
            <div class="chart-metrics-row">
                <div class="metric-pill">
                    <small>Total Master Data</small>
                    <strong><?= $total_data ?> Item Aktif</strong>
                </div>
                <div class="metric-pill">
                    <small>Data Guru Terdaftar</small>
                    <strong><?= $count_guru ?> Tenaga Pendidik</strong>
                </div>
                <div class="metric-pill">
                    <small>Koneksi Basis Data</small>
                    <strong style="color: #10B981; display: flex; align-items: center; gap: 6px;">
                        <span class="pulse-dot"></span>
                        <span>Aktif &amp; Terhubung</span>
                    </strong>
                </div>
            </div>
        </div>

        <!-- Right Column: Recent Activity & Quick Actions -->
        <div class="overview-panel">
            
            <!-- Panel 1: Aktivitas Terkini -->
            <div class="content-card" style="padding: 24px;">
                <div class="card-header-bar" style="margin-bottom: 16px; padding-bottom: 12px;">
                    <div class="card-title-group">
                        <h3 style="font-size: 16px;">Pembaruan Terkini</h3>
                        <p style="font-size: 12px;">Catatan item terakhir pada modul sistem.</p>
                    </div>
                </div>

                <div class="activity-list">
                    <!-- Latest Berita -->
                    <div class="activity-item">
                        <div class="activity-icon">
                            <svg class="icon-line" style="width: 15px; height: 15px;" viewBox="0 0 24 24"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title"><?= $latest_berita ? e($latest_berita['judul']) : 'Belum ada berita' ?></div>
                            <div class="activity-sub">Berita Terakhir &bull; <?= $latest_berita ? tanggal_indo($latest_berita['tanggal_publikasi']) : '-' ?></div>
                        </div>
                    </div>

                    <!-- Latest Galeri -->
                    <div class="activity-item">
                        <div class="activity-icon">
                            <svg class="icon-line" style="width: 15px; height: 15px;" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/></svg>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title"><?= $latest_galeri ? e($latest_galeri['judul_gambar']) : 'Belum ada galeri' ?></div>
                            <div class="activity-sub">Galeri Terakhir &bull; Foto dokumentasi aktif</div>
                        </div>
                    </div>

                    <!-- Latest Fasilitas -->
                    <div class="activity-item">
                        <div class="activity-icon">
                            <svg class="icon-line" style="width: 15px; height: 15px;" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/></svg>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title"><?= $latest_fasilitas ? e($latest_fasilitas['nama_fasilitas']) : 'Belum ada fasilitas' ?></div>
                            <div class="activity-sub">Fasilitas Terakhir &bull; <?= $latest_fasilitas ? e(format_jumlah_fasilitas($latest_fasilitas['jumlah'])) : '-' ?></div>
                        </div>
                    </div>

                    <!-- Pengaturan Kontak -->
                    <div class="activity-item">
                        <div class="activity-icon">
                            <svg class="icon-line" style="width: 15px; height: 15px;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0"/></svg>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Kontak Sekolah Aktif</div>
                            <div class="activity-sub"><?= $latest_pengaturan && !empty($latest_pengaturan['email_sekolah']) ? e($latest_pengaturan['email_sekolah']) : 'Email belum diatur' ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 2: Aksi Cepat (Quick Access) -->
            <div class="content-card" style="padding: 24px;">
                <div class="card-header-bar" style="margin-bottom: 12px; padding-bottom: 12px;">
                    <div class="card-title-group">
                        <h3 style="font-size: 16px;">Langsung Kelola?</h3>
                        <p style="font-size: 12px;">Jalan pintas untuk menambah dan mengelola konten.</p>
                    </div>
                </div>

                <div class="quick-actions-grid">
                    <a href="berita/tambah_berita.php" class="quick-btn">
                        <svg class="icon-line" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                        <span>Tambah Berita</span>
                    </a>
                    <a href="galeri/tambah_galeri.php" class="quick-btn">
                        <svg class="icon-line" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                        <span>Unggah Galeri</span>
                    </a>
                    <a href="fasilitas/tambah.php" class="quick-btn">
                        <svg class="icon-line" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                        <span>Tambah Fasilitas</span>
                    </a>
                    <a href="pengaturan/index.php" class="quick-btn">
                        <svg class="icon-line" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0"/></svg>
                        <span>Edit Kontak &amp; Profil</span>
                    </a>
                </div>
            </div>

        </div>

    </section>

</main>

</body>
</html>
