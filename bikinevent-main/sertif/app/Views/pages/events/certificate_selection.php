<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pilih Event untuk Cetak Sertifikat</h2>
</div>

<div class="card shadow">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-download"></i> Download Sertifikat</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Event</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Anda belum terdaftar di event manapun atau belum ada event yang selesai.
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; ?>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <strong><?= esc($event['title']) ?></strong>
                                    <br>
                                    <small class="text-muted"><?= esc($event['location']) ?></small>
                                </td>
                                <td><?= esc(date('d/m/Y', strtotime($event['start_date']))) ?></td>
                                <td><?= esc(date('d/m/Y', strtotime($event['end_date']))) ?></td>
                                <td>
                                    <?php if ($event['end_date'] < date('Y-m-d H:i:s')): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Selesai
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> Berlangsung
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($event['end_date'] < date('Y-m-d H:i:s')): ?>
                                        <a href="<?= base_url('/events/download-certificate/' . esc($event['id'])) ?>"
                                            class="btn btn-sm btn-success"
                                            title="Download PDF">
                                            <i class="fas fa-download"></i> Download PDF
                                        </a>
                                    <?php else: ?>
                                        <div class="text-center">
                                            <small class="text-muted d-block mb-2">
                                                <i class="fas fa-clock"></i>
                                                Sertifikat tersedia setelah event selesai
                                            </small>
                                            <div class="countdown-timer"
                                                data-end-date="<?= esc($event['end_date']) ?>"
                                                data-event-id="<?= esc($event['id']) ?>">
                                                <div class="countdown-display">
                                                    <div class="countdown-item">
                                                        <span class="countdown-number days">0</span>
                                                        <span class="countdown-label">Hari</span>
                                                    </div>
                                                    <div class="countdown-item">
                                                        <span class="countdown-number hours">0</span>
                                                        <span class="countdown-label">Jam</span>
                                                    </div>
                                                    <div class="countdown-item">
                                                        <span class="countdown-number minutes">0</span>
                                                        <span class="countdown-label">Menit</span>
                                                    </div>
                                                    <div class="countdown-item">
                                                        <span class="countdown-number seconds">0</span>
                                                        <span class="countdown-label">Detik</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($events)): ?>
            <div class="mt-3">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Informasi:</strong>
                    <ul class="mb-0">
                        <li>Sertifikat hanya dapat didownload setelah event selesai</li>
                        <li>Klik "Download PDF" untuk mengunduh sertifikat dalam format PDF</li>
                        <li>File PDF akan otomatis terdownload ke perangkat Anda</li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .btn-group .btn {
        margin-right: 5px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .badge {
        font-size: 12px;
        padding: 5px 8px;
    }

    .table td {
        vertical-align: middle;
    }

    .alert ul {
        padding-left: 20px;
        margin-top: 10px;
    }

    .alert ul li {
        margin-bottom: 5px;
    }

    /* Countdown Timer Styles */
    .countdown-timer {
        margin-top: 10px;
    }

    .countdown-display {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .countdown-item {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        padding: 8px 6px;
        min-width: 45px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }

    .countdown-item:hover {
        transform: translateY(-2px);
    }

    .countdown-number {
        display: block;
        font-size: 16px;
        font-weight: bold;
        color: white;
        line-height: 1;
        transition: transform 0.1s ease;
    }

    .countdown-label {
        display: block;
        font-size: 10px;
        color: rgba(255, 255, 255, 0.9);
        margin-top: 2px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .countdown-expired {
        color: #28a745;
        font-weight: bold;
        padding: 8px 12px;
        background: rgba(40, 167, 69, 0.1);
        border-radius: 6px;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }

    @media (max-width: 768px) {
        .countdown-display {
            gap: 4px;
        }

        .countdown-item {
            min-width: 35px;
            padding: 6px 4px;
        }

        .countdown-number {
            font-size: 14px;
        }

        .countdown-label {
            font-size: 9px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all countdown timers
        const countdownTimers = document.querySelectorAll('.countdown-timer');

        countdownTimers.forEach(function(timer) {
            const endDate = timer.getAttribute('data-end-date');
            const eventId = timer.getAttribute('data-event-id');

            if (endDate) {
                startCountdown(timer, endDate, eventId);
            }
        });

        function startCountdown(timerElement, endDateStr, eventId) {
            const endDate = new Date(endDateStr).getTime();

            const countdownInterval = setInterval(function() {
                const now = new Date().getTime();
                const distance = endDate - now;

                if (distance < 0) {
                    // Event has ended - show completion message and refresh page
                    clearInterval(countdownInterval);
                    timerElement.innerHTML = '<div class="countdown-expired"><i class="fas fa-check-circle"></i> Event Selesai! Sertifikat Tersedia</div>';

                    // Refresh page after 3 seconds to update the download button
                    setTimeout(function() {
                        location.reload();
                    }, 3000);

                    return;
                }

                // Calculate time units
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Update the display
                const daysElement = timerElement.querySelector('.days');
                const hoursElement = timerElement.querySelector('.hours');
                const minutesElement = timerElement.querySelector('.minutes');
                const secondsElement = timerElement.querySelector('.seconds');

                if (daysElement) daysElement.textContent = days.toString().padStart(2, '0');
                if (hoursElement) hoursElement.textContent = hours.toString().padStart(2, '0');
                if (minutesElement) minutesElement.textContent = minutes.toString().padStart(2, '0');
                if (secondsElement) secondsElement.textContent = seconds.toString().padStart(2, '0');

                // Add animation effect when seconds change
                if (secondsElement) {
                    secondsElement.style.transform = 'scale(1.1)';
                    setTimeout(function() {
                        secondsElement.style.transform = 'scale(1)';
                    }, 100);
                }

            }, 1000);
        }
    });
</script>