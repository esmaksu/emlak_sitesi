<?php
// includes/header.php
require_once __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteAyarlari['site_basligi'] ?? 'Emlak Net') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background-color: <?= htmlspecialchars($siteAyarlari['arkaplan_rengi'] ?? '#121212') ?>; }
        .hero { background-image: url('<?= htmlspecialchars($siteAyarlari['arkaplan_resmi'] ?? 'assets/images/hero-bg.jpg') ?>') !important; }

        /* Nav Dropdown Menü - JS ile açılır */
        .nav-dropdown { position: relative; }
        .nav-dropdown > a {
            display: flex; align-items: center; gap: 5px;
            transition: color 0.2s; cursor: pointer;
        }
        .nav-dropdown > a:hover { color: #ff6b00 !important; }
        .nav-dropdown > a .fa-angle-down { transition: transform 0.3s; font-size: 11px; }
        .nav-dropdown.acik > a .fa-angle-down { transform: rotate(180deg); }

        .nav-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 2px);
            left: 0;
            background: #1e1e1e;
            border: 1px solid #2e2e2e;
            border-radius: 12px;
            min-width: 210px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.5);
            overflow: hidden;
            z-index: 9999;
            animation: fadeDown 0.2s ease;
        }
        @keyframes fadeDown {
            from { opacity:0; transform: translateY(-6px); }
            to   { opacity:1; transform: translateY(0); }
        }
        .nav-dropdown.acik .nav-dropdown-menu { display: block; }
        .nav-dropdown-menu a {
            display: block;
            padding: 12px 22px;
            color: #d0d0d0;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.15s, color 0.15s;
            border-bottom: 1px solid #2a2a2a;
        }
        .nav-dropdown-menu a:last-child { border-bottom: none; }
        .nav-dropdown-menu a:hover { background: rgba(255,107,0,0.1); color: #ff6b00; padding-left: 28px; }

        /* Kullanıcı Dropdown - JS ile açılır */
        .dropdown { position: relative; }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 2px);
            right: 0;
            background: #1e1e1e;
            border: 1px solid #333;
            border-radius: 12px;
            min-width: 210px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            overflow: hidden;
            z-index: 9999;
            animation: fadeDown 0.2s ease;
        }
        .dropdown.acik .dropdown-menu { display: block; }
        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 20px;
            color: #f8f9fa;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }
        .dropdown-menu a:hover { background: rgba(255,107,0,0.1); color: #ff6b00; }
        .dropdown-menu a.danger { color: #ff6b6b; }
        .dropdown-menu a.danger:hover { background: rgba(255,50,50,0.1); color: #ff6b6b; }
        .dropdown-menu hr { border: none; border-top: 1px solid #333; margin: 4px 0; }
        .avatar-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,107,0,0.12);
            border: 1px solid rgba(255,107,0,0.3);
            padding: 8px 16px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s;
            color: #ff6b00;
            font-weight: 600;
            font-size: 14px;
        }
        .avatar-btn:hover { background: rgba(255,107,0,0.2); }
        .avatar-icon {
            width: 32px; height: 32px;
            background: #ff6b00;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: 700;
        }
        .rol-badge {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 20px;
            margin-left: 5px;
            font-weight: 600;
        }
        .rol-badge.admin { background: rgba(255,107,0,0.2); color: #ff6b00; }
        .rol-badge.satici { background: rgba(50,150,255,0.15); color: #60a5fa; }
        .rol-badge.alici { background: rgba(50,200,80,0.15); color: #6bff8e; }
    </style>
</head>
<body>

<header class="header">
    <a href="/index.php" class="logo">
        <i class="fa-solid fa-house-chimney"></i>
        Emlak Net
    </a>
    <nav class="header-menu">

        <!-- Satılık -->
        <div class="nav-dropdown">
            <a href="#">Satılık <i class="fa-solid fa-angle-down"></i></a>
            <div class="nav-dropdown-menu">
                <a href="/ilanlar.php?tip=konut&kategori=satilik">Konut</a>
                <a href="/ilanlar.php?tip=arsa&kategori=satilik">Arsa</a>
                <a href="/ilanlar.php?tip=kat-karsiligi&kategori=satilik">Kat Karşılığı Arsa</a>
                <a href="/ilanlar.php?tip=isyeri&kategori=satilik">İşyeri</a>
                <a href="/ilanlar.php?tip=devren-isyeri&kategori=satilik">Devren İşyeri</a>
                <a href="/ilanlar.php?tip=turistik-tesis&kategori=satilik">Turistik Tesis</a>
            </div>
        </div>

        <!-- Kiralık -->
        <div class="nav-dropdown">
            <a href="#">Kiralık <i class="fa-solid fa-angle-down"></i></a>
            <div class="nav-dropdown-menu">
                <a href="/ilanlar.php?tip=konut&kategori=kiralik">Konut</a>
                <a href="/ilanlar.php?tip=gunluk-kiralik&kategori=kiralik">Günlük Kiralık Konut</a>
                <a href="/ilanlar.php?tip=arsa&kategori=kiralik">Arsa</a>
                <a href="/ilanlar.php?tip=isyeri&kategori=kiralik">İşyeri</a>
                <a href="/ilanlar.php?tip=turistik-tesis&kategori=kiralik">Turistik Tesis</a>
            </div>
        </div>

        <!-- Projeler -->
        <div class="nav-dropdown">
            <a href="#">Projeler <i class="fa-solid fa-angle-down"></i></a>
            <div class="nav-dropdown-menu">
                <a href="/projeler.php?tip=konut">Konut Projeleri</a>
                <a href="/projeler.php?tip=ticari">Ticari Projeler</a>
                <a href="/projeler.php?tip=karma">Karma Projeler</a>
            </div>
        </div>

        <!-- Hizmetlerimiz -->
        <div class="nav-dropdown">
            <a href="#">Hizmetlerimiz <i class="fa-solid fa-angle-down"></i></a>
            <div class="nav-dropdown-menu">
                <a href="#">Değerleme Raporu</a>
                <a href="#">Emlak Danışmanlığı</a>
                <a href="#">Tapu ve Hukuki İşlemler</a>
                <a href="#">Kredi Hesaplama</a>
            </div>
        </div>

    </nav>
    <div class="header-menu" style="align-items:center;">
        <?php if ($aktifKullanici): ?>
            <!-- Giriş yapmış kullanıcı menüsü -->
            <div class="dropdown">
                <div class="avatar-btn">
                    <div class="avatar-icon"><?= strtoupper(mb_substr($aktifKullanici['kullanici_adi'], 0, 1)) ?></div>
                    <?= htmlspecialchars($aktifKullanici['kullanici_adi']) ?>
                    <span class="rol-badge <?= $aktifKullanici['rol'] ?>">
                        <?= $aktifKullanici['rol'] === 'admin' ? 'Admin' : ($aktifKullanici['rol'] === 'satici' ? 'Satıcı' : 'Alıcı') ?>
                    </span>
                    <i class="fa-solid fa-angle-down" style="font-size:12px;"></i>
                </div>
                <div class="dropdown-menu">
                    <?php if ($aktifKullanici['rol'] === 'admin'): ?>
                        <a href="/admin/index.php"><i class="fa-solid fa-gauge"></i> Admin Paneli</a>
                        <hr>
                    <?php elseif ($aktifKullanici['rol'] === 'satici'): ?>
                        <a href="/panels/seller/index.php"><i class="fa-solid fa-store"></i> Satıcı Panelim</a>
                        <a href="/panels/seller/add_listing.php"><i class="fa-solid fa-plus"></i> İlan Ekle</a>
                        <hr>
                    <?php else: ?>
                        <a href="/panels/buyer/index.php"><i class="fa-solid fa-user"></i> Profilim</a>
                        <a href="/panels/buyer/favorites.php"><i class="fa-solid fa-heart"></i> Favorilerim</a>
                        <hr>
                    <?php endif; ?>
                    <a href="/auth/logout.php" class="danger"><i class="fa-solid fa-right-from-bracket"></i> Çıkış Yap</a>
                </div>
            </div>
            <a href="#" class="btn-primary" style="margin-left:10px;">
                <i class="fa-solid fa-plus"></i> Ücretsiz İlan Ver
            </a>
        <?php else: ?>
            <!-- Giriş yapmamış kullanıcı -->
            <a href="/auth/login.php" style="color:#f8f9fa; font-weight:500; padding: 8px 14px;">
                <i class="fa-solid fa-right-to-bracket"></i> Giriş Yap
            </a>
            <a href="/auth/register.php" class="btn-primary">
                <i class="fa-solid fa-user-plus"></i> Kayıt Ol
            </a>
        <?php endif; ?>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function tumunuKapat() {
        document.querySelectorAll('.nav-dropdown.acik, .dropdown.acik').forEach(function(el) {
            el.classList.remove('acik');
        });
    }

    // Nav dropdown'lar (Satılık, Kiralık vb.) - tıkla/aç
    document.querySelectorAll('.nav-dropdown > a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var parent = this.closest('.nav-dropdown');
            var zatenAcik = parent.classList.contains('acik');
            tumunuKapat();
            if (!zatenAcik) parent.classList.add('acik');
        });
    });

    // Kullanıcı avatar dropdown - tıkla/aç
    var avatarBtn = document.querySelector('.avatar-btn');
    if (avatarBtn) {
        avatarBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            var parent = this.closest('.dropdown');
            var zatenAcik = parent.classList.contains('acik');
            tumunuKapat();
            if (!zatenAcik) parent.classList.add('acik');
        });
    }

    // Dışarıya tıklayınca kapat
    document.addEventListener('click', tumunuKapat);

    // Menü içindeki linklere tıklayınca sayfaya gidebilsin
    document.querySelectorAll('.nav-dropdown-menu a, .dropdown-menu a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

});
</script>
