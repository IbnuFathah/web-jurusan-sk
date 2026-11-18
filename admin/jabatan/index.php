<?php
require_once __DIR__ . '/../../auth.php';
wajib_admin('../../');
require_once __DIR__ . '/../../koneksi.php';
require_once __DIR__ . '/../../helpers.php';

$pesan    = $_SESSION['pesan']    ?? '';
$berhasil = $_SESSION['berhasil'] ?? false;
unset($_SESSION['pesan'], $_SESSION['berhasil']);

$jabatan_list = mysqli_query($conn, "
    SELECT j.*, COUNT(g.id) AS jumlah_guru
    FROM jabatan j
    LEFT JOIN guru g ON g.jabatan_id = j.id
    GROUP BY j.id
    ORDER BY j.id ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Jabatan | Admin SMKN 1 Kawali</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <style>
        :root {
            --adm-bg: #F8F9FA; --adm-card: #FFFFFF;
            --adm-gold: #C5A059; --adm-gold-hover: #D4AF37;
            --adm-gold-tint: rgba(197,160,89,0.12);
            --adm-charcoal: #18181B; --adm-slate-dark: #1E293B;
            --adm-slate-body: #475569; --adm-slate-muted: #94A3B8;
            --adm-border: #E2E8F0; --adm-shadow: 0 8px 24px -8px rgba(0,0,0,0.05);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--adm-bg); display: flex; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--adm-slate-body); min-height: 100vh; }
        .main-content { flex: 1; min-width: 0; padding: 32px 36px 60px; display: flex; flex-direction: column; gap: 24px; }
        .page-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
        .page-header-left h1 { font-size: 24px; font-weight: 800; color: var(--adm-slate-dark); letter-spacing: -0.02em; }
        .page-header-left p { font-size: 14px; color: var(--adm-slate-muted); margin-top: 4px; }
        .btn-primary { display: inline-flex; align-items: center; gap: 8px; background: var(--adm-gold); color: var(--adm-charcoal); padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s ease; white-space: nowrap; }
        .btn-primary:hover { background: var(--adm-gold-hover); box-shadow: 0 4px 14px rgba(197,160,89,0.35); transform: translateY(-1px); }
        .btn-primary svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
        .alert { padding: 14px 18px; border-radius: 10px; font-size: 14px; font-weight: 500; border: 1px solid; }
        .alert-success { background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.3); color: #065f46; }
        .alert-error   { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.3); color: #991b1b; }
        .content-card { background: var(--adm-card); border: 1px solid var(--adm-border); border-radius: 16px; box-shadow: var(--adm-shadow); overflow: hidden; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        thead tr { background: #F8FAFC; border-bottom: 1px solid var(--adm-border); }
        thead th { padding: 14px 18px; text-align: left; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--adm-slate-muted); white-space: nowrap; }
        tbody tr { border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #F8FAFC; }
        tbody td { padding: 14px 18px; vertical-align: middle; color: var(--adm-slate-dark); }
        .jabatan-badge { display: inline-block; background: var(--adm-gold-tint); color: #92700b; border: 1px solid rgba(197,160,89,0.3); border-radius: 999px; font-size: 13px; font-weight: 600; padding: 4px 12px; }
        .row-num { font-size: 12px; font-weight: 700; color: var(--adm-slate-muted); }
        .text-muted { color: var(--adm-slate-muted); font-size: 13px; }
        .actions { display: flex; gap: 8px; }
        .btn-action { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid; transition: all 0.15s ease; cursor: pointer; background: none; }
        .btn-action svg { width: 13px; height: 13px; fill: none; stroke: currentColor; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
        .btn-edit { color: var(--adm-gold); border-color: rgba(197,160,89,0.4); background: rgba(197,160,89,0.06); }
        .btn-edit:hover { background: rgba(197,160,89,0.15); border-color: var(--adm-gold); }
        .btn-delete { color: #ef4444; border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.04); }
        .btn-delete:hover { background: rgba(239,68,68,0.12); border-color: #ef4444; }
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state h3 { font-size: 16px; font-weight: 700; color: var(--adm-slate-dark); margin-bottom: 6px; }
        .empty-state p { font-size: 14px; color: var(--adm-slate-muted); }
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 999; opacity: 0; pointer-events: none; transition: opacity 0.2s ease; }
        .modal-overlay.open { opacity: 1; pointer-events: all; }
        .modal-box { background: #fff; border-radius: 16px; padding: 32px; width: 100%; max-width: 420px; box-shadow: 0 20px 60px -10px rgba(0,0,0,0.25); transform: scale(0.95); transition: transform 0.2s ease; }
        .modal-overlay.open .modal-box { transform: scale(1); }
        .modal-icon { width: 52px; height: 52px; background: rgba(239,68,68,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
        .modal-icon svg { width: 24px; height: 24px; stroke: #ef4444; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .modal-title { font-size: 18px; font-weight: 800; color: var(--adm-slate-dark); margin-bottom: 8px; }
        .modal-desc { font-size: 14px; color: var(--adm-slate-body); line-height: 1.6; margin-bottom: 24px; }
        .modal-actions { display: flex; gap: 12px; }
        .btn-cancel { flex: 1; padding: 10px; border-radius: 10px; border: 1px solid var(--adm-border); background: none; font-size: 14px; font-weight: 600; color: var(--adm-slate-body); cursor: pointer; }
        .btn-cancel:hover { background: #F8FAFC; }
        .btn-confirm-delete { flex: 1; padding: 10px; border-radius: 10px; border: none; background: #ef4444; font-size: 14px; font-weight: 700; color: #fff; cursor: pointer; }
        .btn-confirm-delete:hover { background: #dc2626; }
    </style>
</head>
<body>

<?php
$root  = '../../';
$aktif = 'jabatan';
include __DIR__ . '/../sidebar.php';
?>

<main class="main-content">
    <div class="page-header">
        <div class="page-header-left">
            <h1>Data Jabatan</h1>
            <p>Kelola master daftar jabatan &amp; wali kelas untuk data guru.</p>
        </div>
        <a href="tambah.php" class="btn-primary">
            <svg viewBox="0 0 24 24"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
            Tambah Jabatan
        </a>
    </div>

    <?php if ($pesan): ?>
    <div class="alert <?= $berhasil ? 'alert-success' : 'alert-error' ?>">
        <?= htmlspecialchars($pesan) ?>
    </div>
    <?php endif; ?>

    <div class="content-card">
        <div class="table-wrap">
            <?php if ($jabatan_list && mysqli_num_rows($jabatan_list) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Jabatan</th>
                        <th>Jumlah Guru</th>
                        <!-- <th>Ditambahkan</th> -->
                        <th style="width:160px; text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($jabatan_list)): ?>
                    <tr>
                        <td><span class="row-num"><?= $no++ ?></span></td>
                        <td><span class="jabatan-badge"><?= htmlspecialchars($row['nama_jabatan']) ?></span></td>
                        <td><span class="text-muted"><?= (int)$row['jumlah_guru'] ?> Guru</span></td>
                        <!-- <td><span class="text-muted"><?= tanggal_indo(substr($row['created_at'], 0, 10)) ?></span></td> -->
                        <td>
                            <div class="actions" style="justify-content:center">
                                <a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn-action btn-edit">
                                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                <button class="btn-action btn-delete"
                                    onclick="confirmDelete(<?= (int)$row['id'] ?>, '<?= htmlspecialchars(addslashes($row['nama_jabatan'])) ?>')">
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
                <h3>Belum Ada Data Jabatan</h3>
                <p>Klik tombol "Tambah Jabatan" untuk menambahkan jabatan pertama.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M9 6V4h6v2"/></svg>
        </div>
        <div class="modal-title">Hapus Jabatan</div>
        <div class="modal-desc" id="modalDesc">Apakah Anda yakin ingin menghapus jabatan ini?</div>
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
    document.getElementById('modalDesc').textContent = 'Apakah Anda yakin ingin menghapus jabatan "' + nama + '"?';
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