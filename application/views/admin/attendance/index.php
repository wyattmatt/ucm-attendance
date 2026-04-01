<!-- Attendance Index -->
<div class="mb-4">
    <p class="text-muted mb-0">Pilih event untuk melihat laporan kehadiran</p>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($events)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-clipboard-check fa-3x mb-3 d-block opacity-50"></i>
                <p>Belum ada event.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover data-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Event</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Peserta</th>
                            <th>Hadir</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $i => $event): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><strong><?= htmlspecialchars($event->name, ENT_QUOTES, 'UTF-8') ?></strong></td>
                                <td><?= date('d M Y', strtotime($event->start_date)) ?></td>
                                <td>
                                    <?php
                                    $bc = ['upcoming' => 'warning', 'ongoing' => 'success', 'completed' => 'secondary'];
                                    $bt = ['upcoming' => 'Akan Datang', 'ongoing' => 'Berlangsung', 'completed' => 'Selesai'];
                                    ?>
                                    <span class="badge bg-<?= $bc[$event->status] ?>"><?= $bt[$event->status] ?></span>
                                </td>
                                <td>
                                    <?php if ($event->has_participants && $event->participant_count > 0): ?>
                                        <?= $event->participant_count ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary"><?= $event->attendance_count ?></span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('admin/attendance/view/' . $event->id) ?>" class="btn btn-outline-primary" title="Detail">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </a>
                                        <a href="<?= base_url('admin/attendance/export/' . $event->id) ?>" class="btn btn-outline-success" title="Export CSV">
                                            <i class="fas fa-file-csv"></i>
                                        </a>
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