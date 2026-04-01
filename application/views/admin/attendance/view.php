<!-- Attendance View -->
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <a href="<?= base_url('admin/attendance') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
    <div class="d-flex gap-2">
        <a href="<?= base_url('admin/attendance/export/' . $event->id) ?>" class="btn btn-success btn-sm">
            <i class="fas fa-file-csv me-1"></i>Export CSV
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="mb-0 text-primary" id="statAttended"><?= $stats['total_attended'] ?></h3>
                <small class="text-muted">Total Hadir</small>
            </div>
        </div>
    </div>
    <?php if ($event->has_participants): ?>
        <div class="col-md-4 mb-3">
            <div class="card border-0 bg-warning bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-warning" id="statParticipants"><?= $stats['total_participants'] ?></h3>
                    <small class="text-muted">Total Peserta</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 bg-success bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-success" id="statPercentage">
                        <?= $stats['total_participants'] > 0 ? round(($stats['total_attended'] / $stats['total_participants']) * 100) : 0 ?>%
                    </h3>
                    <small class="text-muted">Persentase Kehadiran</small>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="col-md-4 mb-3">
            <div class="card border-0 bg-info bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-info"><?= date('H:i', strtotime($event->start_time)) ?> - <?= date('H:i', strtotime($event->end_time)) ?></h3>
                    <small class="text-muted">Waktu Event</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 bg-success bg-opacity-10">
                <div class="card-body text-center">
                    <?php
                    $bc = ['upcoming' => 'warning', 'ongoing' => 'success', 'completed' => 'secondary'];
                    $bt = ['upcoming' => 'Akan Datang', 'ongoing' => 'Berlangsung', 'completed' => 'Selesai'];
                    ?>
                    <h3 class="mb-0"><span class="badge bg-<?= $bc[$event->status] ?>"><?= $bt[$event->status] ?></span></h3>
                    <small class="text-muted">Status</small>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($sessions)): ?>
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-list-ol me-2"></i>Sesi Event</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Sesi</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sessions as $s): ?>
                            <tr>
                                <td><?= htmlspecialchars($s->session_name, ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= $s->start_time ? date('H:i', strtotime($s->start_time)) . ' - ' . date('H:i', strtotime($s->end_time)) : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Participant List (if event has participants) -->
<?php if ($event->has_participants && !empty($participants)): ?>
    <?php
    // Helper to parse additional_info into associative array
    function parse_extra_info($info)
    {
        $result = [];
        if (empty($info)) return $result;
        $parts = explode(' | ', $info);
        foreach ($parts as $part) {
            $colonPos = strpos($part, ': ');
            if ($colonPos !== false) {
                $key = substr($part, 0, $colonPos);
                $val = substr($part, $colonPos + 2);
                $result[$key] = $val;
            }
        }
        return $result;
    }
    ?>
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-users me-2"></i>Daftar Peserta & Status Kehadiran</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover data-table" id="participantTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th><?= htmlspecialchars($event->input_label, ENT_QUOTES, 'UTF-8') ?></th>
                            <?php foreach ($extra_columns as $col): ?>
                                <th><?= htmlspecialchars(ucwords($col), ENT_QUOTES, 'UTF-8') ?></th>
                            <?php endforeach; ?>
                            <th>Status</th>
                            <th>Waktu Hadir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($participants as $i => $p): ?>
                            <?php $extra = parse_extra_info($p->additional_info); ?>
                            <tr class="<?= $p->is_present ? '' : 'table-light' ?>">
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($p->name, ENT_QUOTES, 'UTF-8') ?></td>
                                <td><code><?= htmlspecialchars($p->unique_code, ENT_QUOTES, 'UTF-8') ?></code></td>
                                <?php foreach ($extra_columns as $col): ?>
                                    <td><?= isset($extra[$col]) ? htmlspecialchars($extra[$col], ENT_QUOTES, 'UTF-8') : '-' ?></td>
                                <?php endforeach; ?>
                                <td>
                                    <?php if ($p->is_present): ?>
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Hadir</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Belum Hadir</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $p->attended_at ? date('d M Y H:i', strtotime($p->attended_at)) : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- All Attendance Logs -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Log Kehadiran</h6>
    </div>
    <div class="card-body">
        <?php if (empty($attendances)): ?>
            <div class="text-center py-4 text-muted">
                <p class="mb-0">Belum ada data kehadiran.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover data-table" id="attendanceTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Input</th>
                            <th>Peserta</th>
                            <th>Sesi</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendances as $i => $a): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><code><?= htmlspecialchars($a->input_value, ENT_QUOTES, 'UTF-8') ?></code></td>
                                <td><?= $a->participant_name ? htmlspecialchars($a->participant_name, ENT_QUOTES, 'UTF-8') : '<span class="text-muted">-</span>' ?></td>
                                <td><?= $a->session_name ? htmlspecialchars($a->session_name, ENT_QUOTES, 'UTF-8') : '<span class="text-muted">-</span>' ?></td>
                                <td><?= date('d M Y H:i:s', strtotime($a->attended_at)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>