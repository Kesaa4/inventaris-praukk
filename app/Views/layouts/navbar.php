<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="/dashboard">
            <i class="bi bi-box-seam me-2"></i>Sistem Peminjaman
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= (current_url() == base_url('dashboard')) ? 'active' : '' ?>" href="/dashboard">
                        Dashboard
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'barang') !== false) ? 'active' : '' ?>" href="/barang">
                        Barang
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'kategori') !== false) ? 'active' : '' ?>" href="/kategori">
                        Kategori
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'pinjam') !== false) ? 'active' : '' ?>" href="/pinjam">
                        Peminjaman
                    </a>
                </li>
                
                <?php if (session()->get('role') === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'user') !== false) ? 'active' : '' ?>" href="/user">
                        User
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'activity-log') !== false) ? 'active' : '' ?>" href="/activity-log">
                        Activity Log
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i>
                        <?php 
                            $userProfileModel = new \App\Models\UserProfileModel();
                            $profile = $userProfileModel->where('id_user', session('id_user'))->first();
                            $displayName = $profile['nama'] ?? explode('@', session('email'))[0];
                            echo esc($displayName);
                        ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="/profile">
                                <i class="bi bi-person me-2"></i>Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="/logout">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
