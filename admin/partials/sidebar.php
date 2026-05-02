<?php
// Hangi sayfa aktif?
$sayfaAdi = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="sidebar-logo"><i class="fa-solid fa-house-chimney"></i> Emlak Net</div>
    <nav>
        <a href="index.php"       class="<?= $sayfaAdi==='index.php'?'aktif':'' ?>"><i class="fa-solid fa-gauge"></i> Gösterge Paneli</a>
        <a href="listings.php"    class="<?= $sayfaAdi==='listings.php'||$sayfaAdi==='add_listing.php'?'aktif':'' ?>"><i class="fa-solid fa-list"></i> İlan Yönetimi</a>
        <a href="users.php"       class="<?= $sayfaAdi==='users.php'?'aktif':'' ?>"><i class="fa-solid fa-users"></i> Kullanıcılar</a>
        <a href="settings.php"    class="<?= $sayfaAdi==='settings.php'?'aktif':'' ?>"><i class="fa-solid fa-gear"></i> Site Ayarları</a>
        <a href="../index.php"><i class="fa-solid fa-arrow-left"></i> Siteye Dön</a>
    </nav>
    <div class="sidebar-footer">
        <a href="../auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Çıkış Yap</a>
    </div>
</aside>
