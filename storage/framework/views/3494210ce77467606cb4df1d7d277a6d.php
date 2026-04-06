<?php $__env->startSection('title', 'Manajemen User - Sistem Peminjaman Alat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-users text-orange me-2"></i>Manajemen User</h2>
                <p class="text-muted mb-0">Kelola pengguna sistem</p>
            </div>
        </div>
    </div>

    <!-- Add User Button -->
 
    <!-- Users Table -->
    <div class="card card-custom table-custom">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-orange"></i>Daftar Pengguna</h5>
               <div class="mb-4">
        <button type="button" class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-plus text-white me-2"></i>Tambah User
        </button>
    </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="usersTable">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($user->nama); ?></td>
                            <td><?php echo e($user->username); ?></td>
                            <td>
                                <span class="status-badge
                                    <?php if($user->role == 'admin'): ?> status-dikembalikan
                                    <?php elseif($user->role == 'peminjam'): ?> status-dipinjam
                                    <?php elseif($user->role == 'petugas'): ?> status-belum_dikembalikan
                                    <?php else: ?> status-dipinjam
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($user->role)); ?>

                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-dipinjam">
                                    Aktif
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('admin.editUser', ['id' => $user->id_user])); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <form action="<?php echo e(route('admin.deleteUser', ['id' => $user->id_user])); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="text-center">Tidak ada data pengguna</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('admin.storeUser')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="no_telp" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" id="no_telp" name="no_telp">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">-- Pilih Role --</option>
                               
                                    <option value="petugas">Petugas</option>
                                    <option value="peminjam">Peminjam</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i>
                            <small>Semua petugas akan menggunakan dashboard yang sama. Contoh: petugas1, petugas2 login ke halaman dashboard petugas yang sama.</small>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-orange">Simpan User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    setTimeout(function() {
        if ($('#usersTable').length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#usersTable')) {
                try {
                    $('#usersTable').DataTable({
                        paging: true,
                        searching: true,
                        ordering: true,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                        pageLength: 10,
                        language: {
                            search: "Cari:",
                            lengthMenu: "Tampilkan _MENU_ data",
                            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                            infoEmpty: "Tidak ada data tersedia",
                            zeroRecords: "Tidak ditemukan hasil",
                            paginate: {
                                first: "Awal",
                                last: "Akhir",
                                next: "›",
                                previous: "‹"
                            }
                        }
                    });
                    console.log('✓ usersTable DataTable initialized');
                } catch (e) {
                    console.error('Error:', e);
                }
            }
        }
    }, 300);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Peminjaman_alatNEW\resources\views/admin/users.blade.php ENDPATH**/ ?>