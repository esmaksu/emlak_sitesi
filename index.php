<?php
// index.php
require_once 'includes/header.php';

// Arama Filtreleri
$where = ["i.durum = 'aktif'"];
$params = [];

if (!empty($_GET['tip'])) {
    $where[] = "i.tip = ?";
    $params[] = $_GET['tip'];
}
if (!empty($_GET['keyword'])) {
    $where[] = "(i.il LIKE ? OR i.ilce LIKE ? OR i.baslik LIKE ?)";
    $kw = "%".$_GET['keyword']."%";
    $params[] = $kw; $params[] = $kw; $params[] = $kw;
}
if (!empty($_GET['oda'])) {
    $where[] = "i.oda_sayisi = ?";
    $params[] = $_GET['oda'];
}
if (!empty($_GET['fiyat_araligi'])) {
    if ($_GET['fiyat_araligi'] == '1') { $where[] = "i.fiyat < 2000000"; }
    elseif ($_GET['fiyat_araligi'] == '2') { $where[] = "i.fiyat BETWEEN 2000000 AND 5000000"; }
    elseif ($_GET['fiyat_araligi'] == '3') { $where[] = "i.fiyat > 5000000"; }
}

$whereClause = implode(" AND ", $where);

// Öne çıkan ilanları veritabanından çekelim (Filtreli)
try {
    $stmt = $db->prepare("
        SELECT i.*, s.ad_soyad AS satici_adi, s.telefon AS satici_tel
        FROM ilanlar i
        LEFT JOIN saticilar s ON i.satici_id = s.id
        WHERE $whereClause
        ORDER BY i.id DESC
        LIMIT 20
    ");
    $stmt->execute($params);
    $ilanlar = $stmt->fetchAll();
} catch (PDOException $e) {
    $ilanlar = [];
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1><?= htmlspecialchars($siteAyarlari['site_slogani'] ?? 'Hayalindeki Evi Bulmak Artık Çok Kolay') ?></h1>
    </div>
</section>

<!-- Search Box (Arama Kutusu) -->
<form action="index.php" method="GET" class="search-box">
    <select name="tip">
        <option value="">Gayrimenkul Tipi</option>
        <option value="konut" <?= ($_GET['tip']??'')=='konut'?'selected':'' ?>>Konut</option>
        <option value="isyeri" <?= ($_GET['tip']??'')=='isyeri'?'selected':'' ?>>İş Yeri</option>
        <option value="arsa" <?= ($_GET['tip']??'')=='arsa'?'selected':'' ?>>Arsa</option>
    </select>
    <input type="text" name="keyword" placeholder="İl, ilçe, mahalle..." value="<?= htmlspecialchars($_GET['keyword']??'') ?>">
    <select name="oda">
        <option value="">Oda Sayısı</option>
        <option value="1+1" <?= ($_GET['oda']??'')=='1+1'?'selected':'' ?>>1+1</option>
        <option value="2+1" <?= ($_GET['oda']??'')=='2+1'?'selected':'' ?>>2+1</option>
        <option value="3+1" <?= ($_GET['oda']??'')=='3+1'?'selected':'' ?>>3+1</option>
        <option value="4+1" <?= ($_GET['oda']??'')=='4+1'?'selected':'' ?>>4+1</option>
    </select>
    <select name="fiyat_araligi">
        <option value="">Fiyat Aralığı</option>
        <option value="1" <?= ($_GET['fiyat_araligi']??'')=='1'?'selected':'' ?>>0 - 2.000.000 TL</option>
        <option value="2" <?= ($_GET['fiyat_araligi']??'')=='2'?'selected':'' ?>>2M - 5.000.000 TL</option>
        <option value="3" <?= ($_GET['fiyat_araligi']??'')=='3'?'selected':'' ?>>5.000.000 TL +</option>
    </select>
    <button type="submit" class="btn-primary" style="padding: 0 40px; border-radius: 40px; font-size: 16px;">Ara</button>
</form>

<!-- Öne Çıkan İlanlar -->
<div class="container">
    <h2 class="section-title">Ev Mi Arıyorsun? <span style="font-size: 16px; color: var(--text-muted); font-weight: normal; margin-left: 20px;">Öne Çıkan İlanlar</span></h2>
    
    <div class="listings-grid">
        <?php if(empty($ilanlar)): ?>
            <p>Henüz ilan bulunmamaktadır veya veritabanı kurulmamıştır.</p>
        <?php else: ?>
            <?php foreach($ilanlar as $ilan): ?>
            <div class="card">
                <a href="ilan_detay.php?id=<?= $ilan['id'] ?>" style="display:block; color:inherit;">
                    <div style="position:relative;">
                        <img src="<?= htmlspecialchars($ilan['resim_yolu']) ?>" alt="<?= htmlspecialchars($ilan['baslik']) ?>" class="card-img" onerror="this.src='https://placehold.co/400x260/1e1e1e/ff6b00?text=Resim+Yok'">
                        <span style="position:absolute;top:12px;left:12px;background:<?= $ilan['kategori']==='satilik'?'#ff6b00':'#3b82f6' ?>;color:#fff;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;text-transform:uppercase;">
                            <?= $ilan['kategori'] === 'satilik' ? 'Satılık' : 'Kiralık' ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="card-title"><?= htmlspecialchars($ilan['baslik']) ?></div>
                        <div class="card-info">
                            <i class="fa-solid fa-house"></i>
                            <?= htmlspecialchars($ilan['oda_sayisi'] ?? '—') ?> &nbsp;|&nbsp; <?= htmlspecialchars($ilan['metrekare'] ?? '—') ?> m²
                        </div>
                        <div class="card-info">
                            <i class="fa-solid fa-location-dot"></i>
                            <?= htmlspecialchars(($ilan['il'] ?? '') . ($ilan['ilce'] ? ' - ' . $ilan['ilce'] : '')) ?>
                        </div>
                        <div class="card-price"><?= number_format($ilan['fiyat'], 0, ',', '.') ?> TL</div>
                    </div>
                </a>
                <div class="card-action" style="padding: 15px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                    <a href="ilan_detay.php?id=<?= $ilan['id'] ?>" style="color: var(--primary-color); font-weight: 600;">İlana Git →</a>
                    <i class="fa-regular fa-heart" style="cursor:pointer; color: var(--text-muted);" title="Favorilere Ekle" onclick="this.classList.toggle('fa-solid'); this.classList.toggle('fa-regular'); this.style.color = this.classList.contains('fa-solid') ? '#ff3333' : 'var(--text-muted)';"></i>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>
