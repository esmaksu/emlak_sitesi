<?php
// auth/login.php
require_once __DIR__ . '/../config/db.php';

// Zaten giriş yapmışsa yönlendir
if ($aktifKullanici) {
    if ($aktifKullanici['rol'] === 'admin') {
        header('Location: ../admin/index.php'); exit;
    }
    header('Location: ../index.php'); exit;
}

$hata = $_SESSION['hata'] ?? '';
unset($_SESSION['hata']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap - <?= htmlspecialchars($siteAyarlari['site_basligi']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }

        body {
            background: #121212;
            color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
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
            background: radial-gradient(ellipse at 60% 40%, rgba(255,107,0,0.12) 0%, transparent 60%);
            pointer-events: none;
        }

        .auth-wrapper {
            display: flex;
            width: 900px;
            max-width: 95vw;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
            position: relative;
            z-index: 1;
        }

        /* Sol Panel */
        .auth-panel-left {
            flex: 1;
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: #ff6b00;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 40px;
        }

        .auth-panel-left h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .auth-panel-left p {
            color: #a0aab2;
            margin-bottom: 35px;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #a0aab2;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 18px;
            background: #2a2a2a;
            border: 1px solid #333;
            border-radius: 10px;
            color: #f8f9fa;
            font-size: 15px;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input:focus {
            border-color: #ff6b00;
            box-shadow: 0 0 0 3px rgba(255,107,0,0.15);
        }

        .btn-login {
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
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255,107,0,0.35);
        }

        .error-box {
            background: rgba(255,50,50,0.12);
            border: 1px solid rgba(255,50,50,0.4);
            color: #ff6b6b;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .auth-link {
            text-align: center;
            margin-top: 25px;
            color: #a0aab2;
            font-size: 14px;
        }

        .auth-link a {
            color: #ff6b00;
            font-weight: 600;
            text-decoration: none;
        }

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

        /* Sağ Panel - Dekoratif */
        .auth-panel-right {
            flex: 1;
            background: url('../assets/images/hero-bg.jpg') center/cover no-repeat;
            position: relative;
            min-height: 550px;
        }
        .auth-panel-right::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,107,0,0.6), rgba(0,0,0,0.7));
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
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        .auth-panel-right-content p {
            color: rgba(255,255,255,0.85);
            font-size: 15px;
        }

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
        <h2>Tekrar Hoşgeldiniz!</h2>
        <p>Hesabınıza giriş yaparak ilanları keşfedin.</p>

        <?php if($hata): ?>
        <div class="error-box"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($hata) ?></div>
        <?php endif; ?>

        <form action="login_process.php" method="POST">
            <div class="form-group">
                <label>E-Posta Adresi</label>
                <input type="email" name="eposta" id="eposta" placeholder="ornek@mail.com" required autofocus>
            </div>
            <div class="form-group">
                <label>Şifre</label>
                <input type="password" name="sifre" id="sifre" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-login"><i class="fa-solid fa-right-to-bracket"></i> Giriş Yap</button>
        </form>

        <div class="auth-link">
            Hesabınız yok mu? <a href="register.php">Hemen Kayıt Olun</a>
        </div>
    </div>

    <!-- Sağ: Dekoratif -->
    <div class="auth-panel-right">
        <div class="auth-panel-right-content">
            <h3>Hayalinizdeki Evi<br>Bulmak Artık Kolay</h3>
            <p>Binlerce ilan arasından size en uygun olanı kolayca bulun.</p>
        </div>
    </div>
</div>

</body>
</html>
