<?php $__env->startSection('title', 'Riwayat Peminjaman - Sistem Peminjaman Alat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-history text-orange me-2"></i>Riwayat Peminjaman</h2>
                <p class="text-muted mb-0">Daftar lengkap peminjaman yang telah Anda lakukan</p>
            </div>
        </div>
    </div>

    <br>

 

    <br>
    
    <!-- Borrowing History Table -->
    <div class="card card-custom table-custom">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-orange"></i>Data Peminjaman</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>ID Peminjaman</th>
                            <th>Nama Alat</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali Rencana</th>
                            <th>Tanggal Kembali Real</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $peminjamanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $peminjaman): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e(sprintf('#PJN%03d', $peminjaman->id_peminjaman)); ?></td>
                            <td>
                                <?php $__currentLoopData = $peminjaman->detailPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($detail->alat->nama_alat); ?>

                                    <?php if(!$loop->last): ?>, <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td>
                                <?php $__currentLoopData = $peminjaman->detailPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($detail->jumlah); ?>

                                    <?php if(!$loop->last): ?>, <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td><?php echo e(\Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y')); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d M Y')); ?></td>
                            <td>
                                <?php if($peminjaman->pengembalian): ?>
                                    <?php echo e(\Carbon\Carbon::parse($peminjaman->pengembalian->tanggal_kembali)->format('d M Y')); ?>

                                <?php elseif($peminjaman->status == 'ditolak'): ?>
                                    <span class="text-muted">-</span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge
                                    <?php if($peminjaman->status == 'dipinjam'): ?> status-dipinjam
                                    <?php elseif($peminjaman->status == 'dikembalikan'): ?> status-dikembalikan
                                    <?php elseif($peminjaman->status == 'terlambat'): ?> status-belum_dikembalikan
                                    <?php elseif($peminjaman->status == 'menunggu persetujuan'): ?> status-belum_dikembalikan
                                    <?php elseif($peminjaman->status == 'ditolak'): ?> status-ditolak
                                    <?php endif; ?>">
                                    <?php if($peminjaman->status == 'menunggu persetujuan'): ?>
                                        Menunggu Persetujuan
                                    <?php elseif($peminjaman->status == 'dipinjam'): ?>
                                        Dipinjam
                                    <?php elseif($peminjaman->status == 'dikembalikan'): ?>
                                        Dikembalikan
                                    <?php elseif($peminjaman->status == 'ditolak'): ?>
                                        Ditolak
                                    <?php else: ?>
                                        <?php echo e(ucfirst(str_replace('_', ' ', $peminjaman->status))); ?>

                                    <?php endif; ?>
                                </span>
                                <?php if($peminjaman->status == 'ditolak' && $peminjaman->alasan_ditolak): ?>
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-1" onclick="showAlasanDitolak('<?php echo e(addslashes($peminjaman->alasan_ditolak)); ?>', '<?php echo e(sprintf('#PJN%03d', $peminjaman->id_peminjaman)); ?>')" data-bs-toggle="modal" data-bs-target="#alasanDitolakModal">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($peminjaman->pengembalian): ?>
                                    Rp <?php echo e(number_format($peminjaman->pengembalian->denda, 0, ',', '.')); ?>

                                <?php else: ?>
                                    Rp 0
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data peminjaman</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <?php echo e($peminjamanList->links()); ?>

                </nav>
            </div>
        </div>
    </div>

    <!-- Alasan Ditolak Modal -->
    <div class="modal fade" id="alasanDitolakModal" tabindex="-1" aria-labelledby="alasanDitolakModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="alasanDitolakModalLabel">
                        <i class="fas fa-times-circle me-2"></i>Alasan Penolakan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger border-0 mb-0">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle fa-2x me-3 mt-1"></i>
                            <div>
                                <h6 class="mb-2"><strong>Peminjaman:</strong> <span id="alasanPeminjamanId">-</span></h6>
                                <hr class="my-2">
                                <h6 class="mb-2"><strong>Alasan Penolakan:</strong></h6>
                                <p class="mb-0" id="alasanDitolakText" style="white-space: pre-wrap;"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    function showAlasanDitolak(alasan, peminjamanId) {
        document.getElementById('alasanPeminjamanId').textContent = peminjamanId;
        document.getElementById('alasanDitolakText').textContent = alasan;
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('peminjam.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Peminjaman_alatNEW\resources\views/peminjam/history.blade.php ENDPATH**/ ?>