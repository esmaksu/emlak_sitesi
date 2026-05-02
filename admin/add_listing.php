<?php
// admin/add_listing.php
require_once __DIR__ . '/../config/db.php';

if (!$aktifKullanici || $aktifKullanici['rol'] !== 'admin') {
    header('Location: ../auth/login.php'); exit;
}

$hata   = $_SESSION['hata']   ?? ''; unset($_SESSION['hata']);
$basari = $_SESSION['basari'] ?? ''; unset($_SESSION['basari']);
$saticilar = $db->query("SELECT id, ad_soyad, firma_adi FROM saticilar ORDER BY ad_soyad")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İlan Ekle - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include __DIR__ . '/partials/admin_style.php'; ?>
    <style>
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
        .form-full { grid-column: 1 / -1; }
        .form-group label { display:block; font-size:12px; font-weight:600; color:#a0aab2; text-transform:uppercase; letter-spacing:.5px; margin-bottom:8px; }
        .form-group input, .form-group select, .form-group textarea {
            width:100%; padding:13px 16px; background:#2a2a2a; border:1px solid #333;
            border-radius:10px; color:#f8f9fa; font-size:14px; outline:none; transition:border-color .3s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color:#ff6b00; }
        .form-group textarea { resize:vertical; min-height:120px; }
        .form-group select option { background:#2a2a2a; }
    </style>
</head>
<body>
<?php include __DIR__ . '/partials/sidebar.php'; ?>
<div class="content">
    <div class="page-header">
        <div>
            <div class="page-title">Yeni İlan Ekle</div>
            <div class="page-subtitle">Sisteme yeni bir emlak ilanı ekleyin</div>
        </div>
        <a href="listings.php" class="btn-gray-btn"><i class="fa-solid fa-arrow-left"></i> İlanlara Dön</a>
    </div>

    <?php if ($hata): ?>
        <div class="alert alert-error"><?= htmlspecialchars($hata) ?></div>
    <?php endif; ?>
    <?php if ($basari): ?>
        <div class="alert alert-success"><?= htmlspecialchars($basari) ?></div>
    <?php endif; ?>

    <div class="table-wrap" style="padding:30px;">
        <form action="save_listing.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group form-full">
                    <label>İlan Başlığı *</label>
                    <input type="text" name="baslik" placeholder="Örn: Kadıköy'de Satılık 3+1 Daire" required>
                </div>
                <div class="form-group">
                    <label>Satıcı *</label>
                    <select name="satici_id" required>
                        <option value="">Satıcı Seçin</option>
                        <?php foreach ($saticilar as $s): ?>
                        <option value="<?= $s['id'] ?>">
                            <?= htmlspecialchars($s['ad_soyad']) ?> <?= $s['firma_adi'] ? '(' . htmlspecialchars($s['firma_adi']) . ')' : '' ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fiyat (TL) *</label>
                    <input type="number" name="fiyat" placeholder="3500000" required>
                </div>
                <div class="form-group">
                    <label>Kategori *</label>
                    <select name="kategori" required>
                        <option value="satilik">Satılık</option>
                        <option value="kiralik">Kiralık</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Gayrimenkul Tipi</label>
                    <select name="tip">
                        <option value="konut">Konut</option>
                        <option value="arsa">Arsa</option>
                        <option value="isyeri">İşyeri</option>
                        <option value="turistik">Turistik Tesis</option>
                        <option value="gunluk">Günlük Kiralık</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Oda Sayısı</label>
                    <input type="text" name="oda_sayisi" placeholder="3+1">
                </div>
                <div class="form-group">
                    <label>Metrekare (m²)</label>
                    <input type="number" name="metrekare" placeholder="120">
                </div>
                <div class="form-group">
                    <label>İl</label>
                    <input type="text" name="il" placeholder="İstanbul">
                </div>
                <div class="form-group">
                    <label>İlçe</label>
                    <input type="text" name="ilce" placeholder="Kadıköy">
                </div>
                <div class="form-group">
                    <label>Resim Yükle</label>
                    <input type="file" name="resim" accept="image/*">
                </div>
                <div class="form-group form-full">
                    <label>Açıklama</label>
                    <textarea name="aciklama" placeholder="İlan hakkında detaylı bilgi..."></textarea>
                </div>
                <div class="form-group form-full" style="text-align:right;">
                    <button type="submit" class="btn-orange" style="padding:14px 40px;font-size:16px;">
                        <i class="fa-solid fa-plus"></i> İlanı Kaydet
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
