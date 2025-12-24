<div class="container">
    <h2>Daftar Peserta Event</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($participants)): ?>
                <tr>
                    <td colspan="5">Tidak ada peserta terdaftar</td>
                </tr>
            <?php else: ?>
                <?php foreach ($participants as $participant): ?>
                    <tr>
                        <td><?= $participant['id'] ?></td>
                        <td><?= $participant['name'] ?></td>
                        <td><?= $participant['email'] ?></td>
                        <td><?= $participant['phone'] ?></td>
                        <td><a href="<?= site_url('/participants/certificate/' . $participant['id']) ?>" class="btn btn-sm btn-primary">Cetak Sertifikat</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
