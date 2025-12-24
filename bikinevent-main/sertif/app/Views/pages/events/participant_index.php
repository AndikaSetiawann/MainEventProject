<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Event</h2>
</div>

<div class="card shadow">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-calendar"></i> Daftar Event</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Event</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Peserta</th> <!-- New column -->
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada event yang ditambahkan</td> <!-- Adjusted colspan -->
                        </tr>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td><?= esc($event['id']) ?></td>
                                <td><?= esc($event['title']) ?></td>
                                <td><?= esc($event['start_date']) ?></td>
                                <td><?= esc($event['location']) ?></td>
                                <td>
                                    <?php
                                    $status = '';
                                    $currentDate = date('Y-m-d H:i:s');
                                    if ($event['end_date'] < $currentDate) {
                                        $status = '<span class="badge bg-secondary">Berakhir</span>';
                                    } elseif ($event['start_date'] > $currentDate) {
                                        $status = '<span class="badge bg-info">Aktif</span>';
                                    } else {
                                        $status = '<span class="badge bg-success">Sedang Berlangsung</span>';
                                    }

                                    $current = $event['current_participants'];
                                    $max = $event['max_participants'];

                                    if ($max !== null && $max > 0 && $current >= $max) {
                                        $status = '<span class="badge bg-danger">Penuh</span>';
                                    }
                                    echo $status;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $current = $event['current_participants'];
                                    $max = $event['max_participants'];
                                    if ($max !== null && $max > 0) {
                                        if ($current >= $max) {
                                            echo '<span class="badge bg-danger">Full (' . esc($current) . '/' . esc($max) . ')</span>';
                                        } else {
                                            echo '<span class="badge bg-info">' . esc($current) . '/' . esc($max) . '</span>';
                                        }
                                    } else {
                                        echo '<span class="badge bg-secondary">' . esc($current) . '</span>'; // No limit
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if (! in_array($event['id'], $registeredEventIds)): ?>
                                        <?php if ($event['end_date'] < date('Y-m-d H:i:s')): ?>
                                            <button class="btn btn-sm btn-secondary me-2" disabled>Pendaftaran Ditutup</button>
                                        <?php else: ?>
                                            <a href="<?= base_url('/events/register/' . esc($event['id'])) ?>" class="btn btn-sm btn-primary me-2">Daftar</a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary me-2" disabled>Terdaftar</button>
                                        <?php if ($event['end_date'] < date('Y-m-d H:i:s')): ?>
                                            <a href="<?= base_url('/events/download-certificate/' . esc($event['id'])) ?>" class="btn btn-sm btn-success">Download Sertifikat</a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-warning" disabled>Event Belum Selesai</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>