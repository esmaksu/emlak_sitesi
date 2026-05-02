<?php
// panels/seller/index.php
require_once __DIR__ . '/../../config/db.php';

if (!$aktifKullanici || $aktifKullanici['rol'] !== 'satici') {
    header('Location: ../../auth/login.php'); exit;
}

$saticiId = $aktifKullanici['id'];

// Satıcı bilgilerini çek
$satici = $db->prepare("SELECT * FROM saticilar WHERE id = ?");
$satici->execute([$saticiId]);
$satici = $satici->fetch();

// İstatistikler
$toplamIlan  = $db->prepare("SELECT COUNT(*) FROM ilanlar WHERE satici_id = ?");
$toplamIlan->execute([$saticiId]);
$toplamIlan  = $toplamIlan->fetchColumn();

$aktifIlan   = $db->prepare("SELECT COUNT(*) FROM ilanlar WHERE satici_id = ? AND durum = 'aktif'");
$aktifIlan->execute([$saticiId]);
$aktifIlan   = $aktifIlan->fetchColumn();

// Son ilanlar
$ilanlar = $db->prepare("SELECT * FROM ilanlar WHERE satici_id = ? ORDER BY id DESC");
$ilanlar->execute([$saticiId]);
$ilanlar = $ilanlar->fetchAll();

