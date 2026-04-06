<?php $__env->startSection('title', 'Dashboard Petugas - Sistem Peminjaman Alat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">

    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2>
                   <i class="fas fa-tachometer-alt text-orange me-2"></i>
                    Dashboard Petugas
                </h2>
                <p class="text-muted mb-0">
                    Ringkasan aktivitas sistem peminjaman alat
                </p>
            </div>
            <div class="text-end">
                <h5>
                    Halo, <?php echo e(Auth::user()->name ?? 'Petugas'); ?>!
                </h5>
                <small class="text-muted">
                    Terakhir login: <?php echo e(\Carbon\Carbon::now()->format('j F Y')); ?>

                </small>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row">
        <?php if($totalPengajuan > 0): ?>
        <div class="col-md-12 mb-4">
            <div class="card card-custom border-warning" style="border: 2px solid #ffc107;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-warning">
                                <i class="fas fa-bell me-2"></i>Pengajuan Peminjaman Tersedia!
                            </h4>
                            <p class="mb-0">
                                Terdapat <strong><?php echo e($totalPengajuan); ?> pengajuan peminjaman</strong> yang menunggu persetujuan Anda.
                            </p>
                        </div>
                        <div>
                            <a href="<?php echo e(route('petugas.peminjaman')); ?>" class="btn btn-warning">
                                <i class="fas fa-arrow-right me-2"></i>Lihat Pengajuan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

    
        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo e($totalAktif); ?></h3>
                        <p class="mb-0">Peminjaman Aktif</p>
                    </div>
                    <i class="fas fa-clock fa-2x text-orange"></i>
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

        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo e($totalPengajuan); ?></h3>
                        <p class="mb-0">Peminjaman Pending</p>
                    </div>
                    <i class="fas fa-hourglass-half fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo e($totalKeterlambatan); ?></h3>
                        <p class="mb-0">Keterlambatan</p>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>Rp <?php echo e(number_format($totalDenda, 0, ',', '.')); ?></h3>
                        <p class="mb-0">Total Denda</p>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x text-orange"></i>
                </div>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('petugas.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Peminjaman_alatNEW\resources\views/petugas/dashboard.blade.php ENDPATH**/ ?>