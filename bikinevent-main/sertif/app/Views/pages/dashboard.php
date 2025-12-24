<div class="row">
    <div class="col-12">
        <div class="welcome-card bg-gradient-primary text-white p-5 rounded-4 mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 fw-bold mb-3">Selamat Datang, <?= session()->get('name') ?>!</h1>
                    <p class="lead mb-4">Selamat datang di panel admin BikinEvent.my.id! Kelola semua event dan peserta dengan mudah.</p>
                    <div class="d-flex gap-3">
                        <a class="btn btn-light btn-lg px-4" href="<?= base_url('/events') ?>" role="button">
                            <i class="fas fa-calendar me-2"></i> Lihat Daftar Event
                        </a>
                        <a class="btn btn-outline-light btn-lg px-4" href="<?= base_url('/reports/participants') ?>" role="button">
                            <i class="fas fa-users me-2"></i> Lihat Peserta
                        </a>
                    </div>
                </div>
                <div class="col-md-4 d-none d-md-block">
                    <img src="https://cdn-icons-png.flaticon.com/512/1968/1968641.png" alt="BikinEvent.my.id" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2">Total Event</h6>
                        <h3 class="mb-0 fw-bold"><?= $total_events ?? 0 ?></h3>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-calendar fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2">Total Peserta</h6>
                        <h3 class="mb-0 fw-bold"><?= $total_participants ?? 0 ?></h3>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-users fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2">Event Mendatang</h6>
                        <h3 class="mb-0 fw-bold"><?= $upcoming_events ?? 0 ?></h3>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-clock fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2">Sertifikat Diterbitkan</h6>
                        <h3 class="mb-0 fw-bold"><?= $issued_certificates ?? 0 ?></h3>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-certificate fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-bolt text-warning me-2"></i> Quick Actions</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-3">
                        <a href="<?= base_url('/events/create') ?>" class="quick-action-btn btn btn-primary w-100 p-4 rounded-4 text-center">
                            <i class="fas fa-plus fa-2x mb-3"></i>
                            <h6 class="mb-0">Tambah Event Baru</h6>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= base_url('/events') ?>" class="quick-action-btn btn btn-info w-100 p-4 rounded-4 text-center">
                            <i class="fas fa-list fa-2x mb-3"></i>
                            <h6 class="mb-0">Lihat Semua Event</h6>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= base_url('/reports/participants') ?>" class="quick-action-btn btn btn-success w-100 p-4 rounded-4 text-center">
                            <i class="fas fa-user-plus fa-2x mb-3"></i>
                            <h6 class="mb-0">Kelola Peserta</h6>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= base_url('/auth/createAdmin') ?>" class="quick-action-btn btn btn-warning w-100 p-4 rounded-4 text-center">
                            <i class="fas fa-user-shield fa-2x mb-3"></i>
                            <h6 class="mb-0">Buat Akun Admin</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .welcome-card {
        position: relative;
        overflow: hidden;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
        opacity: 0.1;
        pointer-events: none;
    }

    .stat-card {
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quick-action-btn {
        transition: all 0.3s;
        border: none;
    }

    .quick-action-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .quick-action-btn i {
        transition: transform 0.3s;
    }

    .quick-action-btn:hover i {
        transform: scale(1.1);
    }
</style>