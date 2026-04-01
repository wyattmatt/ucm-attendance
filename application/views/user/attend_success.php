<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berhasil - UCM Attendance</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/user.css') ?>">
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
        <div class="glass-card success-card">
            <div class="success-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <h2 class="success-title">Kehadiran Tercatat!</h2>
            <p class="success-event"><?= htmlspecialchars($event->name, ENT_QUOTES, 'UTF-8') ?></p>
            <p class="success-time"><?= date('d M Y, H:i') ?> WITA</p>
            <a href="<?= base_url() ?>" class="glass-btn">Kembali ke Beranda</a>
        </div>
    </div>
</body>

</html>