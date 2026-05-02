<?php
// admin/settings.php
require_once __DIR__ . '/../config/db.php';

if (!$aktifKullanici || $aktifKullanici['rol'] !== 'admin') {
    header('Location: ../auth/login.php'); exit;
}

// Ayarları çek
$ayarlar = $db->query("SELECT * FROM site_ayarlari WHERE id = 1")->fetch();

// Güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik  = $_POST['site_basligi'] ?? '';
    $slogan  = $_POST['site_slogani'] ?? '';
    $renk    = $_POST['arkaplan_rengi'] ?? '#121212';
    $telefon = $_POST['iletisim_telefon'] ?? '';
    $eposta  = $_POST['iletisim_eposta'] ?? '';
    
    // Resim yükleme
    $resim_yolu = $ayarlar['arkaplan_resmi'];
    if (!empty($_FILES['arkaplan_resmi']['name'])) {
        $yuklemeDir = __DIR__ . '/../assets/images/';
        $uzanti = strtolower(pathinfo($_FILES['arkaplan_resmi']['name'], PATHINFO_EXTENSION));
        $dosyaAdi = 'hero-bg.' . $uzanti;
        if (move_uploaded_file($_FILES['arkaplan_resmi']['tmp_name'], $yuklemeDir . $dosyaAdi)) {
            $resim_yolu = 'assets/images/' . $dosyaAdi;
        }
    }

    try {
        $stmt = $db->prepare("
            UPDATE site_ayarlari 
            SET site_basligi = ?, site_slogani = ?, arkaplan_rengi = ?, arkaplan_resmi = ?, iletisim_telefon = ?, iletisim_eposta = ?
            WHERE id = 1
        ");
        $stmt->execute([$baslik, $slogan, $renk, $resim_yolu, $telefon, $eposta]);
        header('Location: settings.php?mesaj=guncellendi'); exit;
    } catch (PDOException $e) {
        $hata = "Güncelleme hatası: " . $e->getMessage();
    }
}

$mesaj = $_GET['mesaj'] ?? '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Site Ayarları - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include __DIR__ . '/partials/admin_style.php'; ?>
    <style>
        .settings-form { max-width: 800px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #a0aab2; margin-bottom: 8px; text-transform: uppercase; }
        .form-group input { width: 100%; padding: 12px 15px; background: #2a2a2a; border: 1px solid #333; border-radius: 8px; color: #f8f9fa; outline: none; }
        .form-group input:focus { border-color: #ff6b00; }
        .preview-box { margin-top: 10px; padding: 15px; background: #222; border-radius: 8px; border: 1px dotted #444; }
    </style>
</head>
<body>
<?php include __DIR__ . '/partials/sidebar.php'; ?>

<div class="content">
    <div class="page-header">
        <div>
            <div class="page-title">Site Ayarları</div>
            <div class="page-subtitle">Sitenin genel görünüm ve iletişim bilgilerini yönetin</div>
        </div>
    </div>

    <?php if ($mesaj === 'guncellendi'): ?>
        <div class="alert alert-success"><i class="fa-solid fa-check"></i> Ayarlar başarıyla güncellendi.</div>
    <?php endif; ?>
    <?php if (isset($hata)): ?>
        <div class="alert alert-error"><i class="fa-solid fa-xmark"></i> <?= $hata ?></div>
    <?php endif; ?>

    <div class="table-wrap" style="padding: 30px;">
        <form action="settings.php" method="POST" enctype="multipart/form-data" class="settings-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Site Başlığı</label>
                    <input type="text" name="site_basligi" value="<?= htmlspecialchars($ayarlar['site_basligi']) ?>">
                </div>
                <div class="form-group">
                    <label>Arka Plan Rengi</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="color" name="arkaplan_rengi" value="<?= htmlspecialchars($ayarlar['arkaplan_rengi']) ?>" style="width: 50px; padding: 2px; height: 45px; border: none;">
                        <input type="text" value="<?= htmlspecialchars($ayarlar['arkaplan_rengi']) ?>" readonly style="flex: 1;">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Site Sloganı</label>
                <input type="text" name="site_slogani" value="<?= htmlspecialchars($ayarlar['site_slogani']) ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>İletişim Telefonu</label>
                    <input type="text" name="iletisim_telefon" value="<?= htmlspecialchars($ayarlar['iletisim_telefon']) ?>">
                </div>
                <div class="form-group">
                    <label>İletişim E-Posta</label>
                    <input type="email" name="iletisim_eposta" value="<?= htmlspecialchars($ayarlar['iletisim_eposta']) ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Arka Plan Resmi (Hero Image)</label>
                <input type="file" name="arkaplan_resmi" accept="image/*">
                <div class="preview-box">
                    <small style="color: #666; display: block; margin-bottom: 5px;">Mevcut Resim:</small>
                    <img src="../<?= $ayarlar['arkaplan_resmi'] ?>" style="max-width: 200px; border-radius: 4px;" alt="Mevcut BG">
                </div>
            </div>

            <div style="text-align: right; margin-top: 20px;">
                <button type="submit" class="btn-orange" style="padding: 14px 40px;">
                    <i class="fa-solid fa-save"></i> Ayarları Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
