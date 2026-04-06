<?php $__env->startSection('title', 'Dashboard Admin - Sistem Peminjaman Alat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">

    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2>
                   <i class="fas fa-tachometer-alt text-orange me-2"></i>

                    Dashboard Admin
                </h2>
                <p class="text-muted mb-0">
                    Ringkasan sistem peminjaman alat
                </p>
            </div>
            <div class="text-end">
                <h5>
                    Halo, <?php echo e(Auth::user()->name ?? 'Admin'); ?>!
                </h5>
                <small class="text-muted">
                    Terakhir login: <?php echo e(\Carbon\Carbon::now()->format('j F Y')); ?>

                </small>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo e($totalUsers); ?></h3>
                        <p class="mb-0">Total Peminjam</p>
                    </div>
                    <i class="fas fa-user fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo e($totalPetugas); ?></h3>
                        <p class="mb-0">Total Petugas</p>
                    </div>
                    <i class="fas fa-user-tie fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo e($totalAlat); ?></h3>
                        <p class="mb-0">Total Alat</p>
                    </div>
                    <i class="fas fa-boxes fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo e($totalPeminjaman); ?></h3>
                        <p class="mb-0">Total Peminjaman</p>
                    </div>
                    <i class="fas fa-history fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo e($totalPengembalian); ?></h3>
                        <p class="mb-0">Total Pengembalian</p>
                    </div>
                    <i class="fas fa-calendar-check fa-2x text-orange"></i>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Peminjaman_alatNEW\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>