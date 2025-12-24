<div class="container mt-4">
    <h2>Edit Event: <?= esc($event['title']) ?></h2>

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

    <!-- Petunjuk Pengisian -->
    <div class="alert alert-info mb-4">
        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Petunjuk Pengisian</h6>
        <ul class="mb-0">
            <li><strong>Data Event:</strong> Isi informasi dasar event seperti nama, deskripsi, tanggal, dan lokasi</li>
            <li><strong>Informasi Sertifikat:</strong> Data ini akan muncul di sertifikat peserta. Pastikan mengisi dengan benar</li>
            <li><strong>Logo & Tanda Tangan:</strong> Kosongkan jika tidak ingin mengubah file yang sudah ada</li>
        </ul>
    </div>

    <form action="<?= base_url('events/update/' . esc($event['id'])) ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Judul Event</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= old('title', esc($event['title'])) ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?= old('description', esc($event['description'])) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Tanggal Mulai</label>
            <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="<?= old('start_date', date('Y-m-d\TH:i', strtotime(esc($event['start_date'])))) ?>" required>
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">Tanggal Selesai</label>
            <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="<?= old('end_date', date('Y-m-d\TH:i', strtotime(esc($event['end_date'])))) ?>" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Lokasi</label>
            <input type="text" class="form-control" id="location" name="location" value="<?= old('location', esc($event['location'])) ?>" required>
        </div>
        <div class="mb-3">
            <label for="max_participants" class="form-label">Batas Peserta (Kosongkan untuk tanpa batas)</label>
            <input type="number" class="form-control" id="max_participants" name="max_participants" value="<?= old('max_participants', esc($event['max_participants'])) ?>" min="0">
        </div>

        <!-- Informasi Sertifikat -->
        <div class="card mt-4 mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-certificate me-2"></i>Informasi Sertifikat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="institution_name" class="form-label">Nama Institusi/Organisasi</label>
                            <input type="text" class="form-control" id="institution_name" name="institution_name" value="<?= old('institution_name', esc($event['institution_name'] ?? '')) ?>" placeholder="Contoh: Universitas ABC">
                        </div>
                        <div class="mb-3">
                            <label for="organizer_name" class="form-label">Nama Ketua Penyelenggara</label>
                            <input type="text" class="form-control" id="organizer_name" name="organizer_name" value="<?= old('organizer_name', esc($event['organizer_name'] ?? '')) ?>" placeholder="Contoh: Dr. John Doe">
                        </div>
                        <div class="mb-3">
                            <label for="organizer_role" class="form-label">Jabatan Ketua Penyelenggara</label>
                            <input type="text" class="form-control" id="organizer_role" name="organizer_role" value="<?= old('organizer_role', esc($event['organizer_role'] ?? '')) ?>" placeholder="Contoh: Ketua Panitia">
                        </div>
                        <div class="mb-3">
                            <label for="certificate_number" class="form-label">Format Nomor Sertifikat</label>
                            <input type="text" class="form-control" id="certificate_number" name="certificate_number" value="<?= old('certificate_number', esc($event['certificate_number'] ?? '')) ?>" placeholder="Contoh: 001/CERT/2024">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="institution_logo" class="form-label">Logo Institusi (PNG/JPG)</label>
                            <?php if (!empty($event['institution_logo'])): ?>
                                <div class="mb-2">
                                    <img src="<?= base_url('uploads/institutions/' . $event['institution_logo']) ?>" alt="Logo saat ini" style="max-height: 100px;" class="img-thumbnail">
                                    <small class="d-block text-muted">Logo saat ini</small>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="institution_logo" name="institution_logo" accept=".png,.jpg,.jpeg">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah logo</small>
                        </div>
                        <div class="mb-3">
                            <label for="organizer_signature" class="form-label">Tanda Tangan Ketua (PNG)</label>
                            <?php if (!empty($event['organizer_signature'])): ?>
                                <div class="mb-2">
                                    <img src="<?= base_url('uploads/signatures/' . $event['organizer_signature']) ?>" alt="TTD saat ini" style="max-height: 100px;" class="img-thumbnail">
                                    <small class="d-block text-muted">Tanda tangan saat ini</small>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="organizer_signature" name="organizer_signature" accept=".png">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah tanda tangan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Event</button>
        <a href="<?= base_url('/events') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>