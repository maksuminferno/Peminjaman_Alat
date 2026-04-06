<?php $__env->startSection('title', 'Ajukan Peminjaman Alat - Sistem Peminjaman Alat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-hand-point-right text-orange me-2"></i>Ajukan Peminjaman Alat</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Borrowing Form Column -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(isset($alat) ? route('peminjam.storeBorrow') : route('peminjam.storeBorrowMultiple')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php if(session('success')): ?>
                            <div class="alert alert-success">
                                <?php echo e(session('success')); ?>

                            </div>
                        <?php endif; ?>

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Multiple Alat Selection (from tools page) -->
                        <?php if(isset($groupedAlatList) && $groupedAlatList->count() > 0): ?>
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-shopping-cart text-orange me-2"></i>Alat yang Dipilih</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Alat</th>
                                            <th>Kategori</th>
                                            <th>Stok Tersedia</th>
                                            <th>Pilih Kode Barang</th>
                                            <th>Lokasi</th>
                                            <th>Jumlah Pinjam</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $groupedAlatList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $namaAlat => $tools): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $totalStock = $tools->sum('stok');
                                                $firstTool = $tools->first();
                                                $toolIds = $tools->pluck('id_alat')->toArray();
                                            ?>
                                        <tr>
                                            <td><?php echo e($namaAlat); ?></td>
                                            <td><?php echo e($firstTool->kategori->nama_kategori); ?></td>
                                            <td><?php echo e($totalStock); ?></td>
                                            <td>
                                                <select class="form-select kode_barang_select" name="kode_barang[<?php echo e($firstTool->id_alat); ?>]" data-index="<?php echo e($firstTool->id_alat); ?>" required>
                                                    <option value="">-- Pilih Kode Barang --</option>
                                                    <?php $__currentLoopData = $tools->alatTersedia ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alatItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($alatItem->kode_barang); ?>"
                                                            data-lokasi="<?php echo e($alatItem->lokasi); ?>"
                                                            data-stok="<?php echo e($alatItem->stok); ?>">
                                                        <?php echo e($alatItem->kode_barang); ?> - <?php echo e($alatItem->lokasi); ?> (Stok: <?php echo e($alatItem->stok); ?>, Kondisi: <?php echo e($alatItem->kondisi ?? 'N/A'); ?>)
                                                    </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <span class="lokasi_display_<?php echo e($firstTool->id_alat); ?> text-muted">-</span>
                                            </td>
                                            <td>
                                                <input type="number" name="alat[<?php echo e($firstTool->id_alat); ?>]"
                                                    class="form-control" min="1" max="<?php echo e($totalStock); ?>" value="1"
                                                    style="width: 100px;" required>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Single Alat (from "Pinjam" button) -->
                        <?php if(isset($alat)): ?>
                        <div class="mb-3">
                            <label for="nama_alat" class="form-label">Nama Alat</label>
                            <input type="text" class="form-control" value="<?php echo e($alat->nama_alat); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" class="form-control" value="<?php echo e($alat->kategori->nama_kategori); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="kode_barang" class="form-label">Pilih Kode Barang</label>
                            <select class="form-select" id="kode_barang" name="kode_barang" required>
                                <option value="">-- Pilih Kode Barang --</option>
                                <?php $__currentLoopData = $alat->alatTersedia ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alatItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($alatItem->kode_barang); ?>"
                                        data-lokasi="<?php echo e($alatItem->lokasi); ?>"
                                        data-stok="<?php echo e($alatItem->stok); ?>">
                                    <?php echo e($alatItem->kode_barang); ?> - <?php echo e($alatItem->lokasi); ?> (Stok: <?php echo e($alatItem->stok); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi_display" readonly placeholder="Pilih kode barang untuk melihat lokasi">
                        </div>

                        <input type="hidden" name="alat[<?php echo e($alat->id_alat); ?>]" value="1">
                        <?php endif; ?>

                        <!-- Hidden field for tanggal_pinjam (will be set automatically to today) -->
                        <input type="hidden" name="tanggal_pinjam" value="<?php echo e(date('Y-m-d')); ?>">

                        <!-- User selects expected return date -->
                        <div class="mb-3">
                            <label for="tanggal_kembali_rencana" class="form-label">Tanggal Kembali Rencana</label>
                            <input type="date" name="tanggal_kembali_rencana" class="form-control" id="tanggal_kembali_rencana" required>
                            <div class="form-text">Pilih tanggal rencana pengembalian alat (minimal 1 hari dari hari ini)</div>
                        </div>

                        <!-- Alasan Peminjaman -->
                        <div class="mb-3">
                            <label for="alasan" class="form-label">Alasan Peminjaman <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" 
                                      placeholder="Jelaskan alasan Anda meminjam alat ini (contoh: Untuk keperluan praktikum, penelitian, dll.)" required></textarea>
                            <div class="form-text">Mohon jelaskan alasan peminjaman secara detail dan jelas</div>
                        </div>

                        <!-- Hidden fields untuk data yang diisi otomatis sistem -->
                        <input type="hidden" name="id_user" value="<?php echo e(Auth::id()); ?>">

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo e(route('peminjam.tools')); ?>" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-orange">Ajukan Peminjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- History Column -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history me-2 text-orange"></i>Riwayat Terbaru</h5>
                </div>
                <div class="card-body">
                    <?php
                        $user = Auth::user();
                        $userId = $user ? $user->id_user : null;
                        $recentPeminjaman = \App\Models\Peminjaman::with(['detailPeminjaman.alat', 'pengembalian'])
                            ->where('id_user', $userId)
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                        
                        // Debug info - uncomment for troubleshooting
                        // dump('Current User ID: ' . $userId);
                        // dump('Number of peminjaman found: ' . $recentPeminjaman->count());
                        // foreach($recentPeminjaman as $p) {
                        //     dump('Peminjaman ID: ' . $p->id_peminjaman . ', Status: ' . $p->status);
                        // }
                        
                        // Temporary debug - remove after testing
                        $allPeminjaman = \App\Models\Peminjaman::where('id_user', $userId)->get();
                        // dump('All peminjaman count: ' . $allPeminjaman->count());
                        // foreach($allPeminjaman as $p) {
                        //     dump('Peminjaman ID: ' . $p->id_peminjaman . ', Status: ' . $p->status . ', Created: ' . $p->created_at);
                        // }
                    ?>

                
                    </div>

                    <?php if($recentPeminjaman->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Alat</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $recentPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $peminjaman): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $__currentLoopData = $peminjaman->detailPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="small"><?php echo e(Str::limit($detail->alat->nama_alat, 20)); ?></td>
                                            <td>
                                                <span class="status-badge
                                                    <?php if($peminjaman->status == 'dipinjam'): ?> status-dipinjam
                                                    <?php elseif($peminjaman->status == 'dikembalikan'): ?> status-dikembalikan
                                                    <?php elseif($peminjaman->status == 'terlambat'): ?> status-belum_dikembalikan
                                                    <?php elseif($peminjaman->status == 'menunggu persetujuan'): ?> status-belum_dikembalikan
                                                    <?php elseif($peminjaman->status == 'ditolak'): ?> status-ditolak
                                                    <?php endif; ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $peminjaman->status))); ?>

                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-3">
                            <a href="<?php echo e(route('peminjam.history')); ?>" class="btn btn-outline-orange btn-sm">Lihat Semua</a>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Belum ada riwayat peminjaman</p>
                            <small class="text-muted">User ID: <?php echo e(Auth::id()); ?></small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize return date
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date for return date (tomorrow)
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const minReturnDate = tomorrow.toISOString().split('T')[0];
        document.getElementById('tanggal_kembali_rencana').min = minReturnDate;

        // Handle single alat kode_barang change
        const kodeBarangSelect = document.getElementById('kode_barang');
        const lokasiDisplay = document.getElementById('lokasi_display');
        
        if (kodeBarangSelect && lokasiDisplay) {
            kodeBarangSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const lokasi = selectedOption.getAttribute('data-lokasi');
                lokasiDisplay.value = lokasi || '-';
            });
        }

        // Handle multiple alat kode_barang change
        document.querySelectorAll('.kode_barang_select').forEach(function(select) {
            select.addEventListener('change', function() {
                const index = this.getAttribute('data-index');
                const selectedOption = this.options[this.selectedIndex];
                const lokasi = selectedOption.getAttribute('data-lokasi');
                const lokasiDisplay = document.querySelector('.lokasi_display_' + index);
                if (lokasiDisplay) {
                    lokasiDisplay.textContent = lokasi || '-';
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('peminjam.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Peminjaman_alatNEW\resources\views/peminjam/ajukan_peminjaman.blade.php ENDPATH**/ ?>