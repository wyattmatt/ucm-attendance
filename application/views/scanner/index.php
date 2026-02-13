<?php

/** @var array $event */ ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
	<title><?= htmlspecialchars($event['name']) ?> - QR Scanner</title>

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
			background-image: url(<?= base_url('uploads/bgabsen-01_6logotengah.gif?q=80&w=2070&auto=format&fit=crop') ?>);
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
		}

		/* Prevent input focus from shifting background */
		.form-control:focus {
			transform: none !important;
			transition: none !important;
		}
	</style>
</head>

<body>
	<a href="<?= site_url('events') ?>" class="btn back-btn"><i class="bi bi-arrow-left me-2"></i>Back</a>
	<input type="hidden" id="event_id" value="<?= htmlspecialchars($event['id']) ?>">
	<div class="scanner-container single-input-mode">
		<h1 class="event-title mb-3"><?= htmlspecialchars($event['name']) ?></h1>
		<p class="desc-text">Silakan masukkan atau scan QR Code Anda untuk melakukan absensi</p>
		<form class="manual-input-form" onsubmit="return handleFormSubmit(event);">
			<div class="input-group input-group-lg justify-content-center" style="max-width:340px;margin:32px auto 0 auto;">
				<input type="text" class="form-control" id="qrCodeInput" placeholder="Scan QR Code" autofocus autocomplete="off">
				<button class="btn btn-success px-4" type="submit" id="manualBtn" title="Absen">
					<i class="bi bi-check-circle-fill me-1"></i> Absen
				</button>
			</div>
		</form>
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
		});

		let isProcessing = false;

		// Char limit
		const CHAR_LIMIT = 100; // Set to null or 0 for no limit

		function extractUUID(input) {
			let result = input;

			// Check if input contains 'uuid=' parameter
// 			if (input.includes('uuid=')) {
// 				try {
// 					// Try to parse as URL first
// 					const url = new URL(input);
// 					const uuid = url.searchParams.get('uuid');
// 					if (uuid) {
// 						result = uuid;
// 					}
// 				} catch (e) {
// 					// If URL parsing fails, extract everything after 'uuid='
// 					const uuidIndex = input.indexOf('uuid=');
// 					if (uuidIndex !== -1) {
// 						// Get everything after 'uuid='
// 						result = input.substring(uuidIndex + 5); // 5 is length of 'uuid='
// 					}
// 				}
// 			}
// 			else {
// 			    result = input.substring(0, 13);
// 			}

            try {
					// Try to parse as URL first
					const url = new URL(input);
					const uuid = url.searchParams.get('uuid');
					if (uuid) {
						result = uuid;
					}
				} catch (e) {
					// If URL parsing fails, extract everything after 'uuid='
					const uuidIndex = input.indexOf('uuid=');
					if (uuidIndex !== -1) {
						// Get everything after 'uuid='
						result = input.substring(uuidIndex + 5); // 5 is length of 'uuid='
					}
				}

			// Apply character limit if set
			if (CHAR_LIMIT && result.length > CHAR_LIMIT) {
				result = result.substring(0, CHAR_LIMIT);
			}

			return result;
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
				Swal.fire({
					icon: 'warning',
					title: 'QR Code Kosong!',
					text: 'Silakan masukkan QR Code terlebih dahulu.',
					background: 'linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(25, 118, 210, 0.4))',
					color: 'white',
					backdrop: 'rgba(0, 0, 0, 0.6)',
					allowOutsideClick: false,
					allowEscapeKey: true,
					heightAuto: false,
					scrollbarPadding: false,
					customClass: {
						popup: 'swal-glass-popup'
					}
				});
				return;
			}

			qrCodeInput.value = '';
			// Extract UUID from URL if present
			const extractedUUID = extractUUID(qrCode);
			processAttendance(extractedUUID);
		}

		document.addEventListener('DOMContentLoaded', function() {
			const qrCodeInput = document.getElementById('qrCodeInput');
			qrCodeInput.focus();
			qrCodeInput.addEventListener('keypress', function(e) {
				if (e.key === 'Enter') {
					processManualQRCode();
				}
			});
		});

		function processAttendance(qrCodeData) {
			if (isProcessing) return;
			isProcessing = true;

			fetch('<?= base_url('attendance/process') ?>', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						event_id: document.getElementById('event_id').value,
						qr_code_data: qrCodeData
					})
				})
				.then(response => response.json())
				.then(data => {
					isProcessing = false;
					if (data.success) {
						Swal.fire({
							icon: 'success',
							title: 'Absensi Berhasil!',
							html: `
							<div style="text-align: left; margin: 20px 0;">
								<p><strong>Kategori:</strong> ${data.student_info.kategori || '-'}</p>
								<p><strong>Nama:</strong> ${data.student_info.name}</p>
								<p><strong>NIS:</strong> ${data.student_info.nis}</p>
								<p><strong>${data.student_info.prodi}</strong> </p>
								<p><strong>Sesi:</strong> ${data.student_info.session_name}</p>
								<p><strong>Waktu:</strong> ${new Date().toLocaleString('id-ID')}</p>
							</div>
							`,
							background: 'linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(25, 118, 210, 0.4))',
							color: 'white',
							backdrop: 'rgba(0, 0, 0, 0.6)',
							allowOutsideClick: false,
							allowEscapeKey: true,
							heightAuto: false,
							scrollbarPadding: false,
							customClass: {
								popup: 'swal-glass-popup'
							},
							timer: 2000,
							timerProgressBar: true
						});
					} else {
						isProcessing = false;
						Swal.fire({
							icon: 'warning',
							title: 'Tidak Dapat Melakukan Absensi',
							text: data.message,
							background: 'linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(25, 118, 210, 0.4))',
							color: 'white',
							backdrop: 'rgba(0, 0, 0, 0.6)',
							allowOutsideClick: false,
							allowEscapeKey: true,
							heightAuto: false,
							scrollbarPadding: false,
							customClass: {
								popup: 'swal-glass-popup'
							},
							timer: 5000,
							timerProgressBar: true
						});
					}
				})
				.catch(error => {
					isProcessing = false;
					Swal.fire({
						icon: 'error',
						title: 'Error!',
						text: 'Terjadi kesalahan saat memproses absensi.',
						background: 'linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(25, 118, 210, 0.4))',
						color: 'white',
						backdrop: 'rgba(0, 0, 0, 0.6)',
						allowOutsideClick: false,
						allowEscapeKey: true,
						heightAuto: false,
						scrollbarPadding: false,
						customClass: {
							popup: 'swal-glass-popup'
						}
					});
				});
		}
	</script>
</body>

</html>
