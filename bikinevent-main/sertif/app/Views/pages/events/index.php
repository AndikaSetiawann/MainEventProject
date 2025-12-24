<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Kelola Event</h2>
    <a href="<?= base_url('/events/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Event
    </a>
</div>

<script>
    function confirmDelete(eventId) {
        if (confirm('Apakah Anda yakin ingin menghapus event ini?')) {
            window.location.href = "<?= base_url('/events/delete/') ?>/" + eventId;
        }
    }
</script>

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
                        <th>Peserta</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada event yang ditambahkan</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td><?= esc($event['id']) ?></td>
                                <td><?= esc($event['title']) ?></td>
                                <td><?= esc($event['start_date']) ?></td>
                                <td><?= esc($event['location']) ?></td>
                                <td><?= ($event['start_date'] > date('Y-m-d H:i:s')) ? 'Upcoming' : 'Completed' ?></td>
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
                                    <a href="<?= base_url('/events/participants/' . esc($event['id'])) ?>" class="btn btn-sm btn-link">Lihat Peserta</a>
                                </td>
                                <td>
                                    <a href="<?= base_url('/events/edit/' . esc($event['id'])) ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="javascript:void(0)" onclick="confirmDelete(<?= esc($event['id']) ?>)" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmDelete(eventId) {
        if (confirm('Apakah Anda yakin ingin menghapus event ini?')) {
            window.location.href = "<?= base_url('/events/delete/') ?>/" + eventId;
        }
    }
</script>