// Silme işlemi
if (isset($_GET['sil'])) {
    $silinecek = (int)$_GET['sil'];
    $db->prepare("DELETE FROM ilanlar WHERE id = ? AND satici_id = ?")->execute([$silinecek, $saticiId]);
    header('Location: index.php?mesaj=silindi'); exit;
}
$mesaj = $_GET['mesaj'] ?? '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satıcı Paneli - Emlak Net</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
        body { display:flex; background:#121212; color:#f8f9fa; min-height:100vh; }

        /* Sidebar */
        .sidebar { width:250px; background:#1a1a1a; border-right:1px solid #2a2a2a; display:flex; flex-direction:column; position:fixed; top:0; left:0; height:100vh; }
        .sidebar-logo { padding:22px 22px; font-size:20px; font-weight:800; color:#ff6b00; border-bottom:1px solid #2a2a2a; display:flex; align-items:center; gap:10px; }
        .sidebar nav { padding:12px 0; flex:1; }
        .sidebar nav a { display:flex; align-items:center; gap:12px; padding:13px 22px; color:#a0aab2; text-decoration:none; font-size:14px; font-weight:500; transition:all .2s; border-left:3px solid transparent; }
        .sidebar nav a:hover, .sidebar nav a.aktif { background:rgba(255,107,0,0.08); color:#ff6b00; border-left-color:#ff6b00; }
        .sidebar-user { padding:18px 22px; border-top:1px solid #2a2a2a; display:flex; align-items:center; gap:12px; }
        .sidebar-avatar { width:38px; height:38px; background:#ff6b00; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:15px; color:white; flex-shrink:0; }
        .sidebar-user-info { overflow:hidden; }
        .sidebar-user-name { font-weight:600; font-size:14px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .sidebar-user-role { font-size:12px; color:#ff6b00; font-weight:600; }
        .sidebar-footer { padding:14px 22px; border-top:1px solid #2a2a2a; }
        .sidebar-footer a { display:flex; align-items:center; gap:10px; color:#ff6b6b; text-decoration:none; font-size:14px; font-weight:500; }

        /* Content */
        .content { margin-left:250px; flex:1; padding:35px 40px; }
        .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; }
        .page-title { font-size:26px; font-weight:800; margin-bottom:5px; }
        .page-subtitle { color:#a0aab2; font-size:14px; }
        .btn-orange { background:linear-gradient(135deg,#ff6b00,#e65c00); color:#fff; border:none; padding:12px 24px; border-radius:10px; font-size:14px; font-weight:700; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:all .3s; }
        .btn-orange:hover { transform:translateY(-2px); box-shadow:0 6px 15px rgba(255,107,0,.3); }

        /* Stats */
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:18px; margin-bottom:35px; }
        .stat-card { background:#1a1a1a; border:1px solid #2a2a2a; border-radius:14px; padding:22px; display:flex; align-items:center; gap:18px; transition:border-color .3s; }
        .stat-card:hover { border-color:#ff6b00; }
        .stat-icon { width:50px; height:50px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; }
        .icon-orange { background:rgba(255,107,0,.15); color:#ff6b00; }
        .icon-green  { background:rgba(34,197,94,.15); color:#4ade80; }
        .stat-num { font-size:28px; font-weight:800; }
        .stat-label { color:#a0aab2; font-size:13px; margin-top:2px; }

        /* Alerts */
        .alert { padding:14px 20px; border-radius:10px; margin-bottom:20px; font-size:14px; display:flex; align-items:center; gap:10px; }
        .alert-success { background:rgba(50,200,80,.1); border:1px solid rgba(50,200,80,.3); color:#6bff8e; }
        .alert-error   { background:rgba(255,50,50,.1); border:1px solid rgba(255,50,50,.3); color:#ff6b6b; }

        /* Table */
        .table-wrap { background:#1a1a1a; border:1px solid #2a2a2a; border-radius:14px; overflow:hidden; }
        .table-header { padding:18px 22px; border-bottom:1px solid #2a2a2a; display:flex; justify-content:space-between; align-items:center; }
        .table-header h3 { font-size:16px; font-weight:700; }
        table { width:100%; border-collapse:collapse; }
        thead th { background:#1e1e1e; padding:12px 18px; text-align:left; font-size:12px; font-weight:600; color:#a0aab2; text-transform:uppercase; letter-spacing:.5px; border-bottom:1px solid #2a2a2a; }
        tbody td { padding:14px 18px; border-bottom:1px solid #222; font-size:14px; color:#d0d0d0; }
        tbody tr:last-child td { border-bottom:none; }
        tbody tr:hover { background:rgba(255,107,0,.02); }
        .badge { display:inline-block; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700; text-transform:uppercase; }
        .badge-orange { background:rgba(255,107,0,.15); color:#ff6b00; }
        .badge-blue   { background:rgba(59,130,246,.15); color:#60a5fa; }
        .badge-green  { background:rgba(34,197,94,.15); color:#4ade80; }
        .badge-gray   { background:rgba(150,150,150,.15); color:#9ca3af; }
        .btn-sm { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; text-decoration:none; font-size:13px; transition:all .2s; }
        .btn-red  { background:rgba(255,50,50,.15); color:#ff6b6b; border:1px solid rgba(255,50,50,.2); }
        .btn-red:hover { background:rgba(255,50,50,.3); }
        .btn-blue { background:rgba(59,130,246,.15); color:#60a5fa; border:1px solid rgba(59,130,246,.2); }
        .btn-blue:hover { background:rgba(59,130,246,.3); }
        .ilan-img { width:55px; height:40px; object-fit:cover; border-radius:6px; background:#2a2a2a; }
        .empty-state { padding:60px; text-align:center; color:#a0aab2; }
        .empty-state i { font-size:48px; margin-bottom:15px; color:#333; display:block; }
        .empty-state p { margin-bottom:20px; }
    </style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-logo"><i class="fa-solid fa-house-chimney"></i> Emlak Net</div>
    <nav>
        <a href="index.php" class="aktif"><i class="fa-solid fa-gauge"></i> Panelim</a>
        <a href="add_listing.php"><i class="fa-solid fa-plus"></i> İlan Ekle</a>
        <a href="../../index.php"><i class="fa-solid fa-arrow-left"></i> Siteye Dön</a>
    </nav>
    <div class="sidebar-user">
        <div class="sidebar-avatar"><?= strtoupper(mb_substr($aktifKullanici['kullanici_adi'], 0, 1)) ?></div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name"><?= htmlspecialchars($aktifKullanici['kullanici_adi']) ?></div>
            <div class="sidebar-user-role">Satıcı</div>
        </div>
    </div>
    <div class="sidebar-footer">
        <a href="../../auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Çıkış Yap</a>
    </div>
</aside>

<!-- İçerik -->
<div class="content">
    <div class="page-header">
        <div>
            <div class="page-title">Merhaba, <?= htmlspecialchars($satici['ad_soyad']) ?> 👋</div>
            <div class="page-subtitle"><?= htmlspecialchars($satici['firma_adi'] ?? 'Satıcı Paneli') ?></div>
        </div>
        <a href="add_listing.php" class="btn-orange"><i class="fa-solid fa-plus"></i> Yeni İlan Ekle</a>
    </div>

    <?php if ($mesaj === 'silindi'): ?>
        <div class="alert alert-error"><i class="fa-solid fa-trash"></i> İlan başarıyla silindi.</div>
    <?php elseif ($mesaj === 'eklendi'): ?>
        <div class="alert alert-success"><i class="fa-solid fa-check"></i> İlan başarıyla yayınlandı!</div>
    <?php endif; ?>

    <!-- İstatistikler -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-orange"><i class="fa-solid fa-list"></i></div>
            <div><div class="stat-num"><?= $toplamIlan ?></div><div class="stat-label">Toplam İlan</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-green"><i class="fa-solid fa-circle-check"></i></div>
            <div><div class="stat-num"><?= $aktifIlan ?></div><div class="stat-label">Aktif İlan</div></div>
        </div>
    </div>

    <!-- İlanlarım -->
    <div class="table-wrap">
        <div class="table-header">
            <h3>İlanlarım</h3>
            <a href="add_listing.php" class="btn-orange" style="padding:9px 18px; font-size:13px;"><i class="fa-solid fa-plus"></i> Yeni İlan</a>
        </div>

        <?php if (empty($ilanlar)): ?>
        <div class="empty-state">
            <i class="fa-solid fa-house-circle-xmark"></i>
            <p>Henüz hiç ilan eklemediniz.</p>
            <a href="add_listing.php" class="btn-orange">İlk İlanını Ekle</a>
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr><th>Foto</th><th>Başlık</th><th>Fiyat</th><th>Kategori</th><th>Konum</th><th>Durum</th><th>İşlem</th></tr>
            </thead>
            <tbody>
            <?php foreach ($ilanlar as $i): ?>
            <tr>
                <td><img class="ilan-img" src="../../<?= htmlspecialchars($i['resim_yolu']) ?>" onerror="this.src='https://placehold.co/55x40/1e1e1e/ff6b00?text=?'" alt=""></td>
                <td style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-weight:600;"><?= htmlspecialchars($i['baslik']) ?></td>
                <td style="font-weight:700; color:#ff6b00;"><?= number_format($i['fiyat'],0,',','.') ?> TL</td>
                <td><span class="badge <?= $i['kategori']==='satilik'?'badge-orange':'badge-blue' ?>"><?= $i['kategori']==='satilik'?'Satılık':'Kiralık' ?></span></td>
                <td><?= htmlspecialchars(($i['il']??'').($i['ilce']?' - '.$i['ilce']:'')) ?></td>
                <td><span class="badge <?= $i['durum']==='aktif'?'badge-green':'badge-gray' ?>"><?= $i['durum']==='aktif'?'Aktif':'Pasif' ?></span></td>
                <td>
                    <div style="display:flex;gap:8px;">
                        <a href="../../ilan_detay.php?id=<?= $i['id'] ?>" class="btn-sm btn-blue" target="_blank" title="Görüntüle"><i class="fa-solid fa-eye"></i></a>
                        <a href="index.php?sil=<?= $i['id'] ?>" class="btn-sm btn-red" title="Sil" onclick="return confirm('Bu ilanı silmek istediğinize emin misiniz?')"><i class="fa-solid fa-trash"></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
