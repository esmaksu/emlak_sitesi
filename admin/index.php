<?php
// admin/index.php
require_once __DIR__ . '/../config/db.php';

if (!$aktifKullanici || $aktifKullanici['rol'] !== 'admin') {
    header('Location: ../auth/login.php'); exit;
}

$ilanSayisi      = $db->query("SELECT COUNT(*) FROM ilanlar")->fetchColumn();
$aktifIlan       = $db->query("SELECT COUNT(*) FROM ilanlar WHERE durum='aktif'")->fetchColumn();
$aliciSayisi     = $db->query("SELECT COUNT(*) FROM alicilar")->fetchColumn();
$saticiSayisi    = $db->query("SELECT COUNT(*) FROM saticilar")->fetchColumn();
$sonIlanlar      = $db->query("SELECT i.*, s.ad_soyad AS satici_adi FROM ilanlar i LEFT JOIN saticilar s ON i.satici_id=s.id ORDER BY i.id DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli - Emlak Net</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include __DIR__ . '/partials/admin_style.php'; ?>
</head>
<body>
<?php include __DIR__ . '/partials/sidebar.php'; ?>

<div class="content">
    <div class="page-header">
        <div>
            <div class="page-title">Gösterge Paneli</div>
            <div class="page-subtitle">Hoşgeldiniz, <strong><?= htmlspecialchars($aktifKullanici['kullanici_adi']) ?></strong> 👋</div>
        </div>
        <a href="add_listing.php" class="btn-orange"><i class="fa-solid fa-plus"></i> İlan Ekle</a>
    </div>

    <!-- İstatistikler -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fa-solid fa-list"></i></div>
            <div><div class="stat-num"><?= $ilanSayisi ?></div><div class="stat-label">Toplam İlan</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
            <div><div class="stat-num"><?= $aktifIlan ?></div><div class="stat-label">Aktif İlan</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fa-solid fa-user-tie"></i></div>
            <div><div class="stat-num"><?= $aliciSayisi ?></div><div class="stat-label">Kayıtlı Alıcı</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fa-solid fa-store"></i></div>
            <div><div class="stat-num"><?= $saticiSayisi ?></div><div class="stat-label">Kayıtlı Satıcı</div></div>
        </div>
    </div>

    <!-- Son Eklenen İlanlar -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
        <h3 style="font-size:18px;font-weight:700;">Son Eklenen İlanlar</h3>
        <a href="listings.php" class="btn-gray-btn" style="font-size:13px;padding:8px 16px;">Tümünü Gör</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Başlık</th><th>Fiyat</th><th>Kategori</th><th>Konum</th><th>Satıcı</th><th>Durum</th><th>İşlem</th></tr>
            </thead>
            <tbody>
            <?php foreach ($sonIlanlar as $i): ?>
            <tr>
                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($i['baslik']) ?></td>
                <td style="font-weight:700;color:#ff6b00;"><?= number_format($i['fiyat'],0,',','.') ?> TL</td>
                <td><span class="badge <?= $i['kategori']==='satilik'?'badge-orange':'badge-blue' ?>"><?= $i['kategori']==='satilik'?'Satılık':'Kiralık' ?></span></td>
                <td><?= htmlspecialchars(($i['il']??'').($i['ilce']?' - '.$i['ilce']:'')) ?></td>
                <td><?= htmlspecialchars($i['satici_adi']??'—') ?></td>
                <td><span class="badge <?= $i['durum']==='aktif'?'badge-green':'badge-gray' ?>"><?= ucfirst($i['durum']) ?></span></td>
                <td>
                    <a href="listings.php?sil=<?= $i['id'] ?>" class="btn-sm btn-red" onclick="return confirm('Silinsin mi?')"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
