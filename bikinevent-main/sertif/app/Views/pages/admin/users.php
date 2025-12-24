<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-users text-primary me-2"></i> Kelola User</h5>
                        <a href="<?= base_url('/auth/createAdmin') ?>" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i> Tambah Admin Baru
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($users)): ?>
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <p class="mb-0">Belum ada user yang terdaftar</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">No. Telepon</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Tanggal Daftar</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <th scope="row"><?= $no++ ?></th>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?= esc($user['name']) ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= esc($user['email']) ?></td>
                                            <td><?= esc($user['phone_number'] ?? '-') ?></td>
                                            <td>
                                                <?php if ($user['role'] === 'admin'): ?>
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-user-shield me-1"></i> Admin
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-user me-1"></i> Participant
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i> Aktif
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <h4><?= count($users) ?></h4>
                                        <p class="mb-0">Total User</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user-shield fa-2x mb-2"></i>
                                        <h4><?= count(array_filter($users, function($user) { return $user['role'] === 'admin'; })) ?></h4>
                                        <p class="mb-0">Admin</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user fa-2x mb-2"></i>
                                        <h4><?= count(array_filter($users, function($user) { return $user['role'] === 'participant'; })) ?></h4>
                                        <p class="mb-0">Participant</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-check fa-2x mb-2"></i>
                                        <h4><?= count($users) ?></h4>
                                        <p class="mb-0">Aktif</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
}
</style>
<?= $this->endsection() ?>
