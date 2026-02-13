<?php

/** @var array $participants */ /** @var array $event */ /** @var string $event_id */ /** @var array $sessions */ ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Seat Numbers - <?= htmlspecialchars($event['name']) ?> - UCM Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('<?= base_url('uploads/bgabsen-01_6logotengah.gif?q=80&w=2070&auto=format&fit=crop') ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            min-height: 100dvh;
            padding: env(safe-area-inset-top) env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left)
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(13, 71, 161, .15), rgba(25, 118, 210, .2), rgba(33, 150, 243, .1), rgba(0, 0, 0, .4));
            z-index: -1
        }

        .dashboard-container {
            background: linear-gradient(135deg, rgba(0, 0, 0, .3), rgba(25, 118, 210, .2));
            backdrop-filter: blur(20px);
            border-radius: 28px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, .6), 0 0 0 1px rgba(255, 255, 255, .1);
            margin: 20px auto;
            max-width: 1400px;
            padding: 30px;
            color: #fff;
            animation: fadeInUp 1s ease-out
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .table-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, .1), rgba(25, 118, 210, .2));
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .3);
            border: 1px solid rgba(255, 255, 255, .15);
            color: #fff
        }

        .table-container h4 {
            background: linear-gradient(45deg, #fff, #2196f3, #64b5f6);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px
        }

        .form-select,
        .form-control {
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .2);
            color: #fff;
            border-radius: 10px;
            backdrop-filter: blur(10px)
        }

        .form-select option,
        .form-control option {
            background: #2a2a2a;
            color: #fff
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, .7)
        }

        .form-control:focus {
            background: rgba(255, 255, 255, .2);
            border-color: rgba(255, 255, 255, .5);
            color: #fff;
            box-shadow: 0 0 0 .2rem rgba(255, 255, 255, .25)
        }

        .btn {
            border-radius: 10px;
            transition: all .3s ease
        }

        .btn-light {
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .2);
            color: #fff;
            backdrop-filter: blur(10px)
        }

        .btn-light:hover {
            background: rgba(33, 150, 243, .2);
            color: #fff;
            transform: translateY(-2px)
        }

        .btn-success {
            background: linear-gradient(45deg, #4caf50, #81c784);
            border: none
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(76, 175, 80, .3)
        }

        .table {
            color: #fff
        }

        .table-dark {
            background: linear-gradient(135deg, rgba(0, 0, 0, .6), rgba(25, 118, 210, .4))
        }

        .table-dark th {
            border-color: rgba(255, 255, 255, .2);
            color: #fff
        }

        .table td {
            border-color: rgba(255, 255, 255, .1);
            background: rgba(255, 255, 255, .05);
            vertical-align: middle
        }

        .table-hover tbody tr:hover {
            background-color: rgba(33, 150, 243, .1)
        }

        .badge {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, .2)
        }

        .badge-session {
            background: linear-gradient(45deg, #2196f3, #64b5f6) !important;
            border: 1px solid rgba(255, 255, 255, .2);
            font-size: .8em;
            min-width: 114px;
            min-height: 35px;
            display: inline-block;
            text-align: center;
            padding: 6px 8px
        }

        .seat-number {
            font-size: 1.2rem;
            font-weight: bold;
            background: linear-gradient(45deg, #4caf50, #81c784);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            min-width: 60px;
            min-height: 35px;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center
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

        @keyframes gradientShift {

            0%,
            100% {
                background-position: 0% 50%
            }

            50% {
                background-position: 100% 50%
            }
        }

        .toolbar {
            gap: .75rem;
            overflow-x: auto;
            white-space: nowrap
        }

        .toolbar-left {
            gap: .5rem
        }

        .toolbar-select {
            min-width: 160px
        }

        .form-label.mb-0 {
            font-size: .9rem
        }

        .dropdown-wrapper {
            position: relative
        }

        .dropdown-wrapper .form-select {
            appearance: none;
            background-image: none;
            padding-right: 2.5rem
        }

        .dropdown-wrapper::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, .7);
            pointer-events: none;
            transition: all .25s ease
        }

        .dropdown-wrapper:hover::after {
            color: rgba(255, 255, 255, .9)
        }

        .dropdown-wrapper.open::after,
        .dropdown-wrapper.active::after {
            transform: translateY(-50%) rotate(180deg)
        }

        .ms-btn {
            width: 100%;
            text-align: left;
            background: rgba(255, 255, 255, .12);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .25);
            padding: 10px 42px 10px 12px;
            border-radius: 10px;
            backdrop-filter: blur(10px)
        }

        .ms-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            min-width: 260px;
            width: 100%;
            background: rgba(0, 0, 0, .80);
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 12px;
            padding: 10px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .45);
            display: none;
            z-index: 20;
            backdrop-filter: blur(10px)
        }

        .dropdown-wrapper.open .ms-menu {
            display: block;
            animation: fadeInUp .18s ease-out
        }

        .ms-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 8px;
            border-radius: 8px;
            cursor: pointer
        }

        .ms-row:hover {
            background: rgba(255, 255, 255, .06)
        }

        .ms-hidden {
            display: none !important
        }

        .pagination .page-item .page-link {
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .2);
            color: #fff;
            backdrop-filter: blur(10px)
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(45deg, #2196f3, #64b5f6);
            border-color: #2196f3
        }

        .pagination .page-item:hover .page-link {
            background: rgba(33, 150, 243, .2);
            color: #fff
        }

        @keyframes gradientShift {

            0%,
            100% {
                background-position: 0% 50%
            }

            50% {
                background-position: 100% 50%
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .print-content {
                visibility: visible !important;
                display: block !important;
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: white !important;
                color: black !important;
            }

            .print-content * {
                visibility: visible !important;
                display: block !important;
            }

            #printCards {
                display: block !important;
                visibility: visible !important;
            }

            .seat-card {
                visibility: visible !important;
                display: block !important;
                page-break-inside: avoid;
                margin-bottom: 20px;
                border: 2px solid #000 !important;
                padding: 20px !important;
                text-align: center !important;
                background: white !important;
                width: 300px !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }

            .seat-card h1 {
                visibility: visible !important;
                display: block !important;
                font-size: 48px !important;
                margin-bottom: 15px !important;
                color: #000 !important;
                font-weight: bold !important;
            }

            .seat-card h3 {
                visibility: visible !important;
                display: block !important;
                font-size: 24px !important;
                margin-bottom: 10px !important;
                color: #000 !important;
                font-weight: normal !important;
            }
        }
    </style>
