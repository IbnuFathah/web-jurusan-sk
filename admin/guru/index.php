<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';
require_once __DIR__ . '/../../helpers.php';

// Flash message
$pesan   = $_SESSION['pesan']   ?? '';
$berhasil = $_SESSION['berhasil'] ?? false;
unset($_SESSION['pesan'], $_SESSION['berhasil']);

$guru_list = mysqli_query($conn, "SELECT * FROM guru ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Guru | Admin SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <style>
        :root {
            --adm-bg: #F8F9FA;
            --adm-card: #FFFFFF;
            --adm-gold: #C5A059;
            --adm-gold-hover: #D4AF37;
            --adm-gold-tint: rgba(197,160,89,0.12);
            --adm-charcoal: #18181B;
            --adm-slate-dark: #1E293B;
            --adm-slate-body: #475569;
            --adm-slate-muted: #94A3B8;
            --adm-border: #E2E8F0;
            --adm-shadow: 0 8px 24px -8px rgba(0,0,0,0.05);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: var(--adm-bg);
            display: flex;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--adm-slate-body);
            min-height: 100vh;
        }
        .main-content {
            flex: 1;
            min-width: 0;
            padding: 32px 36px 60px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* Page Header */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .page-header-left h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--adm-slate-dark);
            letter-spacing: -0.02em;
        }
        .page-header-left p {
            font-size: 14px;
            color: var(--adm-slate-muted);
            margin-top: 4px;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--adm-gold);
            color: var(--adm-charcoal);
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .btn-primary:hover {
            background: var(--adm-gold-hover);
            box-shadow: 0 4px 14px rgba(197,160,89,0.35);
            transform: translateY(-1px);
        }
        .btn-primary svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }

        /* Alert */
        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid;
        }
        .alert-success { background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.3); color: #065f46; }
        .alert-error   { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.3);  color: #991b1b; }

        /* Content Card */
        .content-card {
            background: var(--adm-card);
            border: 1px solid var(--adm-border);
            border-radius: 16px;
            box-shadow: var(--adm-shadow);
            overflow: hidden;
        }

        /* Table */
        .table-wrap { overflow-x: auto; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        thead tr {
            background: #F8FAFC;
            border-bottom: 1px solid var(--adm-border);
        }
        thead th {
            padding: 14px 18px;
            text-align: left;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--adm-slate-muted);
            white-space: nowrap;
        }
        tbody tr {
            border-bottom: 1px solid #F1F5F9;
            transition: background 0.15s ease;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #F8FAFC; }
        tbody td {
            padding: 14px 18px;
            vertical-align: middle;
            color: var(--adm-slate-dark);
        }

        /* Avatar */
        .guru-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--adm-border);
            background: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }
        .guru-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .avatar-placeholder {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--adm-gold-tint);
            border: 2px solid rgba(197,160,89,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            color: var(--adm-gold);
            flex-shrink: 0;
        }
        .guru-info { display: flex; align-items: center; gap: 12px; }
        .guru-name { font-weight: 600; color: var(--adm-slate-dark); }
        .guru-jabatan {
            display: inline-block;
            background: var(--adm-gold-tint);
            color: #92700b;
            border: 1px solid rgba(197,160,89,0.3);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 10px;
        }
        .row-num {
            font-size: 12px;
            font-weight: 700;
            color: var(--adm-slate-muted);
        }

        /* Action Buttons */
        .actions { display: flex; gap: 8px; }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid;
            transition: all 0.15s ease;
            cursor: pointer;
            background: none;
        }
        .btn-action svg { width: 13px; height: 13px; fill: none; stroke: currentColor; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
        .btn-edit {
            color: var(--adm-gold);
            border-color: rgba(197,160,89,0.4);
            background: rgba(197,160,89,0.06);
        }
        .btn-edit:hover { background: rgba(197,160,89,0.15); border-color: var(--adm-gold); }
        .btn-delete {
            color: #ef4444;
            border-color: rgba(239,68,68,0.3);
            background: rgba(239,68,68,0.04);
        }
        .btn-delete:hover { background: rgba(239,68,68,0.12); border-color: #ef4444; }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-state svg { width: 48px; height: 48px; stroke: var(--adm-slate-muted); fill: none; stroke-width: 1.5; margin-bottom: 16px; }
        .empty-state h3 { font-size: 16px; font-weight: 700; color: var(--adm-slate-dark); margin-bottom: 6px; }
        .empty-state p { font-size: 14px; color: var(--adm-slate-muted); }

        /* Date muted */
        .text-muted { color: var(--adm-slate-muted); font-size: 13px; }

        /* Delete modal */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.5);
            display: flex; align-items: center; justify-content: center;
            z-index: 999; opacity: 0; pointer-events: none;
            transition: opacity 0.2s ease;
        }
        .modal-overlay.open { opacity: 1; pointer-events: all; }
        .modal-box {
            background: #fff;
            border-radius: 16px;
            padding: 32px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px -10px rgba(0,0,0,0.25);
            transform: scale(0.95);
            transition: transform 0.2s ease;
        }
        .modal-overlay.open .modal-box { transform: scale(1); }
        .modal-icon {
            width: 52px; height: 52px;
            background: rgba(239,68,68,0.1);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
        }
        .modal-icon svg { width: 24px; height: 24px; stroke: #ef4444; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .modal-title { font-size: 18px; font-weight: 800; color: var(--adm-slate-dark); margin-bottom: 8px; }
        .modal-desc { font-size: 14px; color: var(--adm-slate-body); line-height: 1.6; margin-bottom: 24px; }
        .modal-actions { display: flex; gap: 12px; }
        .btn-cancel {
            flex: 1; padding: 10px; border-radius: 10px;
            border: 1px solid var(--adm-border); background: none;
            font-size: 14px; font-weight: 600; color: var(--adm-slate-body);
            cursor: pointer; transition: all 0.15s ease;
        }
        .btn-cancel:hover { background: #F8FAFC; }
        .btn-confirm-delete {
            flex: 1; padding: 10px; border-radius: 10px;
            border: none; background: #ef4444;
            font-size: 14px; font-weight: 700; color: #fff;
            cursor: pointer; transition: all 0.15s ease;
        }
        .btn-confirm-delete:hover { background: #dc2626; }
    </style>
</head>
<body>

<?php
$root  = '../../';
$aktif = 'guru';
include __DIR__ . '/../sidebar.php';
?>

<main class="main-content">

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>Data Guru</h1>
            <p>Kelola daftar tenaga pendidik Jurusan SP &amp; SK SMKN 1 Kawali.</p>
        </div>
        <a href="tambah.php" class="btn-primary">
            <svg viewBox="0 0 24 24"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
            Tambah Guru
        </a>
    </div>

    <?php if ($pesan): ?>
    <div class="alert <?= $berhasil ? 'alert-success' : 'alert-error' ?>">
        <?= htmlspecialchars($pesan) ?>
    </div>
    <?php endif; ?>

    <!-- Table Card -->
    <div class="content-card">
        <div class="table-wrap">
            <?php if (mysqli_num_rows($guru_list) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Nama Guru</th>
                        <th>Jabatan / Peran</th>
                        <th>Ditambahkan</th>
                        <th style="width:160px; text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($guru_list)): ?>
                    <tr>
                        <td><span class="row-num"><?= $no++ ?></span></td>
                        <td>
                            <div class="guru-info">
                                <?php if (!empty($row['gambar'])): ?>
                                    <div class="guru-avatar">
                                        <img src="../../assets/img/guru/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_guru']) ?>">
                                    </div>
                                <?php else: ?>
                                    <div class="avatar-placeholder">
                                        <?= mb_strtoupper(mb_substr($row['nama_guru'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <span class="guru-name"><?= htmlspecialchars($row['nama_guru']) ?></span>
                            </div>
                        </td>
                        <td><span class="guru-jabatan"><?= htmlspecialchars($row['jabatan']) ?></span></td>
                        <td><span class="text-muted"><?= tanggal_indo(substr($row['created_at'], 0, 10)) ?></span></td>
                        <td>
                            <div class="actions" style="justify-content:center">
                                <a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn-action btn-edit">
                                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                <button class="btn-action btn-delete"
                                    onclick="confirmDelete(<?= (int)$row['id'] ?>, '<?= htmlspecialchars(addslashes($row['nama_guru'])) ?>')">
                                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <h3>Belum Ada Data Guru</h3>
                <p>Klik tombol "Tambah Guru" untuk menambahkan data guru pertama.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</main>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M9 6V4h6v2"/></svg>
        </div>
        <div class="modal-title">Hapus Data Guru</div>
        <div class="modal-desc" id="modalDesc">Apakah Anda yakin ingin menghapus data guru ini? Tindakan ini tidak dapat dibatalkan.</div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeModal()">Batal</button>
            <form id="deleteForm" method="POST" action="hapus.php" style="flex:1">
                <input type="hidden" name="id" id="deleteId">
                <button type="submit" class="btn-confirm-delete" style="width:100%">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, nama) {
    document.getElementById('deleteId').value = id;
    document.getElementById('modalDesc').textContent = 'Apakah Anda yakin ingin menghapus data guru "' + nama + '"? Tindakan ini tidak dapat dibatalkan.';
    document.getElementById('deleteModal').classList.add('open');
}
function closeModal() {
    document.getElementById('deleteModal').classList.remove('open');
}
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

</body>
</html>
