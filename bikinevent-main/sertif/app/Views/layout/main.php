<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'BikinEvent.my.id' ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= base_url('assets/images/bikinevent-favicon.svg') ?>">
    <link rel="alternate icon" href="<?= base_url('assets/images/bikinevent-favicon.svg') ?>">
    <link rel="mask-icon" href="<?= base_url('assets/images/bikinevent-favicon.svg') ?>" color="#667eea">
    <meta name="theme-color" content="#667eea">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --hover-bg: rgba(255, 255, 255, 0.1);
            --text-light: rgba(255, 255, 255, 0.8);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background: var(--primary-gradient);
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            background-color: #f8f9fa;
            min-height: 100vh;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand {
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-brand i {
            font-size: 1.8rem;
        }

        .sidebar-brand .brand-icon {
            width: 32px;
            height: 32px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
            transition: transform 0.2s ease;
        }

        .sidebar-brand:hover .brand-icon {
            transform: scale(1.05);
        }

        .nav-section {
            padding: 1rem 0;
        }

        .nav-section-title {
            color: var(--text-light);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
        }

        .nav-item {
            margin: 0.2rem 0.8rem;
        }

        .nav-link {
            color: var(--text-light) !important;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.3s;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .nav-link:hover {
            color: white !important;
            background-color: var(--hover-bg);
            transform: translateX(5px);
        }

        .nav-link.active {
            color: white !important;
            background-color: var(--hover-bg);
            font-weight: 500;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background-color: white;
            border-radius: 0 4px 4px 0;
        }

        .main-header {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .main-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            color: #2d3748;
        }

        .alert {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }

            .sidebar.show {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.sidebar-open {
                margin-left: var(--sidebar-width);
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="<?= base_url('/') ?>" class="sidebar-brand">
                <img src="<?= base_url('assets/images/bikinevent-icon.svg') ?>" alt="BikinEvent Icon" class="brand-icon">
                <span>BikinEvent.my.id</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Menu Utama</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= ($page == 'dashboard' || $page == 'home') ? 'active' : '' ?>" href="<?= base_url('/') ?>">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <?php if (session()->get('isLoggedIn')): ?>
                    <?php if (session()->get('role') === 'admin'): ?>
                        <div class="nav-section-title mt-3">Administrasi</div>
                        <li class="nav-item">
                            <a class="nav-link <?= ($page == 'events/index' || $page == 'events/create' || strpos($page, 'events/edit') !== false || strpos($page, 'events/participants') !== false) ? 'active' : '' ?>" href="<?= base_url('/events') ?>">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Kelola Event</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (strpos($page, 'reports') !== false && $page != 'reports/participants') ? 'active' : '' ?>" href="<?= base_url('/reports') ?>">
                                <i class="fas fa-chart-line"></i>
                                <span>Reports</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (strpos($page, 'reports/participants') !== false) ? 'active' : '' ?>" href="<?= base_url('/reports/participants') ?>">
                                <i class="fas fa-users"></i>
                                <span>Kelola Peserta</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (session()->get('role') === 'participant'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($page == 'events/participant_index') ? 'active' : '' ?>" href="<?= base_url('/events/participant_index') ?>">
                                <i class="fas fa-calendar"></i>
                                <span>Lihat Daftar Event</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($page == 'events/certificate' || strpos($page, 'events/certificate') !== false) ? 'active' : '' ?>" href="<?= base_url('/events/certificate') ?>">
                                <i class="fas fa-certificate"></i>
                                <span>Lihat Sertifikat</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <div class="nav-section-title mt-3">Akun</div>
                    <li class="nav-item">
                        <a class="nav-link <?= ($page == 'profile') ? 'active' : '' ?>" href="<?= base_url('/profile') ?>">
                            <i class="fas fa-user-cog"></i>
                            <span>Profil</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/logout') ?>">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($page == 'login') ? 'active' : '' ?>" href="<?= base_url('/login') ?>">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($page == 'register') ? 'active' : '' ?>" href="<?= base_url('/register') ?>">
                            <i class="fas fa-user-plus"></i>
                            <span>Register</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="main-header">
            <h1><?= $title ?? 'BikinEvent.my.id' ?></h1>
        </div>

        <div class="container-fluid">
            <!-- Alert Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <?php
            if ($page == 'home') {
                echo view('pages/home');
            } else {
                echo view('pages/' . $page);
            }
            ?>
        </div>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toggle Sidebar for Mobile -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.createElement('button');
            toggleBtn.className = 'btn btn-primary d-md-none position-fixed';
            toggleBtn.style.cssText = 'top: 1rem; left: 1rem; z-index: 1001;';
            toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
            document.body.appendChild(toggleBtn);

            toggleBtn.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('show');
                document.querySelector('.main-content').classList.toggle('sidebar-open');
            });
        });
    </script>
</body>

</html>