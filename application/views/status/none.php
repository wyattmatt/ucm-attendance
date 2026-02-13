<?php

/** @var array $event */ /** @var array $participants */ ?>
<?php
$prodiList = array_values(array_unique(array_map(function ($p) {
  return $p['desc_2'] ?? '';
}, $participants)));
$sessionList = [];
foreach ($participants as $p) {
  $sid = $p['session'] ?? '';
  if ($sid === '' || isset($sessionList[$sid])) continue;
  $sessionList[$sid] = isset($p['session_name']) && $p['session_name'] !== '' ? $p['session_name'] : ('Sesi ' . $sid);
}
ksort($sessionList);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Belum Absen - <?= htmlspecialchars($event['name'] ?? 'Event') ?></title>
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
      background: rgba(255, 255, 255, .05)
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

    .auto-refresh-indicator {
      background: rgba(255, 255, 255, .1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, .2);
      border-radius: 8px;
      padding: 6px 12px;
      color: rgba(255, 255, 255, .85);
      font-size: .85rem;
      display: inline-flex;
      align-items: center;
      gap: .35rem;
      animation: pulse 2s infinite
    }

    .auto-refresh-indicator i {
      animation: spin 2s linear infinite
    }

    @keyframes spin {
      from {
        transform: rotate(0)
      }

      to {
        transform: rotate(360deg)
      }
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: .8
      }

      50% {
        opacity: 1
      }
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
  </style>
</head>

<body>
  <a class="btn back-btn" href="<?= site_url('admin') . ($event['id'] ? ('?event_id=' . $event['id']) : '') ?>"><i class="bi bi-arrow-left me-2"></i>Back</a>
  <div class="container-fluid p-4">
    <div class="dashboard-container">
      <h2 class="mb-4" style="background:linear-gradient(45deg,#fff,#2196f3,#64b5f6);background-size:200% 200%;-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;animation:gradientShift 3s ease-in-out infinite;">
        <i class="bi bi-person-x me-2"></i>Peserta Belum Absen
      </h2>

      <div class="row mb-3">
        <div class="col-md-6">
          <div class="alert alert-info" style="background:linear-gradient(135deg,rgba(33,150,243,.2),rgba(25,118,210,.3));border:1px solid rgba(33,150,243,.3);color:#fff;border-radius:15px;backdrop-filter:blur(15px)">
            <i class="fas fa-info-circle me-2"></i><strong>Event:</strong> <?= htmlspecialchars($event['name'] ?? '-') ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="alert alert-info" style="background:linear-gradient(135deg,rgba(33,150,243,.2),rgba(25,118,210,.3));border:1px solid rgba(33,150,243,.3);color:#fff;border-radius:15px;backdrop-filter:blur(15px)">
            <i class="fas fa-chart-bar me-2"></i><strong>Total:</strong> <span id="totalPeserta"><?= count($participants) ?></span> peserta belum absen
          </div>
        </div>
      </div>

      <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="mb-0"><i class="fas fa-users me-2"></i>Data Peserta Belum Absen</h4>
          <div class="d-flex gap-2">
            <button class="btn btn-light" onclick="toggleSessionType()" id="sessionToggleBtn"><i class="fas fa-exchange-alt me-1"></i> Show Kategori</button>
            <button class="btn btn-light" onclick="toggleColumnType()" id="toggleBtn"><i class="fas fa-exchange-alt me-1"></i> Show Page No</button>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-2">
            <label class="form-label mb-1"><strong><i class="fas fa-calendar-alt me-1"></i>Filter Sesi:</strong></label>
            <div class="dropdown-wrapper"><select id="filterSesi" class="form-select">
                <option value="">Semua Sesi</option>
                <?php foreach ($sessionList as $sid => $sname): ?>
                  <option value="<?= htmlspecialchars($sid) ?>"><?= htmlspecialchars($sname) ?></option>
                <?php endforeach; ?>
              </select></div>
          </div>
          <div class="col-md-2">
            <label class="form-label mb-1"><strong><i class="fas fa-graduation-cap me-1"></i>Filter Prodi:</strong></label>
            <div class="dropdown-wrapper"><select id="filterProdi" class="form-select">
                <option value="">Semua Prodi</option>
                <?php foreach ($prodiList as $prodi): if ($prodi === '') continue; ?>
                  <option value="<?= htmlspecialchars($prodi) ?>"><?= htmlspecialchars($prodi) ?></option>
                <?php endforeach; ?>
              </select></div>
          </div>
          <div class="col-md-2">
            <label class="form-label mb-1"><strong><i class="fas fa-list me-1"></i>Show:</strong></label>
            <div class="dropdown-wrapper"><select id="recordsPerPage" class="form-select">
                <option value="10" selected>10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="all">All</option>
              </select></div>
          </div>
          <div class="col-md-6">
            <label class="form-label mb-1" style="opacity:0;">Actions</label>
            <div class="d-flex justify-content-end gap-2">
              <div class="auto-refresh-indicator me-2">
                <i class="fas fa-sync-alt me-1"></i>
                <span id="refreshCountdown">30</span>s
              </div>
              <button class="btn btn-light me-2" onclick="refreshTable()"><i class="fas fa-sync-alt me-1"></i>Reset</button>
              <!-- <button class="btn btn-light me-2" onclick="toggleSearch()"><i class="fas fa-search me-1"></i>Search</button> -->
              <button class="btn btn-success" onclick="exportToExcel()"><i class="fas fa-file-excel me-1"></i>Export Excel</button>
            </div>
          </div>
        </div>


        <div id="searchContainer" class="row mb-3">
          <div class="col-12"><input type="text" id="searchInput" class="form-control" placeholder="Cari nama, NIS, atau prodi..."></div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover" id="participantsTable">
            <thead class="table-dark">
              <tr>
                <th>No</th>
                <th onclick="sortTable(1)" style="cursor:pointer;">Nama <i class="fas fa-sort ms-1"></i></th>
                <th onclick="sortTable(2)" style="cursor:pointer;">NIS <i class="fas fa-sort ms-1"></i></th>
                <th>Prodi</th>
                <th onclick="sortTable(4)" style="cursor:pointer;"><span id="sessionHeader">Sesi</span> <i class="fas fa-sort ms-1"></i></th>
                <th onclick="sortTable(5)" style="cursor:pointer;"><span id="columnHeader">Seat No</span> <i class="fas fa-sort ms-1"></i></th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="tableBody"></tbody>
          </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
          <div id="paginationInfo" class="text-white"></div>
          <nav aria-label="Page navigation">
            <ul class="pagination mb-0" id="pagination"></ul>
          </nav>
        </div>
      </div>
    </div>
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
    let showSeatNo = true;
    let showSession = true;

    document.addEventListener('DOMContentLoaded', () => {
      // Load saved column preference from localStorage
      const savedColumnType = localStorage.getItem('ucm_status_column_type');
      if (savedColumnType !== null) {
        showSeatNo = savedColumnType === 'seat';
        updateColumnDisplay();
      }

      // Load saved session preference from localStorage
      const savedSessionType = localStorage.getItem('ucm_status_session_type');
      if (savedSessionType !== null) {
        showSession = savedSessionType === 'session';
        updateSessionDisplay();
      }

      updateTable();
      setupEventListeners();
      enhanceAllSelects();
      startAutoRefresh();
    });

    function setupEventListeners() {
      document.getElementById('filterSesi')?.addEventListener('change', applyFilters);
      document.getElementById('filterProdi')?.addEventListener('change', applyFilters);
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

    function updateColumnDisplay() {
      const headerEl = document.getElementById('columnHeader');
      const toggleBtn = document.getElementById('toggleBtn');

      if (!headerEl || !toggleBtn) return;

      if (showSeatNo) {
        headerEl.textContent = 'Seat No';
        toggleBtn.innerHTML = '<i class="fas fa-exchange-alt me-1"></i> Show Page No';
      } else {
        headerEl.textContent = 'Page No';
        toggleBtn.innerHTML = '<i class="fas fa-exchange-alt me-1"></i> Show Seat No';
      }
    }

    function updateSessionDisplay() {
      const sessionHeaderEl = document.getElementById('sessionHeader');
      const sessionToggleBtn = document.getElementById('sessionToggleBtn');

      if (!sessionHeaderEl || !sessionToggleBtn) return;

      if (showSession) {
        sessionHeaderEl.textContent = 'Sesi';
        sessionToggleBtn.innerHTML = '<i class="fas fa-exchange-alt me-1"></i> Show Kategori';
      } else {
        sessionHeaderEl.textContent = 'Kategori';
        sessionToggleBtn.innerHTML = '<i class="fas fa-exchange-alt me-1"></i> Show Sesi';
      }
    }

    function toggleColumnType() {
      showSeatNo = !showSeatNo;

      // Save preference to localStorage
      localStorage.setItem('ucm_status_column_type', showSeatNo ? 'seat' : 'page');

      updateColumnDisplay();

      // Re-render table with new column data
      updateTable();
    }

    function toggleSessionType() {
      showSession = !showSession;

      // Save preference to localStorage
      localStorage.setItem('ucm_status_session_type', showSession ? 'session' : 'kategori');

      updateSessionDisplay();

      // Re-render table with new session/kategori data
      updateTable();
    }

    // function toggleSearch() {
    //   const el = document.getElementById('searchContainer');
    //   if (!el) return;
    //   if (el.style.display === 'none') {
    //     el.style.display = 'block';
    //     document.getElementById('searchInput').focus();
    //   } else {
    //     el.style.display = 'none';
    //     document.getElementById('searchInput').value = '';
    //     applyFilters();
    //   }
    // }

    function refreshTable() {
      sortColumn = -1;
      sortDirection = 'asc';

      // Load saved column preference or default to seat numbers
      const savedColumnType = localStorage.getItem('ucm_status_column_type');
      showSeatNo = savedColumnType ? (savedColumnType === 'seat') : true;

      // Load saved session preference or default to session
      const savedSessionType = localStorage.getItem('ucm_status_session_type');
      showSession = savedSessionType ? (savedSessionType === 'session') : true;

      document.getElementById('filterSesi').value = '';
      document.getElementById('filterProdi').value = '';
      document.getElementById('recordsPerPage').value = '10';
      recordsPerPage = 10;

      // Update toggle buttons and headers based on saved preferences
      updateColumnDisplay();
      updateSessionDisplay();

      syncAllMsLabels();
      const s = document.getElementById('searchContainer');
      // if (s) s.style.display = 'none';
      const si = document.getElementById('searchInput');
      if (si) si.value = '';
      filteredData = [...participants];
      filteredData.sort((a, b) => new Date(a.created_date) - new Date(b.created_date));
      updateTable();
      updateSortIcons(-1);
    }

    function applyFilters() {
      const sesi = document.getElementById('filterSesi').value;
      const prodi = document.getElementById('filterProdi').value;
      const q = (document.getElementById('searchInput')?.value || '').toLowerCase();
      filteredData = participants.filter(p => {
        const mSesi = !sesi || p.session == sesi;
        const mProdi = !prodi || p.desc_2 === prodi;
        const searchText = [p.full_name, p.nis, p.desc_2].join(' ').toLowerCase();
        const mSearch = !q || searchText.includes(q);
        return mSesi && mProdi && mSearch;
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
            A = parseInt(a.id, 10) || 0;
            B = parseInt(b.id, 10) || 0;
            break;
          case 1:
            A = (a.full_name || '').toLowerCase();
            B = (b.full_name || '').toLowerCase();
            break;
          case 2:
            A = (a.nis || '').toLowerCase();
            B = (b.nis || '').toLowerCase();
            break;
          case 3:
            A = (a.desc_2 || '').toLowerCase();
            B = (b.desc_2 || '').toLowerCase();
            break;
          case 4:
            if (showSession) {
              A = parseInt(a.session, 10) || 0;
              B = parseInt(b.session, 10) || 0;
            } else {
              A = (a.kategori || '').toLowerCase();
              B = (b.kategori || '').toLowerCase();
            }
            break;
          case 5:
            if (showSeatNo) {
              A = (a.seat_no || '').toLowerCase();
              B = (b.seat_no || '').toLowerCase();
            } else {
              A = (a.page_no || '').toLowerCase();
              B = (b.page_no || '').toLowerCase();
            }
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
        const headers = document.querySelectorAll('#participantsTable thead th');
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
        tr.innerHTML = `<td colspan="8" class="text-center text-white" style="padding:4rem 2rem!important;background:linear-gradient(135deg, rgba(33,150,243,.1), rgba(25,118,210,.15));margin:1rem;"><div style="background:rgba(255,255,255,.1);backdrop-filter:blur(10px);border-radius:20px;padding:2.5rem 2rem;border:1px solid rgba(255,255,255,.15);"><i class='fas fa-database me-3' style='font-size:3.5rem;color:rgba(255,255,255,.7);margin-bottom:1rem;'></i><h3 class='mb-3' style='font-size:1.8rem;font-weight:600;background:linear-gradient(45deg,#fff,#2196f3,#64b5f6);background-size:200% 200%;-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;'>Tidak ada data yang ditemukan</h3><p class='mb-0' style='font-size:1rem;color:rgba(255,255,255,.8);line-height:1.5;'>Coba ubah filter atau kata kunci pencarian Anda</p></div></td>`;
        tbody.appendChild(tr);
      } else {
        page.forEach((p, i) => tbody.appendChild(createTableRow(p, start + i + 1)));
      }
      updatePagination();
      updatePaginationInfo();
    }

    function createTableRow(p, no) {
      const tr = document.createElement('tr');

      // Toggle between session and kategori based on current mode
      const sessionColumn = showSession ?
        `<span class=\"badge bg-primary badge-session\">Sesi ${p.session}${p.session_name?'<br><small>'+p.session_name+'</small>':''}</span>` :
        `<span style="color: white;">${p.kategori || '-'}</span>`;
      const columnValue = showSeatNo ? (p.seat_no && p.seat_no !== '' ? p.seat_no : '-') : (p.page_no && p.page_no !== '' ? p.page_no : '-');

      tr.innerHTML = `<td class='text-white'>${no}</td><td class='text-white'>${p.full_name||''}</td><td class='text-white'>${p.nis||''}</td><td class='text-white'>${p.desc_2||''}</td><td>${sessionColumn}</td><td class='text-white'>${columnValue}</td><td><span class=\"badge bg-secondary\"><i class=\"fas fa-clock me-1\"></i>None</span></td>`;
      return tr;
    }

    function updatePagination() {
      const totalPages = Math.ceil(filteredData.length / recordsPerPage);
      const ul = document.getElementById('pagination');
      if (!ul) return;
      ul.innerHTML = '';
      if (totalPages <= 1) return;
      const prev = document.createElement('li');
      prev.className = `page-item ${currentPage===1?'disabled':''}`;
      prev.innerHTML = `<a class='page-link' href='javascript:void(0)' onclick='event.preventDefault(); changePage(${currentPage-1})'><i class="fas fa-chevron-left me-1"></i>Previous</a>`;
      ul.appendChild(prev);
      for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
          const li = document.createElement('li');
          li.className = `page-item ${i===currentPage?'active':''}`;
          li.innerHTML = `<a class='page-link' href='javascript:void(0)' onclick='event.preventDefault(); changePage(${i})'>${i}</a>`;
          ul.appendChild(li);
        } else if (i === currentPage - 3 || i === currentPage + 3) {
          const el = document.createElement('li');
          el.className = 'page-item disabled';
          el.innerHTML = '<span class="page-link">...</span>';
          ul.appendChild(el);
        }
      }
      const next = document.createElement('li');
      next.className = `page-item ${currentPage===totalPages?'disabled':''}`;
      next.innerHTML = `<a class='page-link' href='javascript:void(0)' onclick='event.preventDefault(); changePage(${currentPage+1})'>Next<i class="fas fa-chevron-right ms-1"></i></a>`;
      ul.appendChild(next);
    }

    function updatePaginationInfo() {
      const start = (currentPage - 1) * recordsPerPage + 1;
      const end = Math.min(currentPage * recordsPerPage, filteredData.length);
      document.getElementById('paginationInfo').textContent = `Showing ${start} to ${end} of ${filteredData.length} entries`;
    }

    function changePage(p) {
      const totalPages = Math.ceil(filteredData.length / recordsPerPage);
      if (p >= 1 && p <= totalPages) {
        currentPage = p;
        updateTable();
      }
      return false;
    }

    function exportToExcel() {
      const table = document.getElementById('participantsTable');
      const wb = XLSX.utils.table_to_book(table, {
        sheet: 'Belum_Absen'
      });
      const filename = 'belum_absen_' + new Date().toISOString().split('T')[0] + '.xlsx';
      XLSX.writeFile(wb, filename);
    }

    // Auto-refresh logic
    let refreshInterval, countdownInterval, refreshCountdown = 30;

    function startAutoRefresh() {
      refreshInterval = setInterval(refreshTableData, 30000);
      startCountdown();
    }

    function startCountdown() {
      refreshCountdown = 30;
      updateCountdownDisplay();
      clearInterval(countdownInterval);
      countdownInterval = setInterval(() => {
        refreshCountdown--;
        if (refreshCountdown <= 0) {
          refreshCountdown = 30;
        }
        updateCountdownDisplay();
      }, 1000);
    }

    function updateCountdownDisplay() {
      const el = document.getElementById('refreshCountdown');
      if (el) el.textContent = refreshCountdown;
    }

    function refreshTableData() {
      const currentSesi = document.getElementById('filterSesi').value;
      const currentProdi = document.getElementById('filterProdi').value;
      const currentSearch = document.getElementById('searchInput').value;
      const currentRpp = document.getElementById('recordsPerPage').value;
      const page = currentPage;
      const sCol = sortColumn;
      const sDir = sortDirection;
      fetch(window.location.href, {
        cache: 'no-store'
      }).then(r => r.text()).then(html => {
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const scripts = Array.from(doc.querySelectorAll('script')).map(s => s.textContent || '').join('\n');
        const m = scripts.match(/const participants\s*=\s*(\[.*?\]);/s);
        if (m) {
          const newData = JSON.parse(m[1]);
          participants.length = 0;
          participants.push(...newData);
          filteredData = [...participants];
          document.getElementById('filterSesi').value = currentSesi;
          document.getElementById('filterProdi').value = currentProdi;
          document.getElementById('searchInput').value = currentSearch;
          document.getElementById('recordsPerPage').value = currentRpp;
          recordsPerPage = currentRpp === 'all' ? participants.length : parseInt(currentRpp);
          syncAllMsLabels();
          currentPage = page;
          sortColumn = sCol;
          sortDirection = sDir;
          applyFilters();
          document.getElementById('totalPeserta').textContent = participants.length;
        }
      }).catch(() => {});
      startCountdown();
    }
  </script>
</body>

</html>