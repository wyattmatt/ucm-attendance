<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCM Attendance</title>
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
        <div class="glass-container">
            <div class="container-header">
                <h1 class="app-title">UCM Attendance</h1>
                <p class="app-subtitle">Universitas Ciputra Makassar</p>
            </div>
            <div class="events-list" id="eventsList">
                <?php if (empty($events)): ?>
                    <div class="empty-state">
                        <p>Belum ada event yang tersedia.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <a href="<?= base_url('home/attend/' . $event->id) ?>" class="event-card">
                            <div class="event-info">
                                <h3 class="event-name"><?= htmlspecialchars($event->name, ENT_QUOTES, 'UTF-8') ?></h3>
                                <p class="event-date">
                                    <?= date('d M Y', strtotime($event->start_date)) ?>
                                    <?php if ($event->start_date !== $event->end_date): ?>
                                        - <?= date('d M Y', strtotime($event->end_date)) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="event-status">
                                <span class="badge badge-<?= $event->status ?>">
                                    <?= $event->status === 'upcoming' ? 'Akan Datang' : 'Berlangsung' ?>
                                </span>
                            </div>
                            <div class="event-arrow">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
