<?php
// auth/register_process.php
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php'); exit;
}

$kullanici_adi = trim($_POST['kullanici_adi'] ?? '');
$ad_soyad      = trim($_POST['ad_soyad'] ?? '');
$eposta        = trim($_POST['eposta'] ?? '');
$telefon       = trim($_POST['telefon'] ?? '');
$sifre         = $_POST['sifre'] ?? '';
$rol           = $_POST['rol'] ?? 'alici';

if (!in_array($rol, ['alici', 'satici'])) $rol = 'alici';

// Validasyon
if (empty($kullanici_adi) || empty($ad_soyad) || empty($eposta) || empty($sifre)) {
    $_SESSION['hata'] = 'Tüm zorunlu alanları doldurunuz.';
    header('Location: register.php'); exit;
}
if (!filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['hata'] = 'Geçerli bir e-posta adresi giriniz.';
    header('Location: register.php'); exit;
}
if (strlen($sifre) < 6) {
    $_SESSION['hata'] = 'Şifre en az 6 karakter olmalıdır.';
    header('Location: register.php'); exit;
}

try {
    $sifreHash = password_hash($sifre, PASSWORD_DEFAULT);

    if ($rol === 'satici') {
        $firma_adi = trim($_POST['firma_adi'] ?? '');

        // E-posta kontrol
        $stmt = $db->prepare("SELECT id FROM saticilar WHERE eposta = ? OR kullanici_adi = ?");
        $stmt->execute([$eposta, $kullanici_adi]);
        if ($stmt->fetch()) {
            $_SESSION['hata'] = 'Bu e-posta veya kullanıcı adı zaten kayıtlı.';
            header('Location: register.php'); exit;
        }

        $stmt = $db->prepare("INSERT INTO saticilar (kullanici_adi, ad_soyad, eposta, telefon, firma_adi, sifre) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$kullanici_adi, $ad_soyad, $eposta, $telefon, $firma_adi, $sifreHash]);
        $yeniId = $db->lastInsertId();

        $_SESSION['kullanici'] = ['id' => $yeniId, 'kullanici_adi' => $kullanici_adi, 'eposta' => $eposta, 'rol' => 'satici'];
        header('Location: ../panels/seller/index.php'); exit;

    } else {
        // E-posta kontrol
        $stmt = $db->prepare("SELECT id FROM alicilar WHERE eposta = ? OR kullanici_adi = ?");
        $stmt->execute([$eposta, $kullanici_adi]);
        if ($stmt->fetch()) {
            $_SESSION['hata'] = 'Bu e-posta veya kullanıcı adı zaten kayıtlı.';
            header('Location: register.php'); exit;
        }

        $stmt = $db->prepare("INSERT INTO alicilar (kullanici_adi, ad_soyad, eposta, telefon, sifre) VALUES (?,?,?,?,?)");
        $stmt->execute([$kullanici_adi, $ad_soyad, $eposta, $telefon, $sifreHash]);
        $yeniId = $db->lastInsertId();

        $_SESSION['kullanici'] = ['id' => $yeniId, 'kullanici_adi' => $kullanici_adi, 'eposta' => $eposta, 'rol' => 'alici'];
        header('Location: ../index.php'); exit;
    }

} catch (PDOException $e) {
    $_SESSION['hata'] = 'Kayıt sırasında bir hata oluştu: ' . $e->getMessage();
    header('Location: register.php'); exit;
}
