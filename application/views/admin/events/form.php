<!-- Event Form -->
<div class="mb-4">
    <a href="<?= base_url('admin/events') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<?php if (validation_errors()): ?>
    <div class="alert alert-danger">
        <?= validation_errors('<p class="mb-0">', '</p>') ?>
    </div>
<?php endif; ?>

<?php
$action_url = $mode === 'create' ? 'admin/events/create' : 'admin/events/edit/' . $event->id;
?>

<?= form_open_multipart($action_url) ?>
<div class="row">
    <!-- Left Column: Basic Info -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Event</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Event <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= set_value('name', $event ? $event->name : '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= set_value('description', $event ? $event->description : '') ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?= set_value('start_date', $event ? $event->start_date : '') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?= set_value('end_date', $event ? $event->end_date : '') ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_time" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="start_time" name="start_time" value="<?= set_value('start_time', $event ? substr($event->start_time, 0, 5) : '') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_time" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="end_time" name="end_time" value="<?= set_value('end_time', $event ? substr($event->end_time, 0, 5) : '') ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sessions -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list-ol me-2"></i>Sesi (Opsional)</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addSession">
                    <i class="fas fa-plus me-1"></i>Tambah Sesi
                </button>
            </div>
            <div class="card-body">
                <p class="text-muted small">Tambahkan sesi jika event memiliki beberapa sesi. Kosongkan jika tidak diperlukan.</p>
                <div id="sessionsContainer">
                    <?php if (!empty($sessions)): ?>
                        <?php foreach ($sessions as $i => $s): ?>
                            <div class="session-row mb-3 p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="session-label">Sesi <?= $i + 1 ?></strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-session"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <input type="text" class="form-control form-control-sm" name="session_name[]" placeholder="Nama Sesi" value="<?= htmlspecialchars($s->session_name, ENT_QUOTES, 'UTF-8') ?>">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <input type="time" class="form-control form-control-sm" name="session_start_time[]" placeholder="Mulai" value="<?= $s->start_time ? substr($s->start_time, 0, 5) : '' ?>">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <input type="time" class="form-control form-control-sm" name="session_end_time[]" placeholder="Selesai" value="<?= $s->end_time ? substr($s->end_time, 0, 5) : '' ?>">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Input Config & Participants -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-keyboard me-2"></i>Konfigurasi Input</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="input_label" class="form-label">Label Input Peserta <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="input_label" name="input_label" value="<?= set_value('input_label', $event ? $event->input_label : 'Kode Kehadiran') ?>" required>
                    <small class="text-muted">Harus sama persis dengan nama kolom di CSV. Contoh: NIM, ID, No. Telepon</small>
                </div>
                <div class="mb-3">
                    <label for="input_description" class="form-label">Deskripsi Input</label>
                    <input type="text" class="form-control" id="input_description" name="input_description" value="<?= set_value('input_description', $event ? $event->input_description : '') ?>">
                    <small class="text-muted">Petunjuk tambahan untuk peserta</small>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-image me-2"></i>Background Event</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="background_image" class="form-label">Gambar Background</label>
                    <input type="file" class="form-control form-control-sm" id="background_image" name="background_image" accept="image/jpeg,image/png,image/webp">
                    <small class="text-muted">JPG, PNG, atau WebP. Maks 5MB.</small>
                </div>
                <?php if ($event && !empty($event->background_image)): ?>
                    <div class="mt-2">
                        <small class="text-muted d-block mb-1">Background saat ini:</small>
                        <img src="<?= base_url('assets/uploads/events/' . $event->background_image) ?>" class="img-thumbnail" style="max-height: 120px;">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Daftar Peserta (Opsional)</h5>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="has_participants" name="has_participants" value="1" <?= ($event && $event->has_participants) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="has_participants">Event memiliki daftar peserta</label>
                </div>

                <div id="participantSection" style="<?= ($event && $event->has_participants) ? '' : 'display:none' ?>">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Upload CSV</label>
                        <input type="file" class="form-control form-control-sm" id="csv_file" name="csv_file" accept=".csv">
                        <small class="text-muted">CSV dengan header baris pertama. Kolom kode diambil dari kolom yang sesuai Label Input.</small>
                    </div>

                    <?php if ($mode === 'edit' && $event && $event->has_participants): ?>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="replace_participants" name="replace_participants" value="1">
                            <label class="form-check-label small" for="replace_participants">Ganti semua peserta (hapus yang lama)</label>
                        </div>
                        <?php if (!empty($participants)): ?>
                            <div class="mt-2">
                                <small class="text-muted"><?= count($participants) ?> peserta terdaftar</small>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-2"></i><?= $mode === 'create' ? 'Buat Event' : 'Simpan Perubahan' ?>
            </button>
            <a href="<?= base_url('admin/events') ?>" class="btn btn-outline-secondary">Batal</a>
        </div>
    </div>
</div>
<?= form_close() ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let sessionCount = document.querySelectorAll('.session-row').length;

        // Add session
        document.getElementById('addSession').addEventListener('click', function() {
            sessionCount++;
            const html = `
            <div class="session-row mb-3 p-3 bg-light rounded">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="session-label">Sesi ${sessionCount}</strong>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-session"><i class="fas fa-times"></i></button>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" class="form-control form-control-sm" name="session_name[]" placeholder="Nama Sesi">
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="time" class="form-control form-control-sm" name="session_start_time[]" placeholder="Mulai">
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="time" class="form-control form-control-sm" name="session_end_time[]" placeholder="Selesai">
                    </div>
                </div>
            </div>`;
            document.getElementById('sessionsContainer').insertAdjacentHTML('beforeend', html);
        });

        // Remove session
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-session')) {
                e.target.closest('.session-row').remove();
                // Re-number sessions
                document.querySelectorAll('.session-label').forEach(function(label, i) {
                    label.textContent = 'Sesi ' + (i + 1);
                });
                sessionCount = document.querySelectorAll('.session-row').length;
            }
        });

        // Toggle participant section
        document.getElementById('has_participants').addEventListener('change', function() {
            document.getElementById('participantSection').style.display = this.checked ? '' : 'none';
        });
    });
</script>
