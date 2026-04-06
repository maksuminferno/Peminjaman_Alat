<?php $__env->startSection('title', 'Laporan - Sistem Peminjaman Alat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-file-alt text-orange me-2"></i>Laporan Pengembalian Alat</h2>
                <p class="text-muted mb-0">Daftar pengembalian alat yang telah dilakukan</p>
            </div>
            <div>
                <a href="<?php echo e(route('petugas.export-pengembalian')); ?>" class="btn btn-success">
                    <i class="fas fa-file-excel me-2"></i>Cetak Laporan ke Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Returns Table -->
    <div class="card card-custom table-custom">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-calendar-check me-2 text-orange"></i>Daftar Pengembalian</h5>
            <a href="<?php echo e(route('petugas.pengembalian')); ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="petugasLaporanTable">
                    <thead>
                        <tr>
                            <th>ID Pengembalian</th>
                            <th>ID Peminjaman</th>
                            <th>Nama Peminjam</th>
                            <th>Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali Rencana</th>
                            <th>Tanggal Kembali</th>
                            <th>Denda</th>
                            <th>Kondisi</th>
                            <th>Status Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentPengembalian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengembalian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e(sprintf('PGB%03d', $pengembalian->id_pengembalian)); ?></td>
                            <td><?php echo e(sprintf('PJN%03d', $pengembalian->peminjaman->id_peminjaman)); ?></td>
                            <td><?php echo e($pengembalian->peminjaman->user->nama ?? 'N/A'); ?></td>
                            <td>
                                <?php $__currentLoopData = $pengembalian->peminjaman->detailPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($detail->alat->nama_alat); ?>

                                    <?php if(!$loop->last): ?>, <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td><?php echo e(\Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_pinjam)->format('d M Y')); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_kembali_rencana)->format('d M Y')); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($pengembalian->tanggal_kembali)->format('d M Y')); ?></td>
                            <td>Rp <?php echo e(number_format($pengembalian->denda, 0, ',', '.')); ?></td>
                            <td>
                                <span class="status-badge
                                    <?php if($pengembalian->kondisi_alat == 'baik'): ?> status-dikembalikan
                                    <?php else: ?> status-belum_dikembalikan
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($pengembalian->kondisi_alat)); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($pengembalian->denda > 0): ?>
                                    <span class="status-badge status-belum_dikembalikan">
                                        <i class="fas fa-exclamation-circle me-1"></i>Ada Denda
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge status-dikembalikan">
                                        <i class="fas fa-check-circle me-1"></i>Tidak Ada Denda
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="text-center">Tidak ada data pengembalian</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
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
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    setTimeout(function() {
        if ($('#petugasLaporanTable').length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#petugasLaporanTable')) {
                try {
                    $('#petugasLaporanTable').DataTable({
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
                    console.log('✓ petugasLaporanTable DataTable initialized');
                } catch (e) {
                    console.error('Error:', e);
                }
            }
        }
    }, 300);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('petugas.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Peminjaman_alatNEW\resources\views/petugas/laporan.blade.php ENDPATH**/ ?>