<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= htmlspecialchars($event->name, ENT_QUOTES, 'UTF-8') ?> - UCM Attendance</title>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?= base_url('assets/css/user.css') ?>">
</head>

<body<?php if (!empty($event->background_image)): ?> style="background-image: url('<?= base_url('assets/uploads/events/' . htmlspecialchars($event->background_image, ENT_QUOTES, 'UTF-8')) ?>'); background-size: cover; background-position: center; background-attachment: fixed;" <?php endif; ?>>
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
		<a href="<?= base_url() ?>" class="back-btn">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<polyline points="15 18 9 12 15 6"></polyline>
			</svg>
			Kembali
		</a>

		<div class="glass-card attend-card">
			<h2 class="event-title"><?= htmlspecialchars($event->name, ENT_QUOTES, 'UTF-8') ?></h2>

			<?php if ($event->description): ?>
				<p class="event-desc"><?= htmlspecialchars($event->description, ENT_QUOTES, 'UTF-8') ?></p>
			<?php endif; ?>

			<?php if ($event->input_description): ?>
				<p class="input-hint"><?= htmlspecialchars($event->input_description, ENT_QUOTES, 'UTF-8') ?></p>
			<?php endif; ?>

			<div class="input-group">
				<input
					type="text"
					id="attendInput"
					class="glass-input"
					placeholder="<?= htmlspecialchars($event->input_label, ENT_QUOTES, 'UTF-8') ?>"
					autocomplete="off"
					autofocus>
			</div>
		</div>
	</div>

	<!-- Success Modal -->
	<div class="modal-overlay" id="successModal">
		<div class="modal-glass modal-success">
			<div class="modal-icon success-icon">
				<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
					<polyline points="22 4 12 14.01 9 11.01"></polyline>
				</svg>
			</div>
			<h3 class="modal-title">Kehadiran Tercatat!</h3>
			<p class="modal-event" id="successEvent"></p>
			<p class="modal-session" id="successSession"></p>
			<p class="modal-time" id="successTime"></p>
		</div>
	</div>

	<!-- Error Modal -->
	<div class="modal-overlay" id="errorModal">
		<div class="modal-glass modal-error">
			<div class="modal-icon error-icon">
				<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<circle cx="12" cy="12" r="10"></circle>
					<line x1="15" y1="9" x2="9" y2="15"></line>
					<line x1="9" y1="9" x2="15" y2="15"></line>
				</svg>
			</div>
			<h3 class="modal-title" id="errorTitle">Gagal</h3>
			<p class="modal-message" id="errorMessage"></p>
		</div>
	</div>

	<script>
		var eventId = <?= (int)$event->id ?>;
		var submitUrl = '<?= base_url('home/submit_attendance') ?>';
		var csrfName = '<?= $this->security->get_csrf_token_name() ?>';
		var csrfHash = '<?= $this->security->get_csrf_hash() ?>';
		var input = document.getElementById('attendInput');
		var isSubmitting = false;

		input.addEventListener('keydown', function(e) {
			if (e.key === 'Enter') {
				e.preventDefault();
				submitAttendance();
			}
		});

		function submitAttendance() {
			var value = input.value.trim();
			if (!value || isSubmitting) return;

			isSubmitting = true;
			input.disabled = true;

			var formData = new FormData();
			formData.append('event_id', eventId);
			formData.append('input_value', value);
			formData.append(csrfName, csrfHash);

			fetch(submitUrl, {
					method: 'POST',
					body: formData
				})
				.then(function(r) {
					return r.json();
				})
				.then(function(data) {
					// Update CSRF token for next request
					if (data.csrf_hash) csrfHash = data.csrf_hash;

					if (data.status === 'success') {
						document.getElementById('successEvent').textContent = data.event_name;
						document.getElementById('successSession').textContent = data.session_name || '';
						document.getElementById('successTime').textContent = data.time;
						showModal('successModal');
					} else {
						document.getElementById('errorMessage').textContent = data.message;
						showModal('errorModal');
					}
				})
				.catch(function() {
					document.getElementById('errorMessage').textContent = 'Terjadi kesalahan. Silakan coba lagi.';
					showModal('errorModal');
				})
				.finally(function() {
					isSubmitting = false;
					input.disabled = false;
					input.value = '';
					input.focus();
				});
		}

		function showModal(id) {
			var modal = document.getElementById(id);
			modal.classList.add('active');
			setTimeout(function() {
				modal.classList.remove('active');
			}, 2500);
		}

		// Close modal on click
		document.querySelectorAll('.modal-overlay').forEach(function(m) {
			m.addEventListener('click', function() {
				this.classList.remove('active');
			});
		});
	</script>
	</body>

</html>