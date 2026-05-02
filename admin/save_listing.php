<?php
// admin/save_listing.php
require_once __DIR__ . '/../config/db.php';

if (!$aktifKullanici || $aktifKullanici['rol'] !== 'admin') {
    header('Location: ../auth/login.php'); exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add_listing.php'); exit;
}

$baslik    = trim($_POST['baslik'] ?? '');
$satici_id = (int)($_POST['satici_id'] ?? 0);
$fiyat     = floatval($_POST['fiyat'] ?? 0);
$kategori  = $_POST['kategori'] ?? 'satilik';
$tip       = $_POST['tip'] ?? 'konut';
$oda       = trim($_POST['oda_sayisi'] ?? '');
$m2        = (int)($_POST['metrekare'] ?? 0);
$il        = trim($_POST['il'] ?? '');
$ilce      = trim($_POST['ilce'] ?? '');
$aciklama  = trim($_POST['aciklama'] ?? '');

if (!$baslik || !$satici_id || !$fiyat) {
    $_SESSION['hata'] = 'Başlık, satıcı ve fiyat zorunludur.';
    header('Location: add_listing.php'); exit;
}

// Resim yükleme
$resim_yolu = 'assets/images/hero-bg.jpg';
if (!empty($_FILES['resim']['name'])) {
    $yuklemeDir = __DIR__ . '/../uploads/';
    if (!is_dir($yuklemeDir)) mkdir($yuklemeDir, 0755, true);
    $uzanti = strtolower(pathinfo($_FILES['resim']['name'], PATHINFO_EXTENSION));
    $izinliUzantilar = ['jpg','jpeg','png','webp'];
    if (in_array($uzanti, $izinliUzantilar)) {
        $dosyaAdi = uniqid('ilan_') . '.' . $uzanti;
        if (move_uploaded_file($_FILES['resim']['tmp_name'], $yuklemeDir . $dosyaAdi)) {
            $resim_yolu = 'uploads/' . $dosyaAdi;
        }
    }
}

try {
    $stmt = $db->prepare("
        INSERT INTO ilanlar (satici_id, baslik, aciklama, fiyat, kategori, tip, oda_sayisi, metrekare, il, ilce, resim_yolu)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([$satici_id, $baslik, $aciklama, $fiyat, $kategori, $tip, $oda, $m2, $il, $ilce, $resim_yolu]);
    header('Location: listings.php?mesaj=eklendi'); exit;
} catch (PDOException $e) {
    $_SESSION['hata'] = 'Kayıt hatası: ' . $e->getMessage();
    header('Location: add_listing.php'); exit;
}
