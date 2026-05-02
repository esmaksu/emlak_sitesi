-- =====================================================
-- Emlak Net - Veritabanı Şeması (Temiz Versiyon)
-- =====================================================

-- Varsa eski tabloları temizle
DROP TABLE IF EXISTS ilanlar;
DROP TABLE IF EXISTS saticilar;
DROP TABLE IF EXISTS alicilar;
DROP TABLE IF EXISTS kullanicilar;
DROP TABLE IF EXISTS ayarlar;

-- =====================================================
-- 1. ALICILAR Tablosu
-- =====================================================
CREATE TABLE alicilar (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_adi VARCHAR(50)  NOT NULL UNIQUE,
    ad_soyad      VARCHAR(100) NOT NULL,
    eposta        VARCHAR(100) NOT NULL UNIQUE,
    telefon       VARCHAR(20)  DEFAULT NULL,
    sifre         VARCHAR(255) NOT NULL,
    kayit_tarihi  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Örnek alıcı (şifre: alici123)
INSERT INTO alicilar (kullanici_adi, ad_soyad, eposta, telefon, sifre) VALUES
('ahmet_k', 'Ahmet Kaya', 'ahmet@mail.com', '0555 111 22 33', '$2y$10$1B3VdGcuv5bD0v8F7bPiTuVrPTc5Jw7H3gZkPlFzxwCLXMFgXuNim');

-- =====================================================
-- 2. SATICILAR Tablosu
-- =====================================================
CREATE TABLE saticilar (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_adi VARCHAR(50)  NOT NULL UNIQUE,
    ad_soyad      VARCHAR(100) NOT NULL,
    eposta        VARCHAR(100) NOT NULL UNIQUE,
    telefon       VARCHAR(20)  DEFAULT NULL,
    firma_adi     VARCHAR(150) DEFAULT NULL,
    sifre         VARCHAR(255) NOT NULL,
    kayit_tarihi  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Örnek satıcı (şifre: satici123)
INSERT INTO saticilar (kullanici_adi, ad_soyad, eposta, telefon, firma_adi, sifre) VALUES
('yuksel_emlak', 'Yüksel Demir', 'yuksel@mail.com', '0533 444 55 66', 'Yüksel Emlak Ofisi', '$2y$10$tBXwOyChD1z.nGBwPHBrXOgW3JD5Zgh1tBqSH5y4JYwsTHV.NJC.O');

-- =====================================================
-- 3. İLANLAR Tablosu
-- =====================================================
CREATE TABLE ilanlar (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    satici_id      INT          NOT NULL,                         -- saticilar.id'ye referans
    baslik         VARCHAR(255) NOT NULL,
    aciklama       TEXT         DEFAULT NULL,
    fiyat          DECIMAL(12,2) NOT NULL,
    kategori       ENUM('satilik','kiralik') NOT NULL,            -- Satılık mı Kiralık mı?
    tip            ENUM('konut','arsa','isyeri','arazi','turistik','gunluk') DEFAULT 'konut',
    oda_sayisi     VARCHAR(20)  DEFAULT NULL,                     -- 3+1, 2+1, Stüdyo vb.
    metrekare      INT          DEFAULT NULL,
    il             VARCHAR(100) DEFAULT NULL,
    ilce           VARCHAR(100) DEFAULT NULL,
    adres          VARCHAR(255) DEFAULT NULL,
    resim_yolu     VARCHAR(255) DEFAULT 'assets/images/default.jpg',
    durum          ENUM('aktif','pasif','beklemede') DEFAULT 'aktif',
    eklenme_tarihi TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (satici_id) REFERENCES saticilar(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Örnek İlanlar
INSERT INTO ilanlar (satici_id, baslik, aciklama, fiyat, kategori, tip, oda_sayisi, metrekare, il, ilce, resim_yolu) VALUES
(1, 'Atasoy Gyo Kökçüoğlu 3+1, 12 Yaşında', 'Samsun İlkadımda satılık geniş daire, güney cepheli, 3. kat.', 3730000, 'satilik', 'konut', '3+1', 120, 'Samsun', 'İlkadım', 'assets/images/hero-bg.jpg'),
(1, 'Yüksel Emlaktan 24 Metre Civarı Sıfır Daire', 'Şanlıurfa Haliliye de satılık sıfır daire, ebeveyn banyolu.', 2200000, 'satilik', 'konut', '3+1', 200, 'Şanlıurfa', 'Haliliye', 'assets/images/hero-bg.jpg'),
(1, 'Yüksel Emlaktan Seyrantepe''de Kiralık', 'Şanlıurfa Karaköprü de kiralık, geniş ve aydınlık daire.', 17000, 'kiralik', 'konut', '2+1', 135, 'Şanlıurfa', 'Karaköprü', 'assets/images/hero-bg.jpg');

-- =====================================================
-- 4. ADMİN Tablosu (sadece yönetici hesapları)
-- =====================================================
CREATE TABLE adminler (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_adi VARCHAR(50)  NOT NULL UNIQUE,
    eposta        VARCHAR(100) NOT NULL UNIQUE,
    sifre         VARCHAR(255) NOT NULL,
    kayit_tarihi  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin hesabı (şifre: admin123)
INSERT INTO adminler (kullanici_adi, eposta, sifre) VALUES
('admin', 'admin@emlaknet.com', '$2y$10$ebiCCcwdSm.GtKSgMFVwNewmyQ2uNZ4skQw7MgDqhLVp3Td6zFZXO');

-- =====================================================
-- 5. SİTE AYARLARI Tablosu
-- =====================================================
CREATE TABLE IF NOT EXISTS site_ayarlari (
    id               INT PRIMARY KEY DEFAULT 1,
    site_basligi     VARCHAR(150) DEFAULT 'Emlak Net',
    site_slogani     VARCHAR(255) DEFAULT 'Hayalindeki Evi Bulmak Artık Çok Kolay',
    arkaplan_rengi   VARCHAR(20)  DEFAULT '#121212',
    arkaplan_resmi   VARCHAR(255) DEFAULT 'assets/images/hero-bg.jpg',
    iletisim_telefon VARCHAR(20)  DEFAULT '0850 123 45 67',
    iletisim_eposta  VARCHAR(100) DEFAULT 'info@emlaknet.com'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan ayarları ekle
INSERT IGNORE INTO site_ayarlari (id, site_basligi, site_slogani, arkaplan_rengi, arkaplan_resmi)
VALUES (1, 'Emlak Net', 'Hayalindeki Evi Bulmak Artık Çok Kolay', '#121212', 'assets/images/hero-bg.jpg');
