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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link i {
            width: 25px;
        }

        .main-content {
            padding: 20px;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: #667eea !important;
            font-weight: 600;
        }

        .user-dropdown .dropdown-toggle::after {
            display: none;
        }

        .user-dropdown .dropdown-menu {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php if (session()->get('isLoggedIn')): ?>
                <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                    <div class="text-center mb-4">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <img src="<?= base_url('assets/images/bikinevent-favicon.svg') ?>" alt="BikinEvent Icon" style="width: 24px; height: 24px;">
                            <h4 class="mb-0">BikinEvent.my.id</h4>
                        </div>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= current_url() == base_url('/dashboard') ? 'active' : '' ?>" href="<?= base_url('/dashboard') ?>">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <?php if (session()->get('role') === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), '/events') !== false ? 'active' : '' ?>" href="<?= base_url('/events') ?>">
                                    <i class="fas fa-calendar"></i> Event
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos(current_url(), '/reports') !== false ? 'active' : '' ?>" href="<?= base_url('/reports/events') ?>">
                                    <i class="fas fa-chart-bar"></i> Laporan
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Main Content -->
            <div class="<?= session()->get('isLoggedIn') ? 'col-md-9 col-lg-10 ms-sm-auto' : 'col-12' ?> main-content">
                <!-- Top Navbar -->
                <?php if (session()->get('isLoggedIn')): ?>
                    <nav class="navbar navbar-expand-lg navbar-light mb-4">
                        <div class="container-fluid">
                            <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target=".sidebar">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="ms-auto">
                                <div class="dropdown user-dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-user-circle me-1"></i> <?= session()->get('name') ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="<?= base_url('/profile') ?>">
                                                <i class="fas fa-user-cog me-2"></i> Profil
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="<?= base_url('/logout') ?>">
                                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </nav>
                <?php endif; ?>

                <!-- Page Content -->
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>