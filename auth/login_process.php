<?php
// auth/login_process.php
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php'); exit;
}

$eposta = trim($_POST['eposta'] ?? '');
$sifre  = $_POST['sifre'] ?? '';

if (empty($eposta) || empty($sifre)) {
    $_SESSION['hata'] = 'E-posta ve şifre boş bırakılamaz.';
    header('Location: login.php'); exit;
}

try {
    $kullanici = null;
    $rol       = null;

    // 1) Admin mi?
    $stmt = $db->prepare("SELECT * FROM adminler WHERE eposta = ?");
    $stmt->execute([$eposta]);
    $row = $stmt->fetch();
    if ($row && password_verify($sifre, $row['sifre'])) {
        $kullanici = $row;
        $rol = 'admin';
    }

    // 2) Satıcı mı?
    if (!$kullanici) {
        $stmt = $db->prepare("SELECT * FROM saticilar WHERE eposta = ?");
        $stmt->execute([$eposta]);
        $row = $stmt->fetch();
        if ($row && password_verify($sifre, $row['sifre'])) {
            $kullanici = $row;
            $rol = 'satici';
        }
    }

    // 3) Alıcı mı?
    if (!$kullanici) {
        $stmt = $db->prepare("SELECT * FROM alicilar WHERE eposta = ?");
        $stmt->execute([$eposta]);
        $row = $stmt->fetch();
        if ($row && password_verify($sifre, $row['sifre'])) {
            $kullanici = $row;
            $rol = 'alici';
        }
    }

    if (!$kullanici) {
        $_SESSION['hata'] = 'E-posta veya şifre hatalı!';
        header('Location: login.php'); exit;
    }

    // Oturuma kaydet
    $_SESSION['kullanici'] = [
        'id'            => $kullanici['id'],
        'kullanici_adi' => $kullanici['kullanici_adi'],
        'eposta'        => $kullanici['eposta'],
        'rol'           => $rol,
    ];

    // Role göre yönlendir
    if ($rol === 'admin') {
        header('Location: ../admin/index.php'); exit;
    } elseif ($rol === 'satici') {
        header('Location: ../panels/seller/index.php'); exit;
    } else {
        header('Location: ../index.php'); exit;
    }

} catch (PDOException $e) {
    $_SESSION['hata'] = 'Bir hata oluştu, lütfen tekrar deneyin.';
    header('Location: login.php'); exit;
}
