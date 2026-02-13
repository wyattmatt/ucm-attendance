<?php

/** @var array $events */ /** @var array $summary */ ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Attendance Auto-Updater Management</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-image: url('http://103.165.245.149/ucm_registration/uploads/bg.png?q=80&w=2070&auto=format&fit=crop');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      min-height: 100vh;
      min-height: 100dvh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: env(safe-area-inset-top) env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left);
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: linear-gradient(135deg, rgba(13, 71, 161, .15), rgba(25, 118, 210, .2), rgba(33, 150, 243, .1), rgba(0, 0, 0, .4));
      z-index: -1;
    }

    .main {
      width: 920px;
      max-width: 92%;
      border-radius: 28px;
      overflow: hidden;
      box-shadow: 0 25px 60px rgba(0, 0, 0, .6), 0 0 0 1px rgba(255, 255, 255, .1);
      backdrop-filter: blur(20px);
      color: #fff;
      animation: fadeInUp 1s ease-out;
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

    .section {
      padding: 22px;
    }

    .top {
      background: linear-gradient(135deg, rgba(0, 0, 0, .4), rgba(13, 71, 161, .2));
      border-bottom: 1px solid rgba(255, 255, 255, .15);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .middle {
      background: linear-gradient(135deg, rgba(0, 0, 0, .6), rgba(25, 118, 210, .25));
    }

    .grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 14px;
    }

    .panel {
      background: rgba(255, 255, 255, .06);
      border: 1px solid rgba(255, 255, 255, .15);
      border-radius: 14px;
      padding: 16px;
    }

    button {
      border-radius: 10px;
      border: 1px solid rgba(255, 255, 255, .25);
      padding: 10px 12px;
      background: rgba(33, 150, 243, .35);
      color: #fff;
      cursor: pointer;
      border-color: rgba(33, 150, 243, .6);
      transition: .2s;
    }

    button:disabled {
      opacity: .6;
      cursor: wait
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead th {
      text-align: left;
      font-weight: 600;
      padding: 10px;
      border-bottom: 1px solid rgba(255, 255, 255, .2);
    }

    tbody td {
      padding: 10px;
      border-bottom: 1px solid rgba(255, 255, 255, .08);
      color: #f0f3f8;
    }

    tbody tr:hover {
      background: rgba(255, 255, 255, .05);
    }

    pre {
      white-space: pre-wrap;
      word-break: break-word;
      background: rgba(0, 0, 0, .25);
      padding: 10px;
      border-radius: 10px;
      color: #dbeafe;
      max-height: 320px;
      overflow: auto
    }

    a.back {
      color: #cfe8ff;
      text-decoration: none
    }

    /* Dropdown chevron + multi-select menu */
    .dropdown-wrapper {
      position: relative;
      display: inline-block;
    }

    .ms-btn {
      background: rgba(255, 255, 255, .12);
      color: #fff;
      border: 1px solid rgba(255, 255, 255, .25);
      padding: 10px 42px 10px 12px;
      border-radius: 10px;
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
      transition: .25s;
    }

    .dropdown-wrapper.open::after {
      transform: translateY(-50%) rotate(180deg);
      color: rgba(255, 255, 255, .9)
    }

    .ms-menu {
      position: absolute;
      top: calc(100% + 8px);
      left: 0;
      min-width: 320px;
      width: max-content;
      background: rgba(0, 0, 0, .80);
      border: 1px solid rgba(255, 255, 255, .15);
      border-radius: 12px;
      padding: 10px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, .45);
      display: none;
      z-index: 20;
      backdrop-filter: blur(10px);
    }

    .dropdown-wrapper.open .ms-menu {
      display: block;
      animation: fadeInUp .18s ease-out;
    }

    .ms-row {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 6px 8px;
      border-radius: 8px;
    }

    .ms-row:hover {
      background: rgba(255, 255, 255, .06);
    }

    .ms-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 8px;
      padding: 6px 8px;
      border-top: 1px solid rgba(255, 255, 255, .1);
      margin-top: 8px
    }

    .ms-counter {
      opacity: .85;
      font-size: .9rem
    }
  </style>
</head>

