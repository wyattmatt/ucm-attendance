<?php

/** @var array $event */ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= htmlspecialchars($event['name']) ?> - QR Code Generator</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

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
            background-repeat: no-repeat;
            min-height: 100vh;
            min-height: 100dvh;
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: env(safe-area-inset-top) env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left);
            position: relative;
            overflow: hidden;
            /* Prevent scrollbars that can shift layout */
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100vw;
            height: 100vh;
            background: linear-gradient(135deg, rgba(13, 71, 161, .15), rgba(25, 118, 210, .2), rgba(33, 150, 243, .1), rgba(0, 0, 0, .4));
            z-index: -1;
            pointer-events: none;
            /* Ensure it doesn't interfere with interactions */
        }

        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: rgba(255, 255, 255, .1) !important;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, .2) !important;
            color: #fff !important;
            border-radius: 10px;
            padding: 10px 15px;
            font-weight: 600;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .3);
            transition: all .3s ease;
            text-decoration: none
        }

        .back-btn:hover {
            background: rgba(33, 150, 243, .2) !important;
            color: #fff !important;
            transform: translateY(-2px)
        }

        .scanner-container {
            width: 90%;
            max-width: 500px;
            padding: 40px;
            border-radius: 28px;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.3), rgba(25, 118, 210, 0.2));
            backdrop-filter: blur(20px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.1);
            text-align: center;
            color: white;
            animation: fadeInUp 1s ease-out;
        }

        .single-input-mode {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 350px;
        }

        .event-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #ffffff, #2196f3, #64b5f6);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease-in-out infinite;
        }

        @keyframes gradientShift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .desc-text {
            color: #e0e0e0;
            font-size: 17px;
            margin-bottom: 18px;
            font-weight: 400;
            text-align: center;
        }

        .manual-input-form {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .input-group {
            display: flex;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .input-group .form-control {
            flex: 1;
            border: none;
            border-radius: 12px 0 0 12px;
            background: rgba(255, 255, 255, 0.18);
            color: white;
            font-size: 20px;
            padding: 18px;
            font-weight: 600;
            letter-spacing: 1px;
            box-shadow: none;
            text-align: center;
        }

        .input-group .btn {
            border-radius: 0 12px 12px 0;
            padding: 0 28px;
            font-weight: 700;
            font-size: 20px;
            display: flex;
            align-items: center;
        }

        .btn-success {
            background: linear-gradient(45deg, #43a047, #66bb6a);
            border: none;
            color: #fff;
            box-shadow: 0 4px 16px rgba(76, 175, 80, 0.13);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Prevent layout shifts when SweetAlert appears */
        html {
            overflow: hidden;
            height: 100%;
        }

        html.swal2-shown,
        body.swal2-shown {
            overflow: hidden !important;
            height: 100% !important;
            position: fixed !important;
            width: 100% !important;
        }

        /* Ensure background stays stable during modal interactions */
        .swal2-container {
            backdrop-filter: blur(3px);
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            z-index: 9999 !important;
        }

        .swal2-popup {
            position: relative !important;
            top: auto !important;
            left: auto !important;
            right: auto !important;
            bottom: auto !important;
            transform: none !important;
            margin: 0 !important;
        }

        /* Prevent input focus from shifting background */
        .form-control:focus {
            transform: none !important;
            transition: none !important;
        }

        .qr-display {
            margin-top: 30px;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: none;
            animation: fadeInUp 0.5s ease-out;
        }

        .qr-display.show {
            display: block;
        }

        .qr-display img {
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .qr-text {
            color: #e0e0e0;
            font-size: 14px;
            margin-top: 10px;
            word-break: break-all;
        }

        /* Custom SweetAlert glass styling */
        .swal-glass-popup {
            border-radius: 20px !important;
            backdrop-filter: blur(20px) !important;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .swal2-title {
            color: #fff !important;
            font-weight: 600 !important;
        }

        .swal2-content {
            color: #e0e0e0 !important;
        }

        /* Toast notification styles */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            pointer-events: none;
        }

        .toast-notification {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            margin-bottom: 10px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s ease;
            pointer-events: auto;
            width: auto;
            max-width: 350px;
            white-space: nowrap;
        }

        .toast-notification.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast-success {
            background: rgba(76, 175, 80, 0.9);
            border: 1px solid rgba(76, 175, 80, 0.3);
        }

        .toast-error {
            background: rgba(244, 67, 54, 0.9);
            border: 1px solid rgba(244, 67, 54, 0.3);
        }

        .toast-icon {
            font-size: 18px;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .toast-message {
            flex-grow: 1;
            font-size: 14px;
            line-height: 1.4;
        }
    </style>
</head>

<body>
    <a href="<?= site_url('events') ?>" class="btn back-btn"><i class="bi bi-arrow-left me-2"></i>Back</a>
    <input type="hidden" id="event_id" value="<?= htmlspecialchars($event['id']) ?>">

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    <div class="scanner-container single-input-mode">
        <h1 class="event-title mb-3"><?= htmlspecialchars($event['name']) ?></h1>
        <p class="desc-text">Masukkan NIS Anda untuk melihat QR Code</p>

        <form class="manual-input-form" style="margin-top: -10px;" onsubmit="return handleFormSubmit(event);">
            <div class="input-group input-group-lg justify-content-center" style="max-width:340px;margin:32px auto 0 auto;">
                <input type="text" class="form-control" id="qrCodeInput" placeholder="Masukkan NIS" autofocus autocomplete="off">
                <button class="btn btn-success px-4" type="submit" id="manualBtn" title="Generate QR">
                    <i class="bi bi-qr-code me-1"></i>&nbsp; Show
                </button>
            </div>
        </form>

        <!-- QR Code Display Area -->
        <div id="qrDisplay" class="qr-display text-center">
            <img id="qrImage" src="" alt="Generated QR Code" style="max-width: 100%;">
            <div class="qr-text" id="qrText"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Prevent viewport changes that could affect background
            const viewport = document.querySelector('meta[name=viewport]');
            if (viewport) {
                viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=no');
            }

            // Stabilize body dimensions
            document.body.style.minHeight = window.innerHeight + 'px';

            // Prevent scrolling during modal interactions
            const originalOverflow = document.body.style.overflow;
            window.addEventListener('beforeunload', () => {
                document.body.style.overflow = originalOverflow;
            });

            // Setup input focus and enter key handling
            const qrCodeInput = document.getElementById('qrCodeInput');
            qrCodeInput.focus();
            qrCodeInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    processManualQRCode();
                }
            });
        });

        let isProcessing = false;

        // Toast notification function
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast-notification toast-${type}`;

            const icon = type === 'success' ? '✓' : '✕';
            toast.innerHTML = `
                <div class="toast-icon">${icon}</div>
                <div class="toast-message">${message}</div>
            `;

            container.appendChild(toast);

            // Trigger animation
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

            // Auto remove after 4 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 2000);
        }

        function handleFormSubmit(event) {
            event.preventDefault();
            processManualQRCode();
            return false;
        }

        function processManualQRCode() {
            if (isProcessing) return;

            const qrCodeInput = document.getElementById('qrCodeInput');
            const qrCode = qrCodeInput.value.trim();
            if (!qrCode) {
                showToast('Silakan masukkan text.', 'error');
                return;
            }

            generateQRCode(qrCode);
            qrCodeInput.value = '';
        }



        function generateQRCode(inputText) {
            if (isProcessing) return;
            isProcessing = true;

            // Show loading state
            const qrDisplay = document.getElementById('qrDisplay');
            const qrImage = document.getElementById('qrImage');
            const qrText = document.getElementById('qrText');

            // Create QR code URL using the specified API format
            const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(inputText)}`;

            // Display the QR code
            qrImage.src = qrCodeUrl;
            qrText.textContent = inputText;
            qrDisplay.classList.add('show');

            isProcessing = false;

            // Show success toast
            showToast(`QR Code berhasil dibuat`, 'success');
        }
    </script>
</body>

</html>