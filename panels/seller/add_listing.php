<?php
// panels/seller/add_listing.php
require_once __DIR__ . '/../../config/db.php';

if (!$aktifKullanici || $aktifKullanici['rol'] !== 'satici') {
    header('Location: ../../auth/login.php'); exit;
}

$hata   = $_SESSION['hata']   ?? ''; unset($_SESSION['hata']);
$basari = $_SESSION['basari'] ?? ''; unset($_SESSION['basari']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İlan Ekle - Satıcı Paneli</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
        body { display:flex; background:#121212; color:#f8f9fa; min-height:100vh; }

        /* Sidebar - Same as index.php */
        .sidebar { width:250px; background:#1a1a1a; border-right:1px solid #2a2a2a; display:flex; flex-direction:column; position:fixed; top:0; left:0; height:100vh; }
        .sidebar-logo { padding:22px 22px; font-size:20px; font-weight:800; color:#ff6b00; border-bottom:1px solid #2a2a2a; display:flex; align-items:center; gap:10px; }
        .sidebar nav { padding:12px 0; flex:1; }
        .sidebar nav a { display:flex; align-items:center; gap:12px; padding:13px 22px; color:#a0aab2; text-decoration:none; font-size:14px; font-weight:500; transition:all .2s; border-left:3px solid transparent; }
        .sidebar nav a:hover, .sidebar nav a.aktif { background:rgba(255,107,0,0.08); color:#ff6b00; border-left-color:#ff6b00; }
        .sidebar-user { padding:18px 22px; border-top:1px solid #2a2a2a; display:flex; align-items:center; gap:12px; }
        .sidebar-avatar { width:38px; height:38px; background:#ff6b00; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:15px; color:white; flex-shrink:0; }
        .sidebar-user-name { font-weight:600; font-size:14px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .sidebar-footer { padding:14px 22px; border-top:1px solid #2a2a2a; }
        .sidebar-footer a { display:flex; align-items:center; gap:10px; color:#ff6b6b; text-decoration:none; font-size:14px; font-weight:500; }

        /* Content */
        .content { margin-left:250px; flex:1; padding:35px 40px; }
        .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; }
        .page-title { font-size:26px; font-weight:800; margin-bottom:5px; }
        .page-subtitle { color:#a0aab2; font-size:14px; }

        .btn-gray-btn { background:#2a2a2a; color:#a0aab2; border:1px solid #333; padding:11px 22px; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:all .2s; }
        .btn-gray-btn:hover { background:#333; color:#f8f9fa; }
        .btn-orange { background:linear-gradient(135deg,#ff6b00,#e65c00); color:#fff; border:none; padding:14px 40px; border-radius:10px; font-size:16px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:8px; transition:all .3s; }
        .btn-orange:hover { transform:translateY(-2px); box-shadow:0 6px 15px rgba(255,107,0,.35); }

        /* Form */
        .form-wrap { background:#1a1a1a; border:1px solid #2a2a2a; border-radius:14px; padding:30px; }
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

        .alert { padding:14px 20px; border-radius:10px; margin-bottom:20px; font-size:14px; display:flex; align-items:center; gap:10px; }
        .alert-error { background:rgba(255,50,50,0.1); border:1px solid rgba(255,50,50,.3); color:#ff6b6b; }
    </style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-logo"><i class="fa-solid fa-house-chimney"></i> Emlak Net</div>
    <nav>
        <a href="index.php"><i class="fa-solid fa-gauge"></i> Panelim</a>
        <a href="add_listing.php" class="aktif"><i class="fa-solid fa-plus"></i> İlan Ekle</a>
        <a href="../../index.php"><i class="fa-solid fa-arrow-left"></i> Siteye Dön</a>
    </nav>
    <div class="sidebar-user">
        <div class="sidebar-avatar"><?= strtoupper(mb_substr($aktifKullanici['kullanici_adi'], 0, 1)) ?></div>
        <div class="sidebar-user-name"><?= htmlspecialchars($aktifKullanici['kullanici_adi']) ?></div>
    </div>
    <div class="sidebar-footer">
        <a href="../../auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Çıkış Yap</a>
    </div>
</aside>

<div class="content">
    <div class="page-header">
        <div>
            <div class="page-title">Yeni İlan Ekle</div>
            <div class="page-subtitle">Gayrimenkulünüzü milyonlarca alıcıya ulaştırın</div>
        </div>
        <a href="index.php" class="btn-gray-btn"><i class="fa-solid fa-arrow-left"></i> İlanlarıma Dön</a>
    </div>

    <?php if ($hata): ?>
        <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($hata) ?></div>
    <?php endif; ?>

    <div class="form-wrap">
        <form action="save_listing.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group form-full">
                    <label>İlan Başlığı *</label>
                    <input type="text" name="baslik" placeholder="Örn: Boğaz Manzaralı Satılık 3+1 Daire" required>
                </div>
                <div class="form-group">
                    <label>Fiyat (TL) *</label>
                    <input type="number" name="fiyat" placeholder="4500000" required>
                </div>
                <div class="form-group">
                    <label>Kategori *</label>
                    <select name="kategori" required>
                        <option value="satilik">Satılık</option>
                        <option value="kiralik">Kiralık</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Emlak Tipi</label>
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
                    <input type="number" name="metrekare" placeholder="140">
                </div>
                <div class="form-group">
                    <label>İl</label>
                    <input type="text" name="il" placeholder="İstanbul">
                </div>
                <div class="form-group">
                    <label>İlçe</label>
                    <input type="text" name="ilce" placeholder="Beşiktaş">
                </div>
                <div class="form-group">
                    <label>İlan Fotoğrafı</label>
                    <input type="file" name="resim" accept="image/*">
                </div>
                <div class="form-group form-full">
                    <label>Açıklama</label>
                    <textarea name="aciklama" placeholder="Evin özellikleri, ulaşım imkanları vb. detayları buraya yazın..."></textarea>
                </div>
                <div class="form-group form-full" style="text-align:right;">
                    <button type="submit" class="btn-orange">
                        <i class="fa-solid fa-paper-plane"></i> İlanı Yayınla
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>
