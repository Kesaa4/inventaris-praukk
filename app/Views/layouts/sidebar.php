<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="/dashboard" class="sidebar-brand">
            <i class="bi bi-box-seam me-2"></i>
            <span>Sistem Inventaris</span>
        </a>
        <button class="sidebar-toggle d-lg-none" id="sidebarClose">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="sidebar-menu">
        <div class="menu-section">
            <div class="menu-title">MENU UTAMA</div>

            <a href="/barang" class="menu-item <?= (strpos(current_url(), 'barang') !== false) ? 'active' : '' ?>">
                <i class="bi bi-box-seam"></i>
                <span>Barang</span>
            </a>

            <a href="/kategori" class="menu-item <?= (strpos(current_url(), 'kategori') !== false) ? 'active' : '' ?>">
                <i class="bi bi-tags"></i>
                <span>Kategori</span>
            </a>

            <a href="/pinjam" class="menu-item <?= (strpos(current_url(), 'pinjam') !== false) ? 'active' : '' ?>">
                <i class="bi bi-arrow-left-right"></i>
                <span>Peminjaman</span>
            </a>
        </div>

        <?php if (session()->get('role') === 'admin'): ?>
        <div class="menu-section">
            <div class="menu-title">ADMIN</div>
            
            <a href="/user" class="menu-item <?= (strpos(current_url(), 'user') !== false) ? 'active' : '' ?>">
                <i class="bi bi-people"></i>
                <span>User</span>
            </a>

            <a href="/activity-log" class="menu-item <?= (strpos(current_url(), 'activity-log') !== false) ? 'active' : '' ?>">
                <i class="bi bi-clock-history"></i>
                <span>Activity Log</span>
            </a>
        </div>
        <?php endif; ?>

        <div class="menu-section">
            <div class="menu-title">AKUN</div>
            
            <a href="/profile" class="menu-item <?= (strpos(current_url(), 'profile') !== false) ? 'active' : '' ?>">
                <i class="bi bi-person-circle"></i>
                <span>Profile</span>
            </a>

            <a href="/logout" class="menu-item text-danger">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="user-details">
                <div class="user-name">
                    <?php 
                        $userProfileModel = new \App\Models\UserProfileModel();
                        $profile = $userProfileModel->where('id_user', session('id_user'))->first();
                        $displayName = $profile['nama'] ?? explode('@', session('email'))[0];
                        echo esc($displayName);
                    ?>
                </div>
                <div class="user-role">
                    <?= ucfirst(session('role')) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sidebar Overlay (for mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar Toggle Button (for mobile) -->
<button class="sidebar-toggle-btn d-lg-none" id="sidebarToggle">
    <i class="bi bi-list"></i>
</button>
