<?php
// admin/users.php
require_once __DIR__ . '/../config/db.php';

if (!$aktifKullanici || $aktifKullanici['rol'] !== 'admin') {
    header('Location: ../auth/login.php'); exit;
}

// Silme
if (isset($_GET['sil_alici'])) {
    $db->prepare("DELETE FROM alicilar WHERE id = ?")->execute([(int)$_GET['sil_alici']]);
    header('Location: users.php?mesaj=silindi'); exit;
}
if (isset($_GET['sil_satici'])) {
    $db->prepare("DELETE FROM saticilar WHERE id = ?")->execute([(int)$_GET['sil_satici']]);
    header('Location: users.php?mesaj=silindi'); exit;
}

$mesaj   = $_GET['mesaj'] ?? '';
$alicilar  = $db->query("SELECT *, 'alici' AS rol FROM alicilar ORDER BY id DESC")->fetchAll();
$saticilar = $db->query("SELECT *, 'satici' AS rol FROM saticilar ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcılar - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include __DIR__ . '/partials/admin_style.php'; ?>
</head>
<body>
<?php include __DIR__ . '/partials/sidebar.php'; ?>

<div class="content">
    <div class="page-header">
        <div>
            <div class="page-title">Kullanıcılar</div>
            <div class="page-subtitle">Kayıtlı alıcı ve satıcıları görüntüleyin</div>
        </div>
    </div>

    <?php if ($mesaj === 'silindi'): ?>
        <div class="alert alert-error"><i class="fa-solid fa-trash"></i> Kullanıcı silindi.</div>
    <?php endif; ?>

    <!-- Sekme Butonları -->
    <div style="display:flex;gap:10px;margin-bottom:25px;">
        <button onclick="sekmeGoster('alici')" id="btn-alici" class="btn-orange">Alıcılar (<?= count($alicilar) ?>)</button>
        <button onclick="sekmeGoster('satici')" id="btn-satici" class="btn-gray-btn">Satıcılar (<?= count($saticilar) ?>)</button>
    </div>

    <!-- Alıcılar -->
    <div id="sekme-alici" class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Ad Soyad</th><th>Kullanıcı Adı</th><th>E-Posta</th><th>Telefon</th><th>Kayıt Tarihi</th><th>İşlem</th></tr></thead>
            <tbody>
            <?php if (empty($alicilar)): ?>
                <tr><td colspan="7" style="text-align:center;color:#a0aab2;padding:30px;">Kayıtlı alıcı yok.</td></tr>
            <?php else: ?>
                <?php foreach ($alicilar as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['ad_soyad']) ?></td>
                    <td><?= htmlspecialchars($u['kullanici_adi']) ?></td>
                    <td><?= htmlspecialchars($u['eposta']) ?></td>
                    <td><?= htmlspecialchars($u['telefon'] ?? '—') ?></td>
                    <td><?= date('d.m.Y', strtotime($u['kayit_tarihi'])) ?></td>
                    <td>
                        <a href="users.php?sil_alici=<?= $u['id'] ?>" class="btn-sm btn-red" onclick="return confirm('Bu alıcıyı silmek istiyor musunuz?')"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Satıcılar -->
    <div id="sekme-satici" class="table-wrap" style="display:none;">
        <table>
            <thead><tr><th>#</th><th>Ad Soyad</th><th>Kullanıcı Adı</th><th>Firma</th><th>E-Posta</th><th>Telefon</th><th>Kayıt Tarihi</th><th>İşlem</th></tr></thead>
            <tbody>
            <?php if (empty($saticilar)): ?>
                <tr><td colspan="8" style="text-align:center;color:#a0aab2;padding:30px;">Kayıtlı satıcı yok.</td></tr>
            <?php else: ?>
                <?php foreach ($saticilar as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['ad_soyad']) ?></td>
                    <td><?= htmlspecialchars($u['kullanici_adi']) ?></td>
                    <td><?= htmlspecialchars($u['firma_adi'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($u['eposta']) ?></td>
                    <td><?= htmlspecialchars($u['telefon'] ?? '—') ?></td>
                    <td><?= date('d.m.Y', strtotime($u['kayit_tarihi'])) ?></td>
                    <td>
                        <a href="users.php?sil_satici=<?= $u['id'] ?>" class="btn-sm btn-red" onclick="return confirm('Bu satıcıyı silmek istiyor musunuz?')"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function sekmeGoster(sekme) {
    document.getElementById('sekme-alici').style.display  = sekme === 'alici'  ? 'block' : 'none';
    document.getElementById('sekme-satici').style.display = sekme === 'satici' ? 'block' : 'none';
    document.getElementById('btn-alici').className  = sekme === 'alici'  ? 'btn-orange' : 'btn-gray-btn';
    document.getElementById('btn-satici').className = sekme === 'satici' ? 'btn-orange' : 'btn-gray-btn';
}
</script>
</body>
</html>
