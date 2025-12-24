<!-- Dashboard Reports -->
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="fas fa-chart-line text-primary me-2"></i>Dashboard Reports</h2>
                    <p class="text-muted mb-0">Analytics dan statistik lengkap BikinEvent.my.id</p>
                </div>
                <div>
                    <a href="<?= base_url('/reports/participants') ?>" class="btn btn-success me-2">
                        <i class="fas fa-users me-1"></i> Kelola Peserta
                    </a>
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Print Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-gradient rounded-3 p-3">
                                <i class="fas fa-calendar-alt text-white fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Event</h6>
                            <h3 class="mb-0"><?= number_format($stats['total_events']) ?></h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                <?= $stats['completion_rate'] ?>% completed
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-gradient rounded-3 p-3">
                                <i class="fas fa-users text-white fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Peserta</h6>
                            <h3 class="mb-0"><?= number_format($stats['total_participants']) ?></h3>
                            <small class="text-info">
                                <i class="fas fa-user-plus me-1"></i>
                                <?= number_format($stats['total_users']) ?> registered users
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-gradient rounded-3 p-3">
                                <i class="fas fa-certificate text-white fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Sertifikat Diterbitkan</h6>
                            <h3 class="mb-0"><?= number_format($stats['certificates_issued']) ?></h3>
                            <small class="text-warning">
                                <i class="fas fa-award me-1"></i>
                                From <?= $stats['completed_events'] ?> completed events
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-gradient rounded-3 p-3">
                                <i class="fas fa-clock text-white fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Event Mendatang</h6>
                            <h3 class="mb-0"><?= number_format($stats['upcoming_events']) ?></h3>
                            <small class="text-primary">
                                <i class="fas fa-calendar-plus me-1"></i>
                                <?= $stats['active_events'] ?> active events
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Monthly Trends Chart -->
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        Trend Bulanan Event & Peserta
                    </h5>
                    <p class="text-muted small mb-0">Data 12 bulan terakhir</p>
                </div>
                <div class="card-body">
                    <canvas id="trendsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Event Status Pie Chart -->
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie text-success me-2"></i>
                        Status Event
                    </h5>
                    <p class="text-muted small mb-0">Distribusi status event</p>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="statusChart" width="300" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Events & Top Events -->
    <div class="row mb-4">
        <!-- Recent Events -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock text-info me-2"></i>
                        Event Terbaru
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Event</th>
                                    <th class="border-0">Peserta</th>
                                    <th class="border-0">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentEvents as $event): ?>
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-1"><?= esc($event['title']) ?></h6>
                                                <small class="text-muted"><?= esc($event['location']) ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill">
                                                <?= $event['participant_count'] ?> peserta
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('d M Y', strtotime($event['start_date'])) ?>
                                            </small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Events -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy text-warning me-2"></i>
                        Event Terpopuler
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Rank</th>
                                    <th class="border-0">Event</th>
                                    <th class="border-0">Peserta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topEvents as $index => $event): ?>
                                    <tr>
                                        <td>
                                            <?php if ($index === 0): ?>
                                                <i class="fas fa-crown text-warning"></i>
                                            <?php elseif ($index === 1): ?>
                                                <i class="fas fa-medal text-secondary"></i>
                                            <?php elseif ($index === 2): ?>
                                                <i class="fas fa-award text-warning"></i>
                                            <?php else: ?>
                                                <span class="text-muted">#<?= $index + 1 ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1"><?= esc($event['title']) ?></h6>
                                                <small class="text-muted"><?= esc($event['location']) ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-success rounded-pill">
                                                <?= $event['participant_count'] ?> peserta
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart data from PHP
    const chartData = <?= json_encode($chartData) ?>;

    // Monthly Trends Chart
    const trendsCtx = document.getElementById('trendsChart').getContext('2d');
    new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Event',
                data: chartData.events,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Peserta',
                data: chartData.participants,
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            }
        }
    });

    // Status Pie Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Upcoming', 'Active'],
            datasets: [{
                data: [<?= $stats['completed_events'] ?>, <?= $stats['upcoming_events'] ?>, <?= $stats['active_events'] ?>],
                backgroundColor: [
                    '#198754',
                    '#0d6efd',
                    '#ffc107'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
</script>