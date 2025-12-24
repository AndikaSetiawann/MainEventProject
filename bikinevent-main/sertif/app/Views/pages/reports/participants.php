<!-- Participant Reports -->
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="fas fa-users text-success me-2"></i>Report Peserta</h2>
                    <p class="text-muted mb-0">Laporan detail peserta dan analisis keikutsertaan</p>
                </div>
                <div>
                    <a href="<?= base_url('/reports') ?>" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Reports
                    </a>
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
                    <div class="text-success mb-2">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h4 class="mb-1"><?= count($participants) ?></h4>
                    <p class="text-muted mb-0">Total Peserta</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="text-primary mb-2">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                    <h4 class="mb-1"><?= count($events) ?></h4>
                    <p class="text-muted mb-0">Event Tersedia</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="text-warning mb-2">
                        <i class="fas fa-certificate fa-2x"></i>
                    </div>
                    <h4 class="mb-1">
                        <?php
                        $certificates = array_filter($participants, function ($participant) {
                            return strtotime($participant['end_date']) < time();
                        });
                        echo count($certificates);
                        ?>
                    </h4>
                    <p class="text-muted mb-0">Sertifikat Tersedia</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="text-info mb-2">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h4 class="mb-1">
                        <?php
                        $thisMonth = array_filter($participants, function ($participant) {
                            return date('Y-m', strtotime($participant['created_at'])) === date('Y-m');
                        });
                        echo count($thisMonth);
                        ?>
                    </h4>
                    <p class="text-muted mb-0">Peserta Bulan Ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Filter Event</label>
                    <select class="form-select" id="eventFilter">
                        <option value="">Semua Event</option>
                        <?php foreach ($events as $event): ?>
                            <option value="<?= $event['id'] ?>"><?= esc($event['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filter Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="completed">Event Selesai</option>
                        <option value="ongoing">Event Berlangsung</option>
                        <option value="upcoming">Event Mendatang</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cari Peserta</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-light" placeholder="Nama atau email..." id="searchInput">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Participants Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="card-title mb-0">
                <i class="fas fa-list text-success me-2"></i>
                Daftar Peserta
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="participantsTable">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 ps-4">Peserta</th>
                            <th class="border-0">Event</th>
                            <th class="border-0">Tanggal Daftar</th>
                            <th class="border-0">Status Event</th>
                            <th class="border-0">Sertifikat</th>
                            <th class="border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($participants as $participant): ?>
                            <tr data-event-id="<?= $participant['event_id'] ?>"
                                data-status="<?php
                                                $now = time();
                                                $startTime = strtotime($participant['start_date']);
                                                $endTime = strtotime($participant['end_date']);

                                                if ($endTime < $now) {
                                                    echo 'completed';
                                                } elseif ($startTime <= $now && $endTime >= $now) {
                                                    echo 'ongoing';
                                                } else {
                                                    echo 'upcoming';
                                                }
                                                ?>">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1"><?= esc($participant['participant_name']) ?></h6>
                                            <small class="text-muted"><?= esc($participant['email']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-1"><?= esc($participant['event_title']) ?></h6>
                                        <small class="text-muted">
                                            <?= date('d M Y', strtotime($participant['start_date'])) ?> -
                                            <?= date('d M Y', strtotime($participant['end_date'])) ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        <?= date('d M Y H:i', strtotime($participant['created_at'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $now = time();
                                    $startTime = strtotime($participant['start_date']);
                                    $endTime = strtotime($participant['end_date']);

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
                                    <?php if (strtotime($participant['end_date']) < time()): ?>
                                        <span class="badge bg-success rounded-pill">
                                            <i class="fas fa-certificate me-1"></i>Tersedia
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary rounded-pill">
                                            <i class="fas fa-hourglass-half me-1"></i>Belum Tersedia
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('events/view/' . $participant['event_id']) ?>"
                                        class="btn btn-sm btn-outline-primary" title="Lihat Event">
                                        <i class="fas fa-eye"></i> Lihat Event
                                    </a>
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
    // Filter functionality
    document.getElementById('eventFilter').addEventListener('change', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);
    document.getElementById('searchInput').addEventListener('keyup', filterTable);

    function filterTable() {
        const eventFilter = document.getElementById('eventFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const tableRows = document.querySelectorAll('#participantsTable tbody tr');

        tableRows.forEach(row => {
            const eventId = row.getAttribute('data-event-id');
            const status = row.getAttribute('data-status');
            const participantName = row.querySelector('h6').textContent.toLowerCase();
            const email = row.querySelector('small').textContent.toLowerCase();

            let showRow = true;

            // Event filter
            if (eventFilter && eventId !== eventFilter) {
                showRow = false;
            }

            // Status filter
            if (statusFilter && status !== statusFilter) {
                showRow = false;
            }

            // Search filter
            if (searchTerm && !participantName.includes(searchTerm) && !email.includes(searchTerm)) {
                showRow = false;
            }

            row.style.display = showRow ? '' : 'none';
        });
    }

    // Export functions
    function exportToExcel() {
        const filters = {
            event: document.getElementById('eventFilter').value,
            status: document.getElementById('statusFilter').value,
            search: document.getElementById('searchInput').value
        };

        const params = new URLSearchParams(filters);
        window.location.href = '<?= base_url('reports/export/participants/excel') ?>?' + params.toString();
    }

    function exportToPDF() {
        const filters = {
            event: document.getElementById('eventFilter').value,
            status: document.getElementById('statusFilter').value,
            search: document.getElementById('searchInput').value
        };

        const params = new URLSearchParams(filters);
        window.location.href = '<?= base_url('reports/export/participants/pdf') ?>?' + params.toString();
    }

    // Add animations
    document.addEventListener('DOMContentLoaded', function() {
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
        background-color: rgba(25, 135, 84, 0.05);
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

    .form-select:focus,
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .input-group-text {
        border-right: none;
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