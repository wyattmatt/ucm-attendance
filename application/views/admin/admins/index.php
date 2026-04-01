<!-- Admins List -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0">Kelola akun admin sistem</p>
    </div>
    <a href="<?= base_url('admin/admins/create') ?>" class="btn btn-primary">
        <i class="fas fa-user-plus me-1"></i>Tambah Admin Baru
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Dibuat</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $i => $a): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <strong><?= htmlspecialchars($a->name, ENT_QUOTES, 'UTF-8') ?></strong>
                                <?php if ($a->id == $admin['id']): ?>
                                    <span class="badge bg-info ms-1">Anda</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($a->email, ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <?php if ($a->role === 'superadmin'): ?>
                                    <span class="badge bg-danger">Superadmin</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">Admin</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d M Y', strtotime($a->created_at)) ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('admin/admins/edit/' . $a->id) ?>" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($a->id != $admin['id']): ?>
                                        <button onclick="confirmDelete('<?= base_url('admin/admins/delete/' . $a->id) ?>', '<?= htmlspecialchars(addslashes($a->name), ENT_QUOTES, 'UTF-8') ?>')" class="btn btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>