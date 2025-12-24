<div class="row">
    <div class="col-12">
        <div class="jumbotron bg-primary text-white p-5 rounded mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h1 class="display-4">Selamat Datang di BikinEvent.my.id</h1>
            <p class="lead">Platform terbaik untuk membuat, mengelola, dan mengikuti event impianmu!</p>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.3);">
            <p>Buat event seminar, workshop, webinar, dan acara lainnya dengan mudah. Dapatkan sertifikat digital berkualitas tinggi!</p>
            <a class="btn btn-light btn-lg" href="<?= base_url('/events') ?>" role="button">
                <i class="fas fa-plus"></i> Mulai Kelola Event
            </a>
        </div>
    </div>
</div>
<div class="col-md-3 mb-3">
    <a href="<?= base_url('/reports/events') ?>" class="btn btn-warning btn-block">
        <i class="fas fa-chart-line"></i><br>
        Lihat Laporan
    </a>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="col-auto">
    <i class="fas fa-calendar fa-2x text-gray-300"></i>
</div>
</div>
</div>
</div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Peserta</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_participants ?? 0 ?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-users fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Event Mendatang</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $upcoming_events ?? 0 ?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('/events/create') ?>" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i><br>
                            Tambah Event Baru
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('/events') ?>" class="btn btn-info btn-block">
                            <i class="fas fa-list"></i><br>
                            Lihat Semua Event
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('/events') ?>" class="btn btn-success btn-block">
                            <i class="fas fa-user-plus"></i><br>
                            Kelola Peserta
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('/reports/events') ?>" class="btn btn-warning btn-block">
                            <i class="fas fa-chart-line"></i><br>
                            Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }

    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }

    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }

    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }

    .text-gray-300 {
        color: #dddfeb !important;
    }

    .text-gray-800 {
        color: #5a5c69 !important;
    }

    .btn-block {
        display: block;
        width: 100%;
        padding: 1rem;
        text-align: center;
    }
</style>