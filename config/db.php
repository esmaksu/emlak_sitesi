<?php
// config/db.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host     = 'localhost';
$dbname   = 'emlak_sitesi';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Aktif oturum bilgisi
$aktifKullanici = $_SESSION['kullanici'] ?? null;

// Site Ayarlarını Veritabanından Çek (Tablo yoksa otomatik oluştur)
try {
    $checkTable = $db->query("SHOW TABLES LIKE 'site_ayarlari'")->rowCount();
    if ($checkTable == 0) {
        $db->exec("
            CREATE TABLE site_ayarlari (
                id INT PRIMARY KEY DEFAULT 1,
                site_basligi VARCHAR(150) DEFAULT 'Emlak Net',
                site_slogani VARCHAR(255) DEFAULT 'Hayalindeki Evi Bulmak Artık Çok Kolay',
                arkaplan_rengi VARCHAR(20) DEFAULT '#121212',
                arkaplan_resmi VARCHAR(255) DEFAULT 'assets/images/hero-bg.jpg',
                iletisim_telefon VARCHAR(20) DEFAULT '0850 123 45 67',
                iletisim_eposta VARCHAR(100) DEFAULT 'info@emlaknet.com'
            );
            INSERT INTO site_ayarlari (id, site_basligi) VALUES (1, 'Emlak Net');
        ");
    }
    $siteAyarlari = $db->query("SELECT * FROM site_ayarlari WHERE id = 1")->fetch();
} catch (Exception $e) {
    // Hata durumunda fallback statik ayarlar
    $siteAyarlari = [
        'site_basligi' => 'Emlak Net',
        'site_slogani' => 'Hayalindeki Evi Bulmak Artık Çok Kolay',
        'arkaplan_rengi' => '#121212',
        'arkaplan_resmi' => 'assets/images/hero-bg.jpg'
    ];
}
?>
