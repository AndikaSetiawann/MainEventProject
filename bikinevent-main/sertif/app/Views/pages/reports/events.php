<!-- Event Reports -->
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="fas fa-calendar-alt text-primary me-2"></i>Report Event</h2>
                    <p class="text-muted mb-0">Laporan detail semua event dan statistik peserta</p>
                </div>
                <div>
                    <button class="btn btn-success me-2" onclick="exportToExcel()">
                        <i class="fas fa-file-excel me-1"></i> Export Excel
                    </button>
                    <button class="btn btn-danger" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="text-primary mb-2">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                    <h4 class="mb-1"><?= count($events) ?></h4>
                    <p class="text-muted mb-0">Total Event</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="text-success mb-2">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h4 class="mb-1">
                        <?php
                        $completed = array_filter($events, function ($event) {
                            return strtotime($event['end_date']) < time();
                        });
                        echo count($completed);
                        ?>
                    </h4>
                    <p class="text-muted mb-0">Event Selesai</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="text-warning mb-2">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h4 class="mb-1">
                        <?php
                        $upcoming = array_filter($events, function ($event) {
                            return strtotime($event['start_date']) > time();
                        });
                        echo count($upcoming);
                        ?>
                    </h4>
                    <p class="text-muted mb-0">Event Mendatang</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="text-info mb-2">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h4 class="mb-1">
                        <?php
                        $totalParticipants = array_sum(array_column($events, 'participant_count'));
                        echo number_format($totalParticipants);
                        ?>
                    </h4>
                    <p class="text-muted mb-0">Total Peserta</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list text-primary me-2"></i>
                        Daftar Event
                    </h5>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-light" placeholder="Cari event..." id="searchInput">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="eventsTable">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 ps-4">Event</th>
                            <th class="border-0">Tanggal</th>
                            <th class="border-0">Lokasi</th>
                            <th class="border-0">Peserta</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($event['institution_logo'])): ?>
                                            <img src="<?= base_url('uploads/institutions/' . $event['institution_logo']) ?>"
                                                alt="Logo" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-primary rounded d-flex align-items-center justify-content-center me-3"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas fa-calendar-alt text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="mb-1"><?= esc($event['title']) ?></h6>
                                            <small class="text-muted"><?= esc($event['institution_name'] ?? 'BikinEvent.my.id') ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <small class="text-muted d-block">Mulai:</small>
                                        <strong><?= date('d M Y', strtotime($event['start_date'])) ?></strong>
                                        <small class="text-muted d-block">Selesai:</small>
                                        <strong><?= date('d M Y', strtotime($event['end_date'])) ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?= esc($event['location']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <h5 class="mb-1 text-primary"><?= $event['participant_count'] ?></h5>
                                        <small class="text-muted">peserta</small>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $now = time();
                                    $startTime = strtotime($event['start_date']);
                                    $endTime = strtotime($event['end_date']);

                                    if ($endTime < $now): ?>
                                        <span class="badge bg-success rounded-pill">
                                            <i class="fas fa-check me-1"></i>Selesai
                                        </span>
                                    <?php elseif ($startTime <= $now && $endTime >= $now): ?>
                                        <span class="badge bg-warning rounded-pill">
                                            <i class="fas fa-play me-1"></i>Berlangsung
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-info rounded-pill">
                                            <i class="fas fa-clock me-1"></i>Mendatang
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('events/view/' . $event['id']) ?>"
                                            class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('events/participants/' . $event['id']) ?>"
                                            class="btn btn-sm btn-outline-success" title="Lihat Peserta">
                                            <i class="fas fa-users"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#eventsTable tbody tr');

        tableRows.forEach(row => {
            const eventTitle = row.querySelector('h6').textContent.toLowerCase();
            const location = row.cells[2].textContent.toLowerCase();

            if (eventTitle.includes(searchTerm) || location.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Export functions
    function exportToExcel() {
        window.location.href = '<?= base_url('reports/export/events/excel') ?>';
    }

    function exportToPDF() {
        window.location.href = '<?= base_url('reports/export/events/pdf') ?>';
    }



    // Add some animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animate cards on load
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>

<style>
    .table tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }

    .btn-group .btn {
        transition: all 0.2s ease;
    }

    .btn-group .btn:hover {
        transform: translateY(-1px);
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .badge {
        font-size: 0.75em;
        padding: 0.5em 0.75em;
    }

    .input-group-text {
        border-right: none;
    }

    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>
}

.table th {
font-weight: 600;
color: #2d3748;
border-bottom: 2px solid #e2e8f0;
}

.table td {
vertical-align: middle;
color: #4a5568;
}

.badge {
padding: 0.5em 1em;
font-weight: 500;
}

.btn-sm {
padding: 0.4rem 0.8rem;
font-size: 0.875rem;
}

.card {
border-radius: 1rem;
overflow: hidden;
}

.card-header {
border-bottom: 1px solid #e2e8f0;
}

.table-hover tbody tr:hover {
background-color: #f8fafc;
}
</style>