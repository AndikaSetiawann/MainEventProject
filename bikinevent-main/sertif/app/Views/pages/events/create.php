<div class="container">
    <h2>Tambah Event</h2>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Petunjuk Pengisian -->
    <div class="alert alert-info mb-4">
        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Petunjuk Pengisian</h6>
        <ul class="mb-0">
            <li><strong>Data Event:</strong> Isi informasi dasar event seperti nama, deskripsi, tanggal, dan lokasi</li>
            <li><strong>Informasi Sertifikat:</strong> Data ini akan muncul di sertifikat peserta. Pastikan mengisi dengan benar</li>
            <li><strong>Logo Institusi:</strong> Upload logo dalam format PNG/JPG, maksimal 2MB</li>
            <li><strong>Tanda Tangan:</strong> Upload tanda tangan dalam format PNG dengan background transparan, maksimal 1MB</li>
        </ul>
    </div>

    <form action="<?= base_url('/events/store') ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Nama Event:</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= old('title') ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi:</label>
            <textarea class="form-control" id="description" name="description" required><?= old('description') ?></textarea>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Tanggal Mulai:</label>
            <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="<?= old('start_date') ?>" required>
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">Tanggal Selesai:</label>
            <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="<?= old('end_date') ?>" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Lokasi:</label>
            <input type="text" class="form-control" id="location" name="location" value="<?= old('location') ?>" required>
        </div>
        <div class="mb-3">
            <label for="max_participants" class="form-label">Batas Peserta (Kosongkan untuk tanpa batas)</label>
            <input type="number" class="form-control" id="max_participants" name="max_participants" value="<?= old('max_participants') ?>" min="0">
        </div>

        <!-- Informasi Sertifikat -->
        <div class="card mt-4 mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-certificate me-2"></i>Informasi Sertifikat</h5>
                <small class="text-muted">Data ini akan muncul di sertifikat peserta. Pastikan mengisi dengan benar.</small>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="institution_name" class="form-label">Nama Institusi/Organisasi</label>
                            <input type="text" class="form-control" id="institution_name" name="institution_name" value="<?= old('institution_name') ?>" placeholder="Contoh: Universitas ABC">
                        </div>
                        <div class="mb-3">
                            <label for="organizer_name" class="form-label">Nama Ketua Penyelenggara</label>
                            <input type="text" class="form-control" id="organizer_name" name="organizer_name" value="<?= old('organizer_name') ?>" placeholder="Contoh: Dr. John Doe">
                        </div>
                        <div class="mb-3">
                            <label for="organizer_role" class="form-label">Jabatan Ketua Penyelenggara</label>
                            <input type="text" class="form-control" id="organizer_role" name="organizer_role" value="<?= old('organizer_role') ?>" placeholder="Contoh: Ketua Panitia">
                        </div>
                        <div class="mb-3">
                            <label for="certificate_number" class="form-label">Format Nomor Sertifikat</label>
                            <input type="text" class="form-control" id="certificate_number" name="certificate_number" value="<?= old('certificate_number') ?>" placeholder="Contoh: 001/CERT/2024">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="institution_logo" class="form-label">Logo Institusi (PNG/JPG)</label>
                            <input type="file" class="form-control" id="institution_logo" name="institution_logo" accept=".png,.jpg,.jpeg">
                            <small class="text-muted">Ukuran maksimal 2MB, format PNG/JPG</small>
                        </div>
                        <div class="mb-3">
                            <label for="organizer_signature" class="form-label">Tanda Tangan Ketua (PNG)</label>
                            <input type="file" class="form-control" id="organizer_signature" name="organizer_signature" accept=".png">
                            <small class="text-muted">Ukuran maksimal 1MB, format PNG dengan background transparan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('/events') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>