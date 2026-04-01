<!-- Events List -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0">Kelola semua event universitas</p>
    </div>
    <a href="<?= base_url('admin/events/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Tambah Event Baru
    </a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($events)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-calendar-plus fa-3x mb-3 d-block opacity-50"></i>
                <p>Belum ada event.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover data-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Event</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Input Label</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $i => $event): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($event->name, ENT_QUOTES, 'UTF-8') ?></strong>
                                    <?php if ($event->has_participants): ?>
                                        <br><small class="text-info"><i class="fas fa-users"></i> Punya daftar peserta</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= date('d M Y', strtotime($event->start_date)) ?>
                                    <?php if ($event->start_date !== $event->end_date): ?>
                                        <br><small class="text-muted">s/d <?= date('d M Y', strtotime($event->end_date)) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('H:i', strtotime($event->start_time)) ?> - <?= date('H:i', strtotime($event->end_time)) ?></td>
                                <td><code><?= htmlspecialchars($event->input_label, ENT_QUOTES, 'UTF-8') ?></code></td>
                                <td>
                                    <?php
                                    $bc = ['upcoming' => 'warning', 'ongoing' => 'success', 'completed' => 'secondary'];
                                    $bt = ['upcoming' => 'Akan Datang', 'ongoing' => 'Berlangsung', 'completed' => 'Selesai'];
                                    ?>
                                    <span class="badge bg-<?= $bc[$event->status] ?>"><?= $bt[$event->status] ?></span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('admin/attendance/view/' . $event->id) ?>" class="btn btn-outline-primary" title="Kehadiran">
                                            <i class="fas fa-clipboard-check"></i>
                                        </a>
                                        <a href="<?= base_url('admin/events/edit/' . $event->id) ?>" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="confirmDelete('<?= base_url('admin/events/delete/' . $event->id) ?>', '<?= htmlspecialchars(addslashes($event->name), ENT_QUOTES, 'UTF-8') ?>')" class="btn btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>