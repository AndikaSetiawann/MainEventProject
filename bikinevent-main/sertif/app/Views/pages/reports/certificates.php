<!-- Certificate Reports -->
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="fas fa-certificate text-warning me-2"></i>Report Sertifikat</h2>
                    <p class="text-muted mb-0">Laporan sertifikat yang telah diterbitkan dan statistik</p>
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
                    <div class="text-warning mb-2">
                        <i class="fas fa-certificate fa-2x"></i>
                    </div>
                    <h4 class="mb-1"><?= count($certificates) ?></h4>
                    <p class="text-muted mb-0">Total Sertifikat</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="text-success mb-2">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h4 class="mb-1"><?= $stats['total_certificates'] ?></h4>
                    <p class="text-muted mb-0">Sertifikat Diterbitkan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="text-info mb-2">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                    <h4 class="mb-1"><?= $stats['this_month_certificates'] ?></h4>
                    <p class="text-muted mb-0">Bulan Ini</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="text-primary mb-2">
                        <i class="fas fa-download fa-2x"></i>
                    </div>
                    <h4 class="mb-1">
                        <?php 
                        $downloadedToday = array_filter($certificates, function($cert) {
                            return date('Y-m-d', strtotime($cert['end_date'])) === date('Y-m-d');
                        });
                        echo count($downloadedToday);
                        ?>
                    </h4>
                    <p class="text-muted mb-0">Download Hari Ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Filter Bulan</label>
                    <select class="form-select" id="monthFilter">
                        <option value="">Semua Bulan</option>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= sprintf('%02d', $i) ?>" <?= date('m') == sprintf('%02d', $i) ? 'selected' : '' ?>>
                                <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter Tahun</label>
                    <select class="form-select" id="yearFilter">
                        <option value="">Semua Tahun</option>
                        <?php for ($year = date('Y'); $year >= date('Y') - 5; $year--): ?>
                            <option value="<?= $year ?>" <?= date('Y') == $year ? 'selected' : '' ?>>
                                <?= $year ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter Event</label>
                    <select class="form-select" id="eventFilter">
                        <option value="">Semua Event</option>
                        <?php 
                        $events = array_unique(array_column($certificates, 'event_title'));
                        foreach ($events as $event): ?>
                            <option value="<?= esc($event) ?>"><?= esc($event) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cari Peserta</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-light" placeholder="Nama peserta..." id="searchInput">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificates Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="card-title mb-0">
                <i class="fas fa-list text-warning me-2"></i>
                Daftar Sertifikat
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="certificatesTable">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 ps-4">Peserta</th>
                            <th class="border-0">Event</th>
                            <th class="border-0">Tanggal Event</th>
                            <th class="border-0">Tanggal Selesai</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($certificates as $certificate): ?>
                        <tr data-month="<?= date('m', strtotime($certificate['end_date'])) ?>" 
                            data-year="<?= date('Y', strtotime($certificate['end_date'])) ?>"
                            data-event="<?= esc($certificate['event_title']) ?>">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1"><?= esc($certificate['participant_name']) ?></h6>
                                        <small class="text-muted"><?= esc($certificate['email']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-1"><?= esc($certificate['event_title']) ?></h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        Event selesai
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">
                                    <?= date('d M Y', strtotime($certificate['created_at'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    <?= date('d M Y', strtotime($certificate['end_date'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success rounded-pill">
                                    <i class="fas fa-certificate me-1"></i>Tersedia
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('events/view/' . $certificate['event_id']) ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Lihat Event">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('events/certificate/' . $certificate['event_id'] . '/' . $certificate['user_id']) ?>" 
                                       class="btn btn-sm btn-outline-warning" title="Download Sertifikat">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-info" 
                                            onclick="previewCertificate(<?= $certificate['event_id'] ?>, <?= $certificate['user_id'] ?>)" 
                                            title="Preview Sertifikat">
                                        <i class="fas fa-search"></i>
                                    </button>
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
// Filter functionality
document.getElementById('monthFilter').addEventListener('change', filterTable);
document.getElementById('yearFilter').addEventListener('change', filterTable);
document.getElementById('eventFilter').addEventListener('change', filterTable);
document.getElementById('searchInput').addEventListener('keyup', filterTable);

function filterTable() {
    const monthFilter = document.getElementById('monthFilter').value;
    const yearFilter = document.getElementById('yearFilter').value;
    const eventFilter = document.getElementById('eventFilter').value;
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const tableRows = document.querySelectorAll('#certificatesTable tbody tr');
    
    tableRows.forEach(row => {
        const month = row.getAttribute('data-month');
        const year = row.getAttribute('data-year');
        const event = row.getAttribute('data-event');
        const participantName = row.querySelector('h6').textContent.toLowerCase();
        
        let showRow = true;
        
        // Month filter
        if (monthFilter && month !== monthFilter) {
            showRow = false;
        }
        
        // Year filter
        if (yearFilter && year !== yearFilter) {
            showRow = false;
        }
        
        // Event filter
        if (eventFilter && event !== eventFilter) {
            showRow = false;
        }
        
        // Search filter
        if (searchTerm && !participantName.includes(searchTerm)) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

// Export functions
function exportToExcel() {
    const filters = {
        month: document.getElementById('monthFilter').value,
        year: document.getElementById('yearFilter').value,
        event: document.getElementById('eventFilter').value,
        search: document.getElementById('searchInput').value
    };
    
    const params = new URLSearchParams(filters);
    window.location.href = '<?= base_url('reports/export/certificates/excel') ?>?' + params.toString();
}

function exportToPDF() {
    const filters = {
        month: document.getElementById('monthFilter').value,
        year: document.getElementById('yearFilter').value,
        event: document.getElementById('eventFilter').value,
        search: document.getElementById('searchInput').value
    };
    
    const params = new URLSearchParams(filters);
    window.location.href = '<?= base_url('reports/export/certificates/pdf') ?>?' + params.toString();
}

function previewCertificate(eventId, userId) {
    window.open('<?= base_url('events/certificate/') ?>' + eventId + '/' + userId, '_blank');
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
    background-color: rgba(255, 193, 7, 0.05);
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

.form-select:focus, .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.input-group-text {
    border-right: none;
}
</style>