<body>
  <div class="main">
    <div class="section top">
      <h2 style="margin:0">Auto-Updater</h2>
      <a class="back" href="<?= site_url('admin') ?>">⬅ Back</a>
    </div>
    <div class="section middle">
      <div class="grid">
        <div class="panel">
          <h3 style="margin:0 0 10px">Run Update for Events</h3>
          <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <div class="dropdown-wrapper" id="event_ms_wrap">
              <button type="button" class="ms-btn" id="event_ms_btn"><i class="fas fa-list-check me-1" style="margin-right: 8px;"></i><span id="ms_label">Select events</span></button>
              <div class="ms-menu" id="event_ms_menu">
                <?php foreach ($events as $e): ?>
                  <label class="ms-row">
                    <input type="checkbox" class="ms-opt" value="<?= $e['id'] ?>" />
                    <span><?= htmlspecialchars($e['name']) ?></span>
                  </label>
                <?php endforeach; ?>
                <div class="ms-actions">
                  <label><input type="checkbox" id="ms_select_all" /> Select all</label>
                  <span class="ms-counter" id="ms_counter">0 selected</span>
                </div>
              </div>
            </div>
            <button id="run_btn"><i class="fas fa-play me-1" style="margin-right: 8px;"></i>Run Selected</button>
          </div>
          <pre id="out" class="mt-3"></pre>
        </div>
        <div class="panel">
          <h3 style="margin:0 0 10px">Status Summary</h3>
          <table>
            <thead>
              <tr>
                <th>Event</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Pending</th>
                <th>None</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($summary as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['event_name'] ?? '') ?></td>
                  <td><?= (int)($row['present'] ?? 0) ?></td>
                  <td><?= (int)($row['absent'] ?? 0) ?></td>
                  <td><?= (int)($row['pending'] ?? 0) ?></td>
                  <td><?= (int)($row['none'] ?? 0) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script>
    // Multi-select open/close + selection handling
    const wrap = document.getElementById('event_ms_wrap');
    const btn = document.getElementById('event_ms_btn');
    const menu = document.getElementById('event_ms_menu');
    const label = document.getElementById('ms_label');
    const counter = document.getElementById('ms_counter');
    const selectAll = document.getElementById('ms_select_all');
    const opts = Array.from(menu.querySelectorAll('.ms-opt'));

    function updateLabel() {
      const selected = opts.filter(o => o.checked);
      const count = selected.length;
      counter.textContent = count + ' selected';
      if (count === 0) {
        label.textContent = 'Select events';
      } else if (count <= 2) {
        label.textContent = selected.map(o => o.nextElementSibling.textContent).join(', ');
      } else {
        label.textContent = count + ' events selected';
      }
      selectAll.checked = count === opts.length;
    }

    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      wrap.classList.toggle('open');
    });
    document.addEventListener('click', (e) => {
      if (!wrap.contains(e.target)) wrap.classList.remove('open');
    });
    opts.forEach(o => o.addEventListener('change', updateLabel));
    selectAll.addEventListener('change', () => {
      opts.forEach(o => o.checked = selectAll.checked);
      updateLabel();
    });
    updateLabel();

    // Run updates sequentially for selected events
    const runBtn = document.getElementById('run_btn');
    const out = document.getElementById('out');

    function log(line) {
      out.textContent += (out.textContent ? '\n' : '') + line;
      out.scrollTop = out.scrollHeight;
    }

    runBtn.onclick = async () => {
      const selectedIds = opts.filter(o => o.checked).map(o => o.value);
      if (selectedIds.length === 0) {
        Swal.fire({
          icon: 'warning',
          title: 'No events selected',
          text: 'Please select at least one event.',
          background: 'linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(25, 118, 210, 0.4))',
          color: 'white',
          backdrop: 'rgba(0, 0, 0, 0.6)',
          customClass: {
            popup: 'swal-glass-popup'
          },
          confirmButtonText: 'OK'
        });
        return;
      }
      runBtn.disabled = true;
      out.textContent = '';
      log('Starting updates for ' + selectedIds.length + ' event(s)...');
      for (const id of selectedIds) {
        log('→ Updating event ' + id + ' ...');
        try {
          const resp = await fetch(<?= json_encode(site_url('cron/run_event/')) ?> + id, {
            headers: {
              'Accept': 'application/json'
            }
          });
          const data = await resp.json();
          if (data && data.success) {
            const updated = (typeof data.updated === 'number') ? data.updated : (data.updated_count ?? 0);
            log('   ✓ Event ' + id + ': updated ' + updated + ' record(s)');
          } else {
            log('   ✗ Event ' + id + ': failed - ' + (data && data.error ? data.error : 'Unknown error'));
          }
        } catch (err) {
          log('   ✗ Event ' + id + ': error - ' + err.message);
        }
      }
      log('Done.');
      runBtn.disabled = false;
    };
  </script>
</body>

</html>