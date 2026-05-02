<?php
// ilan_detay.php
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$ilan = null;

if($id > 0) {
    try {
        // İlan ve Satıcı bilgilerini birlikte çekiyoruz
        $stmt = $db->prepare("
            SELECT i.*, s.ad_soyad AS satici_adi, s.telefon AS satici_tel, s.firma_adi 
            FROM ilanlar i 
            LEFT JOIN saticilar s ON i.satici_id = s.id 
            WHERE i.id = ?
        ");
        $stmt->execute([$id]);
        $ilan = $stmt->fetch();
    } catch(PDOException $e) {
        $ilan = null;
    }
}

if (!$ilan):
?>
<div class="container" style="text-align: center; padding: 100px 20px;">
    <i class="fa-solid fa-house-circle-exclamation" style="font-size: 60px; color: #333; margin-bottom: 20px;"></i>
    <h2 style="font-size: 32px; font-weight: 800;">İlan Bulunamadı</h2>
    <p style="color: var(--text-muted); margin-top: 10px;">Aradığınız ilan yayından kaldırılmış veya taşınmış olabilir.</p>
    <a href="index.php" class="btn-primary" style="display: inline-block; margin-top: 30px;">Ana Sayfaya Dön</a>
</div>
<?php 
require_once 'includes/footer.php';
exit; 
endif; 
?>

<style>
    .detail-container { padding: 40px 0; }
    .detail-header { margin-bottom: 25px; }
    .detail-header h1 { font-size: 24px; font-weight: 700; color: #f8f9fa; line-height: 1.4; }
    
    /* Galeri Yapısı */
    .gallery-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 10px;
        height: 480px;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 30px;
    }
    .main-img { width: 100%; height: 100%; object-fit: cover; }
    .side-imgs { display: grid; grid-template-rows: 1fr 1fr; gap: 10px; }
    .side-img { width: 100%; height: 100%; object-fit: cover; }
    
    .detail-content-wrap { display: grid; grid-template-columns: 1fr 340px; gap: 30px; }
    
    /* Sol Taraf */
    .detail-left { background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 16px; padding: 30px; }
    
    .price-box { font-size: 32px; font-weight: 800; color: var(--primary-color); margin-bottom: 20px; }
    
    .specs-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 30px;
        padding: 20px 0;
        border-top: 1px solid #2a2a2a;
        border-bottom: 1px solid #2a2a2a;
    }
    .spec-item { display: flex; flex-direction: column; gap: 5px; }
    .spec-label { font-size: 13px; color: #a0aab2; font-weight: 500; }
    .spec-value { font-size: 16px; font-weight: 700; color: #f8f9fa; }
    
    .location-info { display: flex; align-items: center; gap: 8px; color: #a0aab2; font-size: 14px; margin-bottom: 30px; }
    .location-info i { color: var(--primary-color); }
    
    .description-box h3 { font-size: 18px; font-weight: 700; margin-bottom: 15px; }
    .description-box p { color: #d0d0d0; line-height: 1.8; font-size: 15px; white-space: pre-line; }
    
    /* Sağ Taraf - Sidebar */
    .detail-sidebar { position: sticky; top: 100px; display: flex; flex-direction: column; gap: 20px; }
    
    .contact-card { background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 16px; padding: 25px; }
    .seller-header { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
    .seller-avatar { width: 50px; height: 50px; background: #2a2a2a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; color: var(--primary-color); }
    .seller-name { font-weight: 700; font-size: 16px; margin-bottom: 2px; }
    .seller-firma { font-size: 12px; color: #a0aab2; }
    
    .btn-contact { width: 100%; padding: 15px; border-radius: 10px; font-weight: 700; font-size: 15px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.3s; margin-bottom: 12px; text-decoration: none; }
    .btn-phone { background: #22c55e; color: #fff; }
    .btn-phone:hover { background: #16a34a; box-shadow: 0 5px 15px rgba(34,197,94,0.3); }
    .btn-message { background: transparent; border: 1px solid #333; color: #f8f9fa; }
    .btn-message:hover { background: #222; border-color: #444; }
    
    .map-preview {
        background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 16px; padding: 15px;
        text-align: center; color: #a0aab2; font-size: 13px;
    }
    .map-img { width: 100%; height: 120px; background: #2a2a2a url('https://placehold.co/300x120/1e1e1e/333?text=Harita+Önizleme') center/cover; border-radius: 8px; margin-bottom: 10px; position: relative; }
    .map-img::after { content: '\f3c5'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: var(--primary-color); font-size: 24px; }
    
    @media (max-width: 992px) {
        .gallery-grid { grid-template-columns: 1fr; height: auto; }
        .side-imgs { display: none; }
        .detail-content-wrap { grid-template-columns: 1fr; }
        .detail-sidebar { position: static; }
    }
</style>

<div class="container detail-container">
    <div class="detail-header">
        <h1><?= htmlspecialchars($ilan['baslik']) ?></h1>
    </div>

    <!-- Fotoğraf Galerisi -->
    <div class="gallery-grid">
        <img src="<?= htmlspecialchars($ilan['resim_yolu']) ?>" class="main-img" alt="Ana Resim" onerror="this.src='https://placehold.co/800x480/1e1e1e/ff6b00?text=Fotoğraf+Bulunamadı'">
        <div class="side-imgs">
            <img src="<?= htmlspecialchars($ilan['resim_yolu']) ?>" class="side-img" style="filter: brightness(0.6);" alt="">
            <img src="<?= htmlspecialchars($ilan['resim_yolu']) ?>" class="side-img" style="filter: brightness(0.4);" alt="">
        </div>
    </div>

    <div class="detail-content-wrap">
        <!-- Sol Taraf: Bilgiler -->
        <div class="detail-left">
            <div class="price-box"><?= number_format($ilan['fiyat'], 0, ',', '.') ?> TL</div>
            
            <div class="location-info">
                <i class="fa-solid fa-location-dot"></i>
                <?= htmlspecialchars($ilan['il']) ?> / <?= htmlspecialchars($ilan['ilce']) ?> / <?= htmlspecialchars($ilan['kategori'] === 'satilik' ? 'Satılık' : 'Kiralık') ?>
            </div>

            <!-- Özellikler -->
            <div class="specs-grid">
                <div class="spec-item">
                    <span class="spec-label">Oda Sayısı</span>
                    <span class="spec-value"><?= htmlspecialchars($ilan['oda_sayisi'] ?: '—') ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Brüt Metrekare</span>
                    <span class="spec-value"><?= htmlspecialchars($ilan['metrekare'] ?: '—') ?> m²</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Gayrimenkul Tipi</span>
                    <span class="spec-value"><?= ucfirst(htmlspecialchars($ilan['tip'] ?: 'Konut')) ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Bulunduğu Kat</span>
                    <span class="spec-value">5. Kat</span> <!-- Örnek veri, DB'de yoksa sabit kalabilir veya DB'ye eklenebilir -->
                </div>
                <div class="spec-item">
                    <span class="spec-label">Kullanım Durumu</span>
                    <span class="spec-value">Boş</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">İlan Tarihi</span>
                    <span class="spec-value"><?= date('d.m.Y', strtotime($ilan['eklenme_tarihi'])) ?></span>
                </div>
            </div>

            <!-- Açıklama -->
            <div class="description-box">
                <h3>İlan Açıklaması</h3>
                <p><?= htmlspecialchars($ilan['aciklama']) ?></p>
            </div>
        </div>

        <!-- Sağ Taraf: Sidebar -->
        <div class="detail-sidebar">
            <div class="contact-card">
                <div class="seller-header">
                    <div class="seller-avatar"><?= strtoupper(mb_substr($ilan['satici_adi'], 0, 1)) ?></div>
                    <div>
                        <div class="seller-name"><?= htmlspecialchars($ilan['satici_adi']) ?></div>
                        <div class="seller-firma"><?= htmlspecialchars($ilan['firma_adi'] ?: 'Bireysel İlan Sahibi') ?></div>
                    </div>
                </div>
                
                <a href="tel:<?= $ilan['satici_tel'] ?>" class="btn-contact btn-phone" id="btn-phone-show" onclick="this.innerHTML='<i class=\'fa-solid fa-phone\'></i> <?= $ilan['satici_tel'] ?>'; return false;">
                    <i class="fa-solid fa-phone"></i> Telefona Bak
                </a>
                
                <button class="btn-contact btn-message">
                    <i class="fa-regular fa-comment-dots"></i> Mesaj Gönder
                </button>

                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <button style="flex: 1; padding: 10px; background: #222; border: 1px solid #333; color: #a0aab2; border-radius: 8px; font-size: 13px; cursor: pointer;">
                        <i class="fa-regular fa-heart"></i> Favori
                    </button>
                    <button style="flex: 1; padding: 10px; background: #222; border: 1px solid #333; color: #a0aab2; border-radius: 8px; font-size: 13px; cursor: pointer;">
                        <i class="fa-solid fa-share-nodes"></i> Paylaş
                    </button>
                </div>
            </div>

            <div class="map-preview">
                <div class="map-img"></div>
                <span style="font-weight: 600; color: #f8f9fa;">Konumu Haritada Gör</span>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
