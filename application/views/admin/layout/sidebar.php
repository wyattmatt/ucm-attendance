        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2 class="sidebar-brand">UCM</h2>
                <span class="sidebar-brand-sub">Attendance</span>
            </div>
            <nav class="sidebar-nav">
                <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= $this->router->class === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('admin/events') ?>" class="nav-link <?= $this->router->class === 'events' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Event</span>
                </a>
                <a href="<?= base_url('admin/attendance') ?>" class="nav-link <?= $this->router->class === 'attendance' ? 'active' : '' ?>">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Kehadiran</span>
                </a>
                <?php if ($admin['role'] === 'superadmin'): ?>
                    <a href="<?= base_url('admin/admins') ?>" class="nav-link <?= $this->router->class === 'admins' ? 'active' : '' ?>">
                        <i class="fas fa-users-cog"></i>
                        <span>Kelola Admin</span>
                    </a>
                <?php endif; ?>
            </nav>
            <div class="sidebar-footer">
                <div class="admin-info">
                    <div class="admin-avatar">
                        <?= strtoupper(substr($admin['name'], 0, 1)) ?>
                    </div>
                    <div class="admin-details">
                        <span class="admin-name"><?= htmlspecialchars($admin['name'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="admin-role"><?= ucfirst(htmlspecialchars($admin['role'], ENT_QUOTES, 'UTF-8')) ?></span>
                    </div>
                </div>
                <a href="#" onclick="event.preventDefault(); if(confirm('Yakin ingin logout?')) window.location.href='<?= base_url('auth/logout') ?>';" class="nav-link logout-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <!-- Top Bar -->
            <div class="topbar">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title"><?= htmlspecialchars($page_title ?? 'Dashboard', ENT_QUOTES, 'UTF-8') ?></h1>
                <div class="topbar-right">
                    <a href="<?= base_url() ?>" target="_blank" class="btn-view-site">
                        <i class="fas fa-external-link-alt"></i> Lihat Site
                    </a>
                </div>
            </div>

            <!-- Page Content -->
            <div class="page-content">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= $this->session->flashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= $this->session->flashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('csv_info')): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i><?= $this->session->flashdata('csv_info') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>