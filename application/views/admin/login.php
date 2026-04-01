<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - UCM Attendance</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/user.css') ?>">
    <style>
        .login-card {
            max-width: 420px;
            width: 90%;
            padding: 48px 40px;
        }

        .login-title {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            text-align: center;
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
            text-align: center;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .login-error {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <defs>
            <filter id="glass-distortion" x="0%" y="0%" width="100%" height="100%">
                <feTurbulence type="fractalNoise" baseFrequency="0.008 0.008" numOctaves="2" seed="92" result="noise" />
                <feGaussianBlur in="noise" stdDeviation="2" result="blurred" />
                <feDisplacementMap in="SourceGraphic" in2="blurred" scale="77" xChannelSelector="R" yChannelSelector="G" />
            </filter>
        </defs>
    </svg>
    <div class="page-wrapper">
        <div class="glass-card login-card">
            <h2 class="login-title">Admin Login</h2>
            <p class="login-subtitle">UCM Attendance System</p>

            <?php if (isset($error) && $error): ?>
                <div class="login-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <?= form_open('auth/login') ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="glass-input" placeholder="admin@ciputra.ac.id" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="glass-input" placeholder="Password" required>
            </div>
            <button type="submit" class="glass-btn" style="width:100%; margin-top: 8px;">Login</button>
            <?= form_close() ?>
        </div>
    </div>
</body>

</html>