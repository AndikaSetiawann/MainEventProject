<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0"><?= esc($event['title']) ?></h3>
        </div>
        <div class="card-body">
            <p class="card-text"><strong>Deskripsi:</strong> <?= esc($event['description']) ?></p>
            <p class="card-text"><strong>Tanggal Mulai:</strong> <?= esc($event['start_date']) ?></p>
            <p class="card-text"><strong>Tanggal Selesai:</strong> <?= esc($event['end_date']) ?></p>
            <p class="card-text"><strong>Lokasi:</strong> <?= esc($event['location']) ?></p>
            <p class="card-text">
                <strong>Peserta:</strong>
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
            </p>
            <hr>
            <?php
            $isFull = ($event['max_participants'] !== null && $event['max_participants'] > 0 && $event['current_participants'] >= $event['max_participants']);
            ?>
            <a href="<?= base_url('events/register/' . esc($event['id'])) ?>" class="btn btn-success <?= $isFull ? 'disabled' : '' ?>" <?= $isFull ? 'aria-disabled="true"' : '' ?>>
                <?= $isFull ? 'Event Penuh' : 'Daftar Event Ini' ?>
            </a>
            <a href="<?= base_url('events') ?>" class="btn btn-secondary">Kembali ke Daftar Event</a>
        </div>
    </div>
</div>
