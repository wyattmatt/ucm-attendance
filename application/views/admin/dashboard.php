<!-- Dashboard Stats -->
<div class="stats-grid">
    <div class="stat-card stat-primary">
        <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="stat-info">
            <span class="stat-value"><?= $total_events ?></span>
            <span class="stat-label">Total Event</span>
        </div>
    </div>
    <div class="stat-card stat-success">
        <div class="stat-icon"><i class="fas fa-play-circle"></i></div>
        <div class="stat-info">
            <span class="stat-value"><?= $total_ongoing ?></span>
            <span class="stat-label">Berlangsung</span>
        </div>
    </div>
    <div class="stat-card stat-warning">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <span class="stat-value"><?= $total_upcoming ?></span>
            <span class="stat-label">Akan Datang</span>
        </div>
    </div>
    <div class="stat-card stat-info">
        <div class="stat-icon"><i class="fas fa-clipboard-check"></i></div>
        <div class="stat-info">
            <span class="stat-value"><?= $total_attendances ?></span>
            <span class="stat-label">Total Kehadiran</span>
        </div>
    </div>
</div>

<!-- Recent Events -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Event Terbaru</h5>
        <a href="<?= base_url('admin/events/create') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Tambah Event
        </a>
    </div>
    <div class="card-body p-0">
        <?php if (empty($recent_events)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-calendar-plus fa-3x mb-3 d-block opacity-50"></i>
                <p>Belum ada event. Mulai buat event pertama!</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_events as $event): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($event->name, ENT_QUOTES, 'UTF-8') ?></strong>
                                </td>
                                <td>
                                    <?= date('d M Y', strtotime($event->start_date)) ?>
                                    <?php if ($event->start_date !== $event->end_date): ?>
                                        <br><small class="text-muted">s/d <?= date('d M Y', strtotime($event->end_date)) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('H:i', strtotime($event->start_time)) ?> - <?= date('H:i', strtotime($event->end_time)) ?></td>
                                <td>
                                    <?php
                                    $badge_class = ['upcoming' => 'warning', 'ongoing' => 'success', 'completed' => 'secondary'];
                                    $badge_text = ['upcoming' => 'Akan Datang', 'ongoing' => 'Berlangsung', 'completed' => 'Selesai'];
                                    ?>
                                    <span class="badge bg-<?= $badge_class[$event->status] ?>">
                                        <?= $badge_text[$event->status] ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/attendance/view/' . $event->id) ?>" class="btn btn-sm btn-outline-primary" title="Lihat Kehadiran">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('admin/events/edit/' . $event->id) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>