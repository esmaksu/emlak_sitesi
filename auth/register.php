<?php
// auth/register.php
require_once __DIR__ . '/../config/db.php';

if ($aktifKullanici) {
    header('Location: ../index.php'); exit;
}

$hata    = $_SESSION['hata'] ?? '';
$basari  = $_SESSION['basari'] ?? '';
unset($_SESSION['hata'], $_SESSION['basari']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - <?= htmlspecialchars($siteAyarlari['site_basligi']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }

        body {
            background: #121212;
            color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            overflow-y: auto;
        }

        body::before {
            content: '';
            position: fixed;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(ellipse at 40% 60%, rgba(255,107,0,0.12) 0%, transparent 60%);
            pointer-events: none;
        }

        .auth-wrapper {
            display: flex;
            width: 950px;
            max-width: 95vw;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
            position: relative;
            z-index: 1;
        }

        /* Sağ: Dekoratif */
        .auth-panel-right {
            flex: 1;
            background: url('../assets/images/hero-bg.jpg') center/cover no-repeat;
            position: relative;
            min-height: 100%;
        }
        .auth-panel-right::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.75), rgba(255,107,0,0.5));
        }
        .auth-panel-right-content {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            text-align: center;
        }
        .auth-panel-right-content h3 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        .auth-panel-right-content p {
            color: rgba(255,255,255,0.85);
            font-size: 15px;
        }

        /* Sol: Form */
        .auth-panel-left {
            flex: 1.2;
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            padding: 45px 50px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .logo {
            font-size: 26px;
            font-weight: 800;
            color: #ff6b00;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .auth-panel-left h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .auth-panel-left > p {
            color: #a0aab2;
            margin-bottom: 25px;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #a0aab2;
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 13px 16px;
            background: #2a2a2a;
            border: 1px solid #333;
            border-radius: 10px;
            color: #f8f9fa;
            font-size: 15px;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group select option { background: #2a2a2a; }

        .form-group input:focus, .form-group select:focus {
            border-color: #ff6b00;
            box-shadow: 0 0 0 3px rgba(255,107,0,0.15);
        }

        /* Rol Seçici */
        .rol-secici {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .rol-kart {
            flex: 1;
            background: #2a2a2a;
            border: 2px solid #333;
            border-radius: 12px;
            padding: 16px 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .rol-kart i { font-size: 24px; color: #a0aab2; display: block; margin-bottom: 8px; transition: color 0.3s; }
        .rol-kart span { font-size: 13px; font-weight: 600; color: #a0aab2; transition: color 0.3s; }
        .rol-kart.aktif {
            border-color: #ff6b00;
            background: rgba(255,107,0,0.1);
        }
        .rol-kart.aktif i, .rol-kart.aktif span { color: #ff6b00; }
        .rol-kart:hover { border-color: #ff6b00; }

        .btn-register {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #ff6b00, #e65c00);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255,107,0,0.35);
        }

        .error-box {
            background: rgba(255,50,50,0.12);
            border: 1px solid rgba(255,50,50,0.4);
            color: #ff6b6b;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .success-box {
            background: rgba(50,200,80,0.1);
            border: 1px solid rgba(50,200,80,0.4);
            color: #6bff8e;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .auth-link {
            text-align: center;
            margin-top: 20px;
            color: #a0aab2;
            font-size: 14px;
        }
        .auth-link a { color: #ff6b00; font-weight: 600; text-decoration: none; }
        .auth-link a:hover { text-decoration: underline; }

        .home-link {
            position: absolute;
            top: 25px;
            left: 30px;
            color: #a0aab2;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.3s;
            z-index: 10;
        }
        .home-link:hover { color: #ff6b00; }

        @media(max-width: 700px) {
            .auth-panel-right { display: none; }
        }
    </style>
</head>
<body>

<a href="../index.php" class="home-link"><i class="fa-solid fa-arrow-left"></i> Ana Sayfa</a>

<div class="auth-wrapper">
    <!-- Sol: Form -->
    <div class="auth-panel-left">
        <div class="logo"><i class="fa-solid fa-house-chimney"></i> Emlak Net</div>
        <h2>Hesap Oluşturun</h2>
        <p>Binlerce ilan arasında yerinizi alın.</p>

        <?php if($hata): ?>
        <div class="error-box"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($hata) ?></div>
        <?php endif; ?>
        <?php if($basari): ?>
        <div class="success-box"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($basari) ?></div>
        <?php endif; ?>

        <form action="register_process.php" method="POST">
            <!-- Rol Seçimi -->
            <label style="font-size:12px; font-weight:600; color:#a0aab2; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:10px; display:block;">Hesap Türü Seçin</label>
            <div class="rol-secici">
                <div class="rol-kart aktif" id="kart-alici" onclick="rolSec('alici')">
                    <i class="fa-solid fa-user-tie"></i>
                    <span>Alıcı</span>
                </div>
                <div class="rol-kart" id="kart-satici" onclick="rolSec('satici')">
                    <i class="fa-solid fa-store"></i>
                    <span>Satıcı</span>
                </div>
            </div>
            <input type="hidden" name="rol" id="rol_input" value="alici">

            <div class="form-group">
                <label>Ad Soyad</label>
                <input type="text" name="ad_soyad" id="ad_soyad" placeholder="Adınız ve soyadınız" required>
            </div>
            <div class="form-group">
                <label>Kullanıcı Adı</label>
                <input type="text" name="kullanici_adi" id="kullanici_adi" placeholder="Kullanıcı adınız" required>
            </div>
            <div class="form-group">
                <label>Telefon</label>
                <input type="tel" name="telefon" id="telefon" placeholder="05XX XXX XX XX">
            </div>
            <div class="form-group" id="firma_alani" style="display:none;">
                <label>Firma / Ofis Adı</label>
                <input type="text" name="firma_adi" id="firma_adi" placeholder="Emlak ofisi veya şirket adı">
            </div>
            <div class="form-group">
                <label>E-Posta Adresi</label>
                <input type="email" name="eposta" id="register_eposta" placeholder="ornek@mail.com" required>
            </div>
            <div class="form-group">
                <label>Şifre</label>
                <input type="password" name="sifre" id="register_sifre" placeholder="En az 6 karakter" required>
            </div>
            <button type="submit" class="btn-register"><i class="fa-solid fa-user-plus"></i> Kayıt Ol</button>
        </form>

        <div class="auth-link">
            Zaten hesabınız var mı? <a href="login.php">Giriş Yapın</a>
        </div>
    </div>

    <!-- Sağ: Dekoratif -->
    <div class="auth-panel-right">
        <div class="auth-panel-right-content">
            <h3>Emlak Net ile<br>İlanınızı Yayınlayın</h3>
            <p>Satıcı hesabı açarak binlerce alıcıya ulaşın, alıcı hesabıyla hayalinizdeki evi keşfedin.</p>
        </div>
    </div>
</div>

<script>
function rolSec(rol) {
    document.getElementById('rol_input').value = rol;
    document.getElementById('kart-alici').classList.toggle('aktif', rol === 'alici');
    document.getElementById('kart-satici').classList.toggle('aktif', rol === 'satici');
    document.getElementById('firma_alani').style.display = (rol === 'satici') ? 'block' : 'none';
}
</script>

</body>
</html>
