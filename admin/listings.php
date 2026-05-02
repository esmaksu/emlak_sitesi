<?php
// admin/listings.php
require_once __DIR__ . '/../config/db.php';

if (!$aktifKullanici || $aktifKullanici['rol'] !== 'admin') {
    header('Location: ../auth/login.php'); exit;
}

// Silme işlemi
if (isset($_GET['sil'])) {
    $silinecekId = (int)$_GET['sil'];
    $db->prepare("DELETE FROM ilanlar WHERE id = ?")->execute([$silinecekId]);
    header('Location: listings.php?mesaj=silindi'); exit;
}

// Durum değiştirme
if (isset($_GET['durum']) && isset($_GET['id'])) {
    $yeniDurum = $_GET['durum'] === 'aktif' ? 'aktif' : 'pasif';
    $db->prepare("UPDATE ilanlar SET durum = ? WHERE id = ?")->execute([$yeniDurum, (int)$_GET['id']]);
    header('Location: listings.php?mesaj=guncellendi'); exit;
}

$mesaj   = $_GET['mesaj'] ?? '';
$ilanlar = $db->query("
    SELECT i.*, s.ad_soyad AS satici_adi, s.firma_adi
    FROM ilanlar i
    LEFT JOIN saticilar s ON i.satici_id = s.id
    ORDER BY i.id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İlan Yönetimi - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include __DIR__ . '/partials/admin_style.php'; ?>
</head>
<body>
<?php include __DIR__ . '/partials/sidebar.php'; ?>

<div class="content">
    <div class="page-header">
        <div>
            <div class="page-title">İlan Yönetimi</div>
            <div class="page-subtitle">Tüm ilanları buradan yönetin</div>
        </div>
        <a href="add_listing.php" class="btn-orange"><i class="fa-solid fa-plus"></i> Yeni İlan Ekle</a>
    </div>

    <?php if ($mesaj === 'silindi'): ?>
        <div class="alert alert-error"><i class="fa-solid fa-trash"></i> İlan başarıyla silindi.</div>
    <?php elseif ($mesaj === 'guncellendi'): ?>
        <div class="alert alert-success"><i class="fa-solid fa-check"></i> İlan durumu güncellendi.</div>
    <?php endif; ?>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Başlık</th>
                    <th>Fiyat</th>
                    <th>Kategori</th>
                    <th>Konum</th>
                    <th>Satıcı</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($ilanlar)): ?>
                <tr><td colspan="8" style="text-align:center;color:#a0aab2;padding:30px;">Henüz ilan yok.</td></tr>
            <?php else: ?>
                <?php foreach ($ilanlar as $i): ?>
                <tr>
                    <td><?= $i['id'] ?></td>
                    <td style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($i['baslik']) ?></td>
                    <td style="font-weight:700;color:#ff6b00;"><?= number_format($i['fiyat'],0,',','.') ?> TL</td>
                    <td>
                        <span class="badge <?= $i['kategori']==='satilik'?'badge-orange':'badge-blue' ?>">
                            <?= $i['kategori']==='satilik'?'Satılık':'Kiralık' ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars(($i['il']??'') . ($i['ilce']?' - '.$i['ilce']:'')) ?></td>
                    <td><?= htmlspecialchars($i['satici_adi'] ?? '—') ?></td>
                    <td>
                        <?php if ($i['durum']==='aktif'): ?>
                            <span class="badge badge-green">Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-gray">Pasif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="display:flex;gap:8px;">
                            <?php if ($i['durum']==='aktif'): ?>
                                <a href="listings.php?id=<?= $i['id'] ?>&durum=pasif" class="btn-sm btn-gray" title="Pasife Al"><i class="fa-solid fa-eye-slash"></i></a>
                            <?php else: ?>
                                <a href="listings.php?id=<?= $i['id'] ?>&durum=aktif" class="btn-sm btn-green" title="Aktife Al"><i class="fa-solid fa-eye"></i></a>
                            <?php endif; ?>
                            <a href="../ilan_detay.php?id=<?= $i['id'] ?>" class="btn-sm btn-blue" title="Görüntüle" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                            <a href="listings.php?sil=<?= $i['id'] ?>" class="btn-sm btn-red" title="Sil" onclick="return confirm('Bu ilanı silmek istediğinize emin misiniz?')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
