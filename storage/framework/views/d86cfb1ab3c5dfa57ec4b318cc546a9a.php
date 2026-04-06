<?php $__env->startSection('title', 'Dashboard Peminjam - Sistem Peminjaman Alat'); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid px-0">

    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2>
                   <i class="fas fa-tachometer-alt text-orange me-2"></i>

                    Dashboard Peminjam
                </h2>
                <p class="text-muted mb-0">
                    Selamat datang di sistem peminjaman alat Anda
                </p>
            </div>
            <div class="text-end">
                <h5>
                    Halo, <?php echo e($user->nama ?? Auth::user()->name ?? 'Peminjam'); ?>!
                </h5>
                <small class="text-muted">
                    Terakhir login: <?php echo e(\Carbon\Carbon::now()->format('j F Y')); ?>

                </small>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo e($totalPeminjaman); ?></h3>
                        <p class="mb-0">Total Pinjaman</p>
                    </div>
                    <i class="fas fa-history fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo e($belumDikembalikan); ?></h3>
                        <p class="mb-0">Belum Dikembalikan</p>
                    </div>
                    <i class="fas fa-exclamation-circle fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo e($sudahDikembalikan); ?></h3>
                        <p class="mb-0">Sudah Dikembalikan</p>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo e($terlambat); ?></h3>
                        <p class="mb-0">Terlambat</p>
                    </div>
                    <i class="fas fa-clock fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-12 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>Rp <?php echo e(number_format($totalDenda ?? 0, 0, ',', '.')); ?></h3>
                        <p class="mb-0">Total Denda</p>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x text-orange"></i>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('peminjam.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Peminjaman_alatNEW\resources\views/peminjam/dashboard.blade.php ENDPATH**/ ?>