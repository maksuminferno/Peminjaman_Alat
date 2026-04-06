<?php $__env->startSection('title', 'Log Aktivitas - Sistem Peminjaman Alat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-clipboard-list text-orange me-2"></i>Log Aktivitas</h2>
                <p class="text-muted mb-0">Catatan aktivitas sistem</p>
            </div>
        </div>
    </div>

    <!-- Log Aktivitas Table -->
    <div class="card card-custom table-custom">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-orange"></i>Daftar Aktivitas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="logAktivitasTable">
                    <thead>
                        <tr>
                            <th width="30%">Nama User</th>
                            <th width="40%">Aktivitas</th>
                            <th width="30%">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($logAktivitas) && $logAktivitas->count() > 0): ?>
                            <?php $__currentLoopData = $logAktivitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($log->user->nama ?? 'N/A'); ?></td>
                                <td><?php echo e($log->aktivitas); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($log->waktu)->format('d M Y H:i:s')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada data log aktivitas</p>
                                    <small class="text-muted">Log akan tercatat ketika ada aktivitas pengguna</small>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    setTimeout(function() {
        if ($('#logAktivitasTable').length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#logAktivitasTable')) {
                try {
                    $('#logAktivitasTable').DataTable({
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
                    console.log('✓ logAktivitasTable DataTable initialized');
                } catch (e) {
                    console.error('Error:', e);
                }
            }
        }
    }, 300);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Peminjaman_alatNEW\resources\views/admin/log_aktivitas.blade.php ENDPATH**/ ?>