<?php $__env->startSection('title', 'Pengembalian Alat - Sistem Peminjaman Alat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-calendar-check text-orange me-2"></i>Riwayat Pengembalian</h2>
                <p class="text-muted mb-0">Daftar riwayat pengembalian alat</p>
            </div>
        </div>
    </div>

    <!-- Riwayat Pengembalian Table -->
    <div class="card card-custom table-custom">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-history me-2 text-orange"></i>Riwayat Pengembalian</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="petugasPengembalianTable">
                    <thead>
                        <tr>
                            <th>ID Pengembalian</th>
                            <th>ID Peminjaman</th>
                            <th>Nama Peminjam</th>
                            <th>Nama Alat</th>
                            <th>Tanggal Kembali</th>
                            <th>Kondisi</th>
                            <th>Bukti Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pengembalianList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengembalianItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e(sprintf('PGB%03d', $pengembalianItem->id_pengembalian)); ?></td>
                            <td><?php echo e(sprintf('PJN%03d', $pengembalianItem->peminjaman->id_peminjaman)); ?></td>
                            <td><?php echo e($pengembalianItem->peminjaman->user->nama ?? 'N/A'); ?></td>
                            <td>
                                <?php $__currentLoopData = $pengembalianItem->peminjaman->detailPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($detail->alat->nama_alat); ?>

                                    <?php if(!$loop->last): ?>, <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td><?php echo e(\Carbon\Carbon::parse($pengembalianItem->tanggal_kembali)->format('d M Y')); ?></td>
                            <td>
                                <span class="status-badge
                                    <?php if($pengembalianItem->kondisi_alat == 'baik'): ?> status-dikembalikan
                                    <?php else: ?> status-belum_dikembalikan
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($pengembalianItem->kondisi_alat)); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($pengembalianItem->bukti_foto): ?>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#fotoBuktiModal" data-foto="<?php echo e(asset($pengembalianItem->bukti_foto)); ?>" data-alat="<?php echo e(collect($pengembalianItem->peminjaman->detailPeminjaman)->pluck('alat.nama_alat')->join(', ')); ?>">
                                        <i class="fas fa-image me-1"></i>Lihat Foto
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted"><i class="fas fa-camera-slash me-1"></i>Tidak ada foto</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="<?php echo e(route('petugas.deletePengembalian', ['id' => $pengembalianItem->id_pengembalian])); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengembalian ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center">Belum ada riwayat pengembalian</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Foto Bukti Modal -->
    <div class="modal fade" id="fotoBuktiModal" tabindex="-1" aria-labelledby="fotoBuktiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fotoBuktiModalLabel"><i class="fas fa-image text-primary me-2"></i>Bukti Foto Pengembalian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <label class="form-label text-start d-block fw-bold">Nama Alat</label>
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-tools me-2"></i><span id="fotoBuktiAlat"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-start d-block fw-bold">Foto Bukti Pengembalian</label>
                        <div class="border rounded p-2 bg-light">
                            <img id="fotoBuktiImage" src="" alt="Bukti Foto Pengembalian" class="img-fluid" style="max-width: 100%; max-height: 500px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="downloadFoto" href="#" class="btn btn-outline-primary" download>
                        <i class="fas fa-download me-1"></i>Download Foto
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle foto bukti modal
    var fotoModal = document.getElementById('fotoBuktiModal');
    fotoModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var fotoUrl = button.getAttribute('data-foto');
        var alatName = button.getAttribute('data-alat');

        // Update modal content
        document.getElementById('fotoBuktiImage').src = fotoUrl;
        document.getElementById('fotoBuktiAlat').textContent = alatName || 'N/A';
        document.getElementById('downloadFoto').href = fotoUrl;
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    setTimeout(function() {
        if ($('#petugasPengembalianTable').length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#petugasPengembalianTable')) {
                try {
                    $('#petugasPengembalianTable').DataTable({
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
                    console.log('✓ petugasPengembalianTable DataTable initialized');
                } catch (e) {
                    console.error('Error:', e);
                }
            }
        }
    }, 300);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('petugas.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Peminjaman_alatNEW\resources\views/petugas/pengembalian.blade.php ENDPATH**/ ?>