<?php

/** @var array $events */ ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Ciputra University - Attendance Registration</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-image: url('<?= base_url('uploads/bgabsen-01_6logotengah.gif?q=80&w=2070&auto=format&fit=crop') ?>');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      min-height: 100vh;
      min-height: 100dvh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      padding: env(safe-area-inset-top) env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left);
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: linear-gradient(135deg, rgba(13, 71, 161, .15), rgba(25, 118, 210, .2), rgba(33, 150, 243, .1), rgba(0, 0, 0, .4));
      z-index: -1;
    }

    .main-container {
      width: 380px;
      border-radius: 28px;
      overflow: hidden;
      box-shadow: 0 25px 60px rgba(0, 0, 0, .6), 0 0 0 1px rgba(255, 255, 255, .1);
      backdrop-filter: blur(20px);
      animation: fadeInUp 1s ease-out, float 6s ease-in-out infinite;
      position: relative;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px)
      }

      to {
        opacity: 1;
        transform: translateY(0)
      }
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0)
      }

      50% {
        transform: translateY(-5px)
      }
    }

    .section {
      padding: 32px 28px;
      color: #fff;
      text-align: center;
      position: relative;
    }

    .top {
      background: linear-gradient(135deg, rgba(0, 0, 0, .4), rgba(13, 71, 161, .2));
      border-bottom: 1px solid rgba(255, 255, 255, .15);
    }

    .middle {
      background: linear-gradient(135deg, rgba(0, 0, 0, .4), rgba(13, 71, 161, .2));
    }

    .bottom {
      background: linear-gradient(135deg, rgba(0, 0, 0, .4), rgba(13, 71, 161, .2));
      border-top: 1px solid rgba(255, 255, 255, .15);
    }

    .top h1 {
      font-size: 28px;
      margin: 0 0 8px;
      letter-spacing: 1px;
      font-weight: 700;
      background: linear-gradient(45deg, #fff, #2196f3, #64b5f6);
      background-size: 200% 200%;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: gradientShift 3s ease-in-out infinite;
      text-shadow: 0 2px 10px rgba(0, 0, 0, .3);
    }

    @keyframes gradientShift {

      0%,
      100% {
        background-position: 0% 50%
      }

      50% {
        background-position: 100% 50%
      }
    }

    .top p {
      font-size: 15px;
      color: #e0e0e0;
      margin-top: 12px;
      line-height: 1.5;
      font-weight: 300;
      opacity: .9;
    }

    .menu-item {
      margin: 16px 0;
      font-size: 17px;
      font-weight: 500;
      /* transition: all .3s ease; */
      cursor: pointer;
      padding: 12px 20px;
      border-radius: 12px;
      position: relative;
      overflow: hidden;
      border: 1px solid transparent;
      opacity: 0;
      animation: slideInLeft .6s ease-out forwards;
      /* background: rgba(255, 255, 255, .02); */
      /* backdrop-filter: blur(5px); */
    }

    .menu-item:nth-child(1) {
      animation-delay: .1s
    }

    .menu-item:nth-child(2) {
      animation-delay: .2s
    }

    .menu-item:nth-child(3) {
      animation-delay: .3s
    }

    .menu-item:nth-child(4) {
      animation-delay: .4s
    }

    .menu-item:nth-child(5) {
      animation-delay: .5s
    }

    .menu-item:nth-child(6) {
      animation-delay: .6s
    }

    .menu-item::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .15), transparent);
      transition: left .6s ease
    }

    .menu-item:hover {
      color: #fff;
      background: rgba(255, 255, 255, .15);
      border-color: rgba(255, 255, 255, .25);
      transform: translateY(-2px);
      backdrop-filter: blur(10px)
    }

    .menu-item:hover::before {
      left: 100%
    }

    .bottom p {
      font-size: 14px;
      font-weight: 600;
      margin: 0;
      line-height: 1.5;
    }

    .bottom span {
      display: block;
      color: #2196f3;
      font-weight: 700;
      margin-top: 4px;
      text-shadow: 0 0 10px rgba(33, 150, 243, .5);
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-30px)
      }

      to {
        opacity: 1;
        transform: translateX(0)
      }
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 1
      }

      50% {
        opacity: .7
      }
    }

    .admin-btn {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      font-size: 12px;
      margin-top: 10px;
      display: inline-block;
      padding: 8px 16px;
      border-radius: 8px;
      transition: all .3s ease;
      border: 1px solid transparent;
    }

    .admin-btn:hover {
      color: #fff;
      background: rgba(255, 255, 255, .1);
      border-color: rgba(255, 255, 255, .2);
      backdrop-filter: blur(10px);
      transform: translateY(-1px);
      text-decoration: none;
    }

    @media (max-width:768px) {
      .main-container {
        width: 85%;
        margin: 0 auto;
        border-radius: 24px
      }

      .section {
        padding: 28px 24px
      }

      .top h1 {
        font-size: 23px
      }

      .top p {
        font-size: 14px
      }

      .menu-item {
        font-size: 16px;
        padding: 13px 20px;
        margin: 15px 0
      }
    }

    @media (max-width:450px) {
      body {
        padding: 20px 0;
        align-items: flex-start;
        padding-top: max(20px, env(safe-area-inset-top));
        padding-bottom: max(20px, env(safe-area-inset-bottom))
      }

      .main-container {
        width: 85%;
        max-height: calc(100dvh - 40px);
        margin: 20px auto;
        border-radius: 20px
      }

      .section {
        padding: 18px 16px
      }

      .top h1 {
        font-size: 21px
      }

      .top p {
        font-size: 13px
      }

      .menu-item {
        font-size: 15px;
        padding: 10px 14px;
        margin: 10px 0
      }

      .bottom p {
        font-size: 12px
      }

      .bottom span {
        font-size: 13px;
        margin-top: 3px
      }
    }
  </style>
</head>

<body>
  <div class="main-container">
    <div class="section top">
      <!-- <h1>CIPUTRA<br>UNIVERSITY</h1> -->
      <p><img src="<?= base_url('uploads/UCM.png') ?>" alt="Italian Trulli" style="width:175px;height:175px;"></p>
    </div>

    <div class="section middle">
      <?php if (!empty($events)): ?>
        <?php foreach ($events as $event): ?>
          <div class="menu-item" onclick="redirectToEvent('<?= $event['id'] ?>')">
            <?= htmlspecialchars($event['name'] ?? $event['id']) ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="menu-item">No active events</div>
      <?php endif; ?>
    </div>
    <div class="section bottom">
      <span><a href="<?= site_url('admin') ?>" class="admin-btn">
          <i class="fa fa-dashboard"></i>&nbsp; Admin Dashboard
        </a></span></p>
      <span><a href="<?= site_url('qrcode') ?>" class="admin-btn">
          <i class="fa fa-qrcode"></i>&nbsp; Show QR Code
        </a></span></p>
      <span><a href="<?= site_url('print') ?>" class="admin-btn">
          <i class="fa fa-print"></i>&nbsp; Show Seat No
        </a></span></p>

    </div>

  </div>
  <script>
    function redirectToEvent(eventId) {
      window.location.href = <?= json_encode(site_url('scanner')) ?> + '/' + encodeURIComponent(eventId);
    }
  </script>
</body>

</html>