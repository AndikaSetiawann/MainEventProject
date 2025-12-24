<div class="container">
    <?php if (isset($event)): ?>
        <h2>Registrasi Event: <?= esc($event['title']) ?></h2>
        <form action="<?= base_url('/participants/register') ?>" method="post">
            <input type="hidden" name="event_id" value="<?= esc($event_id) ?>">

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <?php
            // Determine initial values for form fields
            $nameValue = old('name') ?? (session()->get('isLoggedIn') ? session()->get('name') : '');
            $emailValue = old('email') ?? (session()->get('isLoggedIn') ? session()->get('email') : '');
            $phoneValue = old('phone') ?? (session()->get('isLoggedIn') ? session()->get('phone_number') : '');
            ?>

            <div class="mb-3">
                <label for="name" class="form-label">Nama:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= esc($nameValue) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= esc($emailValue) ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Telepon:</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?= esc($phoneValue) ?>">
            </div>

            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>
    <?php else: ?>
        <h2>Daftar Event yang Tersedia</h2>
        <?php if (! empty($events)): ?>
            <div class="list-group">
                <?php foreach ($events as $event): ?>
                    <a href="<?= base_url('events/register/' . esc($event['id'])) ?>" class="list-group-item list-group-item-action">
                        <h5 class="mb-1"><?= esc($event['title']) ?></h5>
                        <p class="mb-1"><?= esc(substr($event['description'], 0, 100)) ?>...</p>
                        <small>Tanggal: <?= esc($event['start_date']) ?> - <?= esc($event['end_date']) ?></small><br>
                        <small>Lokasi: <?= esc($event['location']) ?></small>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Tidak ada event yang tersedia saat ini.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
