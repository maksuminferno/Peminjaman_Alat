<?php $__env->startSection('title', 'Daftar Peminjaman - Sistem Peminjaman Alat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-history text-orange me-2"></i>Daftar Peminjaman</h2>
                <p class="text-muted mb-0">Semua data peminjaman alat</p>
            </div>
        </div>
    </div>


    <!-- Peminjaman Table -->
    <div class="card card-custom table-custom">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-orange"></i>Daftar Peminjaman</h5>
          
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="adminPeminjamanTable">
                    <thead>
                        <tr>
                            <th>Nama User</th>
                            <th>Nama Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Disetujui Oleh</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $peminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $peminjamanItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php $__currentLoopData = $peminjamanItem->detailPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($peminjamanItem->user->nama ?? 'N/A'); ?></td>
                                <td><?php echo e($detail->alat->nama_alat); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($peminjamanItem->tanggal_pinjam)->format('d M Y')); ?></td>
                                <td>
                                    <?php if($peminjamanItem->disetujui_oleh): ?>
                                        <span class="text-orange">
                                            <i class="fas fa-user-check me-1"></i><?php echo e($peminjamanItem->petugas->nama ?? 'N/A'); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge
                                        <?php if($peminjamanItem->status == 'dipinjam'): ?> status-dipinjam
                                        <?php elseif($peminjamanItem->status == 'dikembalikan'): ?> status-dikembalikan
                                        <?php elseif($peminjamanItem->status == 'terlambat'): ?> status-belum_dikembalikan
                                        <?php elseif($peminjamanItem->status == 'menunggu persetujuan'): ?> status-belum_dikembalikan
                                        <?php endif; ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $peminjamanItem->status))); ?>

                                    </span>
                                </td>
                                <td>
                                    <form action="<?php echo e(route('admin.deletePeminjaman', ['id' => $peminjamanItem->id_peminjaman])); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peminjaman ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="text-center">Tidak ada data peminjaman</td>
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
        if ($('#adminPeminjamanTable').length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#adminPeminjamanTable')) {
                try {
                    $('#adminPeminjamanTable').DataTable({
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
                    console.log('✓ adminPeminjamanTable DataTable initialized');
                } catch (e) {
                    console.error('Error:', e);
                }
            }
        }
    }, 300);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Peminjaman_alatNEW\resources\views/admin/peminjaman.blade.php ENDPATH**/ ?>