<!-- Admin Form -->
<div class="mb-4">
    <a href="<?= base_url('admin/admins') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<?php if (validation_errors()): ?>
    <div class="alert alert-danger">
        <?= validation_errors('<p class="mb-0">', '</p>') ?>
    </div>
<?php endif; ?>

<?php
$action_url = $mode === 'create' ? 'admin/admins/create' : 'admin/admins/edit/' . $admin_data->id;
?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-<?= $mode === 'create' ? 'plus' : 'edit' ?> me-2"></i>
                    <?= $mode === 'create' ? 'Tambah Admin Baru' : 'Edit Admin' ?>
                </h5>
            </div>
            <div class="card-body">
                <?= form_open($action_url) ?>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= set_value('name', $admin_data ? $admin_data->name : '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email', $admin_data ? $admin_data->email : '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">
                        Password
                        <?php if ($mode === 'create'): ?>
                            <span class="text-danger">*</span>
                        <?php else: ?>
                            <small class="text-muted">(kosongkan jika tidak diubah)</small>
                        <?php endif; ?>
                    </label>
                    <input type="password" class="form-control" id="password" name="password" <?= $mode === 'create' ? 'required' : '' ?> minlength="6">
                </div>
                <div class="mb-4">
                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">Pilih Role</option>
                        <option value="superadmin" <?= set_select('role', 'superadmin', ($admin_data && $admin_data->role === 'superadmin')) ?>>Superadmin</option>
                        <option value="admin" <?= set_select('role', 'admin', ($admin_data && $admin_data->role === 'admin')) ?>>Admin</option>
                    </select>
                    <small class="text-muted">Superadmin dapat mengelola admin lain. Admin hanya mengelola event.</small>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i><?= $mode === 'create' ? 'Buat Admin' : 'Simpan Perubahan' ?>
                    </button>
                    <a href="<?= base_url('admin/admins') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>