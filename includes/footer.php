<?php
// includes/footer.php
?>
<footer style="background: var(--white); padding: 40px 20px; text-align: center; border-top: 1px solid var(--border-color); margin-top: 40px; color: var(--text-muted);">
    <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($siteAyarlari['site_basligi'] ?? 'Emlak Sitesi') ?>. Tüm Hakları Saklıdır.</p>
    <p style="margin-top: 10px;">İletişim: <?= htmlspecialchars($siteAyarlari['iletisim_eposta'] ?? 'info@ornek.com') ?></p>
</footer>

</body>
</html>