</head>

<body>
    <a class="btn back-btn" href="<?= site_url('admin') . ($event_id ? ('?event_id=' . $event_id) : '') ?>"><i class="bi bi-arrow-left me-2"></i>Back</a>

    <div class="container-fluid p-4">
        <div class="dashboard-container">
            <h2 class="mb-4" style="background:linear-gradient(45deg,#fff,#2196f3,#64b5f6);background-size:200% 200%;-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;animation:gradientShift 3s ease-in-out infinite;">
                <i class="fas fa-chair me-2"></i>Seat Numbers
            </h2>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="alert alert-info" style="background:linear-gradient(135deg,rgba(33,150,243,.2),rgba(25,118,210,.3));border:1px solid rgba(33,150,243,.3);color:#fff;border-radius:15px;backdrop-filter:blur(15px)">
                        <i class="fas fa-info-circle me-2"></i><strong>Event:</strong> <?= htmlspecialchars($event['name'] ?? '-') ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-info" style="background:linear-gradient(135deg,rgba(33,150,243,.2),rgba(25,118,210,.3));border:1px solid rgba(33,150,243,.3);color:#fff;border-radius:15px;backdrop-filter:blur(15px)">
                        <i class="fas fa-chart-bar me-2"></i><strong>Total:</strong> <span id="totalParticipants"><?= count($participants) ?></span> participants with seat numbers
                    </div>
                </div>
            </div>

            <div class="table-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0"><i class="fas fa-chair me-2"></i>Data Seat Numbers</h4>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light" onclick="printSeatCards()"><i class="fas fa-print me-1"></i>Print Cards</button>
                        <button class="btn btn-success" onclick="exportToExcel()"><i class="fas fa-file-excel me-1"></i>Export Excel</button>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-2">
                        <label class="form-label mb-1"><strong><i class="fas fa-calendar-alt me-1"></i>Filter Sesi:</strong></label>
                        <div class="dropdown-wrapper">
                            <select class="form-select" id="filterSesi">
                                <option value="">Semua Sesi</option>
                                <?php foreach ($sessions as $s): ?>
                                    <option value="<?= $s['session'] ?>">Sesi <?= $s['session'] ?> - <?= $s['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1"><strong><i class="fas fa-user-check me-1"></i>Filter Status:</strong></label>
                        <div class="dropdown-wrapper">
                            <select class="form-select" id="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="present">Hadir</option>
                                <option value="absent">None</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1"><strong><i class="fas fa-list me-1"></i>Show:</strong></label>
                        <div class="dropdown-wrapper">
                            <select id="recordsPerPage" class="form-select">
                                <option value="10" selected>10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                                <option value="all">All</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-1" style="opacity:0;">Actions</label>
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-light me-2" onclick="refreshTable()"><i class="fas fa-sync-alt me-1"></i>Reset</button>
                            <!-- <button class="btn btn-light me-2" onclick="toggleSearch()"><i class="fas fa-search me-1"></i>Search</button> -->
                        </div>
                    </div>
                </div>

                <div id="searchContainer" class="row mb-3">
                    <div class="col-12">
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari nama, NIS, atau seat number...">
                    </div>
                </div>


                <?php if (!empty($participants)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover" id="seatTable">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-chair me-1"></i>Seat No</th>
                                    <th onclick="sortTable(1)" style="cursor:pointer;">Nama <i class="fas fa-sort ms-1"></i></th>
                                    <th onclick="sortTable(2)" style="cursor:pointer;">NIS <i class="fas fa-sort ms-1"></i></th>
                                    <th>Sub Prodi</th>
                                    <th>Sesi</th>
                                    <th>Status</th>
                                    <th onclick="sortTable(6)" style="cursor:pointer;">Kategori <i class="fas fa-sort ms-1"></i></th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php foreach ($participants as $p): ?>
                                    <tr data-session="<?= $p['session'] ?>" data-status="<?= $p['final_status'] ?>">
                                        <td><span class="seat-number"><?= htmlspecialchars($p['seat_no']) ?></span></td>
                                        <td class="text-white"><?= htmlspecialchars($p['name']) ?></td>
                                        <td class="text-white"><?= htmlspecialchars($p['nis']) ?></td>
                                        <td class="text-white"><?= htmlspecialchars($p['prodi']) ?></td>
                                        <td><span class="badge bg-primary badge-session">Sesi <?= $p['session'] ?><?php if (!empty($p['session_name'])): ?><br><small><?= htmlspecialchars($p['session_name']) ?></small><?php endif; ?></span></td>
                                        <td>
                                            <?php if ($p['final_status'] === 'present'): ?>
                                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>Hadir</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><i class="fas fa-times me-1"></i>None</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-white">
                                            <?= htmlspecialchars($p['kategori'] ?? '-') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div id="paginationInfo" class="text-white"></div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0" id="pagination"></ul>
                        </nav>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div style="background:rgba(255,255,255,.1);backdrop-filter:blur(15px);border-radius:20px;padding:3rem 2rem;border:1px solid rgba(255,255,255,.15);">
                            <i class="fas fa-chair" style="font-size:3.5rem;color:rgba(255,152,0,.8);margin-bottom:1.5rem;"></i>
                            <h3 class="mb-3" style="font-size:1.8rem;font-weight:600;background:linear-gradient(45deg,#fff,#ff9800,#ffb74d);background-size:200% 200%;-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Belum Ada Seat Number</h3>
                            <p class="mb-0" style="font-size:1rem;color:rgba(255,255,255,.8);line-height:1.5;">Tidak ada peserta dengan seat number yang terdaftar untuk event ini</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Print Content Area (Hidden) -->
    <div class="print-content" style="display: none;">
        <div id="printCards"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        const participants = <?= json_encode($participants) ?>;
        let filteredData = [...participants];
        let currentPage = 1;
        let recordsPerPage = 10;
        let sortColumn = -1;
        let sortDirection = 'asc';

        document.addEventListener('DOMContentLoaded', () => {
            updateTable();
            setupEventListeners();
            enhanceAllSelects();
        });

        function setupEventListeners() {
            document.getElementById('filterSesi')?.addEventListener('change', applyFilters);
            document.getElementById('filterStatus')?.addEventListener('change', applyFilters);
            document.getElementById('recordsPerPage')?.addEventListener('change', function() {
                recordsPerPage = this.value === 'all' ? filteredData.length : parseInt(this.value);
                currentPage = 1;
                updateTable();
            });
            document.getElementById('searchInput')?.addEventListener('input', applyFilters);
        }

        // Enhance native selects into ms-menu single-select dropdowns
        function enhanceAllSelects() {
            const selects = document.querySelectorAll('.dropdown-wrapper select.form-select');
            selects.forEach(enhanceSelect);
            document.addEventListener('click', (e) => {
                document.querySelectorAll('.dropdown-wrapper.open').forEach(w => {
                    if (!w.contains(e.target)) w.classList.remove('open');
                });
            });
        }

        function enhanceSelect(sel) {
            if (!sel || sel.dataset.enhanced === '1') return;
            const wrap = sel.closest('.dropdown-wrapper') || sel.parentElement;
            if (!wrap) return;
            sel.dataset.enhanced = '1';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'ms-btn';
            const label = document.createElement('span');
            label.className = 'ms-label';
            btn.appendChild(label);
            const menu = document.createElement('div');
            menu.className = 'ms-menu';
            Array.from(sel.options).forEach(opt => {
                const row = document.createElement('div');
                row.className = 'ms-row';
                row.textContent = opt.text;
                row.dataset.value = opt.value;
                row.addEventListener('click', (ev) => {
                    sel.value = row.dataset.value;
                    sel.dispatchEvent(new Event('change'));
                    updateMsLabel(sel, btn);
                    wrap.classList.remove('open');
                    ev.stopPropagation();
                    ev.preventDefault();
                });
                menu.appendChild(row);
            });
            sel.classList.add('ms-hidden');
            wrap.appendChild(btn);
            wrap.appendChild(menu);
            updateMsLabel(sel, btn);
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                // Close others first
                document.querySelectorAll('.dropdown-wrapper.open').forEach(w => {
                    if (w !== wrap) w.classList.remove('open');
                });
                wrap.classList.toggle('open');
            });
        }

        function updateMsLabel(sel, btnEl) {
            const opt = sel.options[sel.selectedIndex];
            const txt = opt ? opt.text : 'Select';
            const btn = btnEl || sel.closest('.dropdown-wrapper')?.querySelector('.ms-btn');
            if (btn) btn.querySelector('.ms-label').textContent = txt;
        }

        function syncAllMsLabels() {
            document.querySelectorAll('.dropdown-wrapper select.form-select').forEach(sel => updateMsLabel(sel));
        }

        // function toggleSearch() {
        //     const el = document.getElementById('searchContainer');
        //     if (!el) return;
        //     if (el.style.display === 'none') {
        //         el.style.display = 'block';
        //         document.getElementById('searchInput').focus();
        //     } else {
        //         el.style.display = 'none';
        //         document.getElementById('searchInput').value = '';
        //         applyFilters();
        //     }
        // }

        function refreshTable() {
            sortColumn = -1;
            sortDirection = 'asc';
            document.getElementById('filterSesi').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('recordsPerPage').value = '10';
            recordsPerPage = 10;
            syncAllMsLabels();
            const s = document.getElementById('searchContainer');
            // if (s) s.style.display = 'none';
            const si = document.getElementById('searchInput');
            if (si) si.value = '';
            filteredData = [...participants];
            updateTable();
            updateSortIcons(-1);
        }

        function applyFilters() {
            const sesi = document.getElementById('filterSesi').value;
            const status = document.getElementById('filterStatus').value;
            const q = (document.getElementById('searchInput')?.value || '').toLowerCase();

            filteredData = participants.filter(p => {
                const mSesi = !sesi || p.session == sesi;
                const mStatus = !status || p.final_status === status;
                const searchText = [p.name, p.nis, p.seat_no].join(' ').toLowerCase();
                const mSearch = !q || searchText.includes(q);
                return mSesi && mStatus && mSearch;
            });

            // Update recordsPerPage if "all" is selected
            if (document.getElementById('recordsPerPage').value === 'all') {
                recordsPerPage = filteredData.length;
            }

            currentPage = 1;
            updateTable();
        }

        function sortTable(idx) {
            if (sortColumn === idx) {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                sortColumn = idx;
                sortDirection = 'asc';
            }

            filteredData.sort((a, b) => {
                let A, B;
                switch (idx) {
                    case 0:
                        // Seat number - sort numerically
                        A = parseInt(a.seat_no, 10) || 0;
                        B = parseInt(b.seat_no, 10) || 0;
                        break;
                    case 1:
                        A = (a.name || '').toLowerCase();
                        B = (b.name || '').toLowerCase();
                        break;
                    case 2:
                        A = (a.nis || '').toLowerCase();
                        B = (b.nis || '').toLowerCase();
                        break;
                    case 6:
                        A = (a.kategori || '').toLowerCase();
                        B = (b.kategori || '').toLowerCase();
                        break;
                    default:
                        return 0;
                }

                if (A < B) return sortDirection === 'asc' ? -1 : 1;
                if (A > B) return sortDirection === 'asc' ? 1 : -1;
                return 0;
            });

            updateTable();
            updateSortIcons(idx);
        }

        function updateSortIcons(active) {
            document.querySelectorAll('th i.fas').forEach(icon => {
                if (icon.className.includes('fa-sort')) icon.className = 'fas fa-sort ms-1';
            });
            if (active >= 0) {
                const headers = document.querySelectorAll('#seatTable thead th');
                const icon = headers[active]?.querySelector('i.fas');
                if (icon) icon.className = sortDirection === 'asc' ? 'fas fa-sort-up ms-1' : 'fas fa-sort-down ms-1';
            }
        }

        function updateTable() {
            const start = (currentPage - 1) * recordsPerPage;
            const end = start + recordsPerPage;
            const page = filteredData.slice(start, end);
            const tbody = document.getElementById('tableBody');
            if (!tbody) return;

            tbody.innerHTML = '';
            if (filteredData.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td colspan="7" class="text-center text-white" style="padding:4rem 2rem!important;background:linear-gradient(135deg, rgba(33,150,243,.1), rgba(25,118,210,.15));margin:1rem;"><div style="background:rgba(255,255,255,.1);backdrop-filter:blur(10px);border-radius:20px;padding:2.5rem 2rem;border:1px solid rgba(255,255,255,.15);"><i class='fas fa-chair me-3' style='font-size:3.5rem;color:rgba(255,255,255,.7);margin-bottom:1rem;'></i><h3 class='mb-3' style='font-size:1.8rem;font-weight:600;background:linear-gradient(45deg,#fff,#2196f3,#64b5f6);background-size:200% 200%;-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;'>Tidak ada data yang ditemukan</h3><p class='mb-0' style='font-size:1rem;color:rgba(255,255,255,.8);line-height:1.5;'>Coba ubah filter atau kata kunci pencarian Anda</p></div></td>`;
                tbody.appendChild(tr);
            } else {
                page.forEach((p) => tbody.appendChild(createTableRow(p)));
            }

            updatePagination();
            updatePaginationInfo();
        }

        function createTableRow(p) {
            const tr = document.createElement('tr');
            tr.dataset.session = p.session;
            tr.dataset.status = p.final_status;

            const statusBadge = p.final_status === 'present' ?
                '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Hadir</span>' :
                '<span class="badge bg-secondary"><i class="fas fa-times me-1"></i>None</span>';

            const sessionBadge = p.session_name ?
                `<span class="badge bg-primary badge-session">Sesi ${p.session}<br><small>${p.session_name}</small></span>` :
                `<span class="badge bg-primary badge-session">Sesi ${p.session}</span>`;

            const kategori = p.kategori || '-';

            tr.innerHTML = `
        <td><span class="seat-number">${p.seat_no || '-'}</span></td>
        <td class="text-white">${p.name || ''}</td>
        <td class="text-white">${p.nis || ''}</td>
        <td class="text-white">${p.prodi || ''}</td>
        <td>${sessionBadge}</td>
        <td>${statusBadge}</td>
        <td class="text-white">${kategori}</td>
      `;

            return tr;
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredData.length / recordsPerPage);
            const ul = document.getElementById('pagination');
            if (!ul) return;

            ul.innerHTML = '';
            if (totalPages <= 1) return;

            // Previous button
            const prev = document.createElement('li');
            prev.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            prev.innerHTML = `<a class='page-link' href='javascript:void(0)' onclick='event.preventDefault(); changePage(${currentPage - 1})'><i class="fas fa-chevron-left me-1"></i>Previous</a>`;
            ul.appendChild(prev);

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    li.innerHTML = `<a class='page-link' href='javascript:void(0)' onclick='event.preventDefault(); changePage(${i})'>${i}</a>`;
                    ul.appendChild(li);
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    const el = document.createElement('li');
                    el.className = 'page-item disabled';
                    el.innerHTML = '<span class="page-link">...</span>';
                    ul.appendChild(el);
                }
            }

            // Next button
            const next = document.createElement('li');
            next.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
            next.innerHTML = `<a class='page-link' href='javascript:void(0)' onclick='event.preventDefault(); changePage(${currentPage + 1})'>Next<i class="fas fa-chevron-right ms-1"></i></a>`;
            ul.appendChild(next);
        }

        function updatePaginationInfo() {
            const start = (currentPage - 1) * recordsPerPage + 1;
            const end = Math.min(currentPage * recordsPerPage, filteredData.length);
            const infoEl = document.getElementById('paginationInfo');
            if (infoEl) {
                infoEl.textContent = `Showing ${start} to ${end} of ${filteredData.length} entries`;
            }
        }

        function changePage(p) {
            const totalPages = Math.ceil(filteredData.length / recordsPerPage);
            if (p >= 1 && p <= totalPages) {
                currentPage = p;
                updateTable();
            }
            return false;
        }

        function printSeatCards() {
            const visibleParticipants = getVisibleParticipants();
            const printArea = document.getElementById('printCards');
            const printContainer = document.querySelector('.print-content');

            if (!printArea || !printContainer) {
                console.error('Print area not found');
                alert('Print area not found. Please refresh the page and try again.');
                return;
            }

            if (visibleParticipants.length === 0) {
                alert('No participants to print. Please check your filters.');
                return;
            }

            // Clear previous content
            printArea.innerHTML = '';

            // Generate cards
            visibleParticipants.forEach(p => {
                const card = document.createElement('div');
                card.className = 'seat-card';

                const seatNumber = document.createElement('h1');
                seatNumber.textContent = p.seat_no || 'N/A';
                card.appendChild(seatNumber);

                const name = document.createElement('h3');
                name.textContent = p.name || 'N/A';
                card.appendChild(name);

                const nis = document.createElement('h3');
                nis.textContent = p.nis || 'N/A';
                card.appendChild(nis);

                printArea.appendChild(card);
            });

            console.log(`Generated ${visibleParticipants.length} cards for printing`);
            // Delay to ensure DOM is updated
            setTimeout(() => {
                window.print();
            }, 200);
        }

        function getVisibleParticipants() {
            // Get only the participants currently visible on the page
            if (recordsPerPage === filteredData.length || document.getElementById('recordsPerPage').value === 'all') {
                // If showing all records, return all filtered data
                return filteredData;
            } else {
                // Return only the current page participants
                const start = (currentPage - 1) * recordsPerPage;
                const end = start + recordsPerPage;
                return filteredData.slice(start, end);
            }
        }

        function exportToExcel() {
            const table = document.getElementById('seatTable');
            const wb = XLSX.utils.table_to_book(table, {
                sheet: 'Seat Numbers'
            });
            const filename = 'seat_numbers_<?= $event['name'] ?>_' + new Date().toISOString().split('T')[0] + '.xlsx';
            XLSX.writeFile(wb, filename);
        }
    </script>
</body>

</html>