<?php $__env->startSection('title', 'Pengajuan Peminjaman - Sistem Peminjaman Alat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-clipboard-list text-orange me-2"></i>Manajemen Peminjaman</h2>
                <p class="text-muted mb-0">Kelola pengajuan dan alat yang sedang dipinjam</p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-4">
        <div class="nav-tabs-custom">
            <a href="javascript:void(0)" class="nav-tab active" id="tabPengajuan" onclick="showTable('pengajuan')">
                <i class="fas fa-history me-2"></i>Daftar Pengajuan Peminjaman
            </a>
            <a href="javascript:void(0)" class="nav-tab" id="tabDipinjam" onclick="showTable('dipinjam')">
                <i class="fas fa-hand-holding me-2"></i>Alat Sedang Dipinjam
            </a>
        </div>
    </div>

    <!-- Pengajuan Peminjaman Table -->
    <div class="card card-custom table-custom" id="cardPengajuan">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-orange"></i>Daftar Pengajuan Peminjaman</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="peminjamanTable">
                    <thead>
                        <tr>
                            <th>ID Peminjaman</th>
                            <th>Nama Peminjam</th>
                            <th>Kode Barang</th>
                            <th>Nama Alat</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali Rencana</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $peminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $peminjamanItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr id="row-<?php echo e($peminjamanItem->id_peminjaman); ?>">
                            <td><?php echo e(sprintf('PJN%03d', $peminjamanItem->id_peminjaman)); ?></td>
                            <td><?php echo e($peminjamanItem->user->nama ?? 'N/A'); ?></td>
                            <td>
                                <?php $__currentLoopData = $peminjamanItem->detailPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($detail->kode_barang ?? '-'); ?>

                                    <?php if(!$loop->last): ?>, <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td>
                                <?php $__currentLoopData = $peminjamanItem->detailPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($detail->alat->nama_alat); ?>

                                    <?php if(!$loop->last): ?>, <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td>
                                <?php $__currentLoopData = $peminjamanItem->detailPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($detail->jumlah); ?>

                                    <?php if(!$loop->last): ?>, <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td><?php echo e(\Carbon\Carbon::parse($peminjamanItem->tanggal_pinjam)->format('d M Y')); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($peminjamanItem->tanggal_kembali_rencana)->format('d M Y')); ?></td>
                            <td>
                                <span class="status-badge
                                    <?php if($peminjamanItem->status == 'dipinjam'): ?> status-dipinjam
                                    <?php elseif($peminjamanItem->status == 'dikembalikan'): ?> status-dikembalikan
                                    <?php elseif($peminjamanItem->status == 'terlambat'): ?> status-belum_dikembalikan
                                    <?php elseif($peminjamanItem->status == 'menunggu persetujuan'): ?> status-belum_dikembalikan
                                    <?php elseif($peminjamanItem->status == 'ditolak'): ?> status-ditolak
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $peminjamanItem->status))); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($peminjamanItem->status == 'menunggu persetujuan'): ?>
                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#approveModal"
                                        data-id="<?php echo e($peminjamanItem->id_peminjaman); ?>"
                                        data-nama="<?php echo e($peminjamanItem->user->nama); ?>"
                                        data-alat="<?php $__currentLoopData = $peminjamanItem->detailPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($detail->alat->nama_alat); ?> (<?php echo e($detail->jumlah); ?>)<?php if(!$loop->last): ?>, <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>">
                                        <i class="fas fa-check me-1"></i>Setujui
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal"
                                        data-id="<?php echo e($peminjamanItem->id_peminjaman); ?>"
                                        data-nama="<?php echo e($peminjamanItem->user->nama); ?>"
                                        data-alat="<?php $__currentLoopData = $peminjamanItem->detailPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($detail->alat->nama_alat); ?> (<?php echo e($detail->jumlah); ?>)<?php if(!$loop->last): ?>, <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>">
                                        <i class="fas fa-times me-1"></i>Tolak
                                    </button>
                                <?php elseif($peminjamanItem->status == 'ditolak'): ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="fas fa-times-circle me-1"></i>Ditolak
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="text-center">Tidak ada data pengajuan peminjaman</td>
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

    <!-- Alat Sedang Dipinjam Table -->
    <div class="card card-custom table-custom" id="cardDipinjam" style="display: none;">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-hand-holding me-2 text-orange"></i>Alat Sedang Dipinjam</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dipinjamTable">
                    <thead>
                        <tr>
                            <th>ID Peminjaman</th>
                            <th>Nama Peminjam</th>
                            <th>Kode Barang</th>
                            <th>Nama Alat</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali Rencana</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $peminjamanAktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $peminjamanItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr id="row-dipinjam-<?php echo e($peminjamanItem->id_peminjaman); ?>">
                            <td><?php echo e(sprintf('PJN%03d', $peminjamanItem->id_peminjaman)); ?></td>
                            <td><?php echo e($peminjamanItem->user->nama ?? 'N/A'); ?></td>
                            <td>
                                <?php $__currentLoopData = $peminjamanItem->detailPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($detail->kode_barang ?? '-'); ?>

                                    <?php if(!$loop->last): ?>, <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td>
                                <?php $__currentLoopData = $peminjamanItem->detailPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($detail->alat->nama_alat); ?>

                                    <?php if(!$loop->last): ?>, <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td>
                                <?php $__currentLoopData = $peminjamanItem->detailPeminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($detail->jumlah); ?>

                                    <?php if(!$loop->last): ?>, <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td><?php echo e(\Carbon\Carbon::parse($peminjamanItem->tanggal_pinjam)->format('d M Y')); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($peminjamanItem->tanggal_kembali_rencana)->format('d M Y')); ?></td>
                            <td>
                                <span class="status-badge
                                    <?php if($peminjamanItem->status == 'dipinjam'): ?> status-dipinjam
                                    <?php elseif($peminjamanItem->status == 'terlambat'): ?> status-belum_dikembalikan
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $peminjamanItem->status))); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="text-center">Tidak ada alat yang sedang dipinjam</td>
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

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel"><i class="fas fa-check-circle text-success me-2"></i>Konfirmasi Persetujuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian!</strong><br>
                        Apakah Anda yakin ingin menyetujui peminjaman ini?
                    </div>
                    <form id="approveForm" method="POST" action="#">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="peminjamanId" name="peminjaman_id">

                        <div class="mb-3">
                            <label class="form-label">Peminjam</label>
                            <p class="fw-bold" id="peminjamName">-</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alat yang Dipinjam</label>
                            <p class="fw-bold" id="alatList">-</p>
                        </div>

                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            <small>Dengan menyetujui, status peminjaman akan berubah menjadi <strong>"Dipinjam"</strong></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="approveBtn" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Ya, Setujui
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel"><i class="fas fa-times-circle text-danger me-2"></i>Tolak Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="rejectForm" method="POST" action="#">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="rejectPeminjamanId" name="peminjaman_id">

                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Perhatian!</strong><br>
                            Apakah Anda yakin ingin menolak peminjaman ini?
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Peminjam</label>
                            <p class="fw-bold" id="rejectPeminjamName">-</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alat yang Dipinjam</label>
                            <p class="fw-bold" id="rejectAlatList">-</p>
                        </div>

                        <div class="mb-3">
                            <label for="alasan_ditolak" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alasan_ditolak" name="alasan_ditolak" rows="4" 
                                      placeholder="Jelaskan alasan penolakan peminjaman ini..." required></textarea>
                            <div class="form-text">Alasan ini akan ditampilkan kepada peminjam</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="rejectBtn" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Ya, Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.nav-tabs-custom {
    display: inline-flex;
    border-radius: 8px;
    gap: 8px;
}

.nav-tab {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    text-decoration: none;
    color: #6c757d;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.2s ease;
    background: transparent;
    border: none;
    cursor: pointer;
    border: 1px solid transparent;
}

.nav-tab:hover {
    color: #fd7e14;
}

.nav-tab.active {
    color: #fd7e14;
    border-bottom: 2px solid #fd7e14;
    border-radius: 0;
    background: transparent;
}

.nav-tab i {
    font-size: 14px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Global function to show/hide tables
    window.showTable = function(tableType) {
        const cardPengajuan = document.getElementById('cardPengajuan');
        const cardDipinjam = document.getElementById('cardDipinjam');
        const tabPengajuan = document.getElementById('tabPengajuan');
        const tabDipinjam = document.getElementById('tabDipinjam');

        if (tableType === 'pengajuan') {
            cardPengajuan.style.display = 'block';
            cardDipinjam.style.display = 'none';
            tabPengajuan.classList.add('active');
            tabDipinjam.classList.remove('active');
        } else if (tableType === 'dipinjam') {
            cardPengajuan.style.display = 'none';
            cardDipinjam.style.display = 'block';
            tabPengajuan.classList.remove('active');
            tabDipinjam.classList.add('active');
        }
    };

    // Show pengajuan table by default
    showTable('pengajuan');

    // Handle approve modal
    var approveModal = document.getElementById('approveModal');
    if (approveModal) {
        approveModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var nama = button.getAttribute('data-nama');
            var alat = button.getAttribute('data-alat');

            document.getElementById('peminjamanId').value = id;
            document.getElementById('peminjamName').textContent = nama;
            document.getElementById('alatList').textContent = alat;
            document.getElementById('approveForm').action = '/petugas/peminjaman/' + id + '/approve';
        });
    }

    // Handle reject modal
    var rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var nama = button.getAttribute('data-nama');
            var alat = button.getAttribute('data-alat');

            document.getElementById('rejectPeminjamanId').value = id;
            document.getElementById('rejectPeminjamName').textContent = nama;
            document.getElementById('rejectAlatList').textContent = alat;
            document.getElementById('rejectForm').action = '/petugas/peminjaman/' + id + '/reject';
        });
    }

    // Handle approve button click
    var approveBtn = document.getElementById('approveBtn');
    if (approveBtn) {
        approveBtn.addEventListener('click', function() {
            console.log('Approve button clicked');
            
            const peminjamanId = document.getElementById('peminjamanId').value;
            console.log('Peminjaman ID:', peminjamanId);
            
            if (!peminjamanId) {
                alert('Error: ID Peminjaman tidak ditemukan');
                return;
            }

            const approveForm = document.getElementById('approveForm');
            approveForm.action = '/petugas/peminjaman/' + peminjamanId + '/approve';

            const formData = new FormData(approveForm);
            const actionUrl = approveForm.action;
            const modal = document.getElementById('approveModal');
            const btn = this;

            console.log('Sending request to:', actionUrl);

            // Disable button while processing
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';

            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Close modal
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) {
                        bsModal.hide();
                    }

                    // Remove the row with animation
                    const row = document.getElementById('row-' + peminjamanId);
                    if (row) {
                        row.style.transition = 'opacity 0.3s ease';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            // Check if table is empty
                            const tbody = document.querySelector('#peminjamanTable tbody');
                            if (tbody.querySelectorAll('tr').length === 0) {
                                tbody.innerHTML = '<tr><td colspan="9" class="text-center">Tidak ada pengajuan peminjaman</td></tr>';
                            }
                        }, 300);
                    }

                    // Show success message
                    alert('Peminjaman berhasil disetujui!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Terjadi kesalahan saat memproses permintaan: ' + error.message);
            })
            .finally(() => {
                // Re-enable button
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check me-1"></i>Ya, Setujui';
            });
        });
    }

    // Handle reject button click
    var rejectBtn = document.getElementById('rejectBtn');
    if (rejectBtn) {
        rejectBtn.addEventListener('click', function() {
            const peminjamanId = document.getElementById('rejectPeminjamanId').value;
            if (!peminjamanId) {
                alert('Error: ID Peminjaman tidak ditemukan');
                return;
            }

            const rejectForm = document.getElementById('rejectForm');
            rejectForm.action = '/petugas/peminjaman/' + peminjamanId + '/reject';

            const formData = new FormData(rejectForm);
            const actionUrl = rejectForm.action;
            const modal = document.getElementById('rejectModal');
            const btn = this;

            // Disable button while processing
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';

            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    bsModal.hide();

                    // Update row status to "Ditolak"
                    const row = document.getElementById('row-' + peminjamanId);
                    if (row) {
                        const aksiCell = row.querySelector('td:last-child');
                        if (aksiCell) {
                            aksiCell.innerHTML = '<button type="button" class="btn btn-sm btn-outline-secondary" disabled><i class="fas fa-times-circle me-1"></i>Ditolak</button>';
                        }

                        // Update status badge
                        const statusCell = row.querySelectorAll('td')[7];
                        if (statusCell) {
                            statusCell.innerHTML = '<span class="status-badge status-ditolak">Ditolak</span>';
                        }
                    }

                    // Show success message
                    alert('Peminjaman berhasil ditolak!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses permintaan');
            })
            .finally(() => {
                // Re-enable button
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-times me-1"></i>Ya, Tolak';
            });
        });
    }

    console.log('[View] All event handlers attached.');
});
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// Initialize DataTables after jQuery and DataTables libraries are loaded
$(document).ready(function() {
    setTimeout(function() {
        // Initialize peminjamanTable
        if ($('#peminjamanTable').length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#peminjamanTable')) {
                try {
                    $('#peminjamanTable').DataTable({
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
                    console.log('✓ peminjamanTable DataTable initialized');
                } catch (e) {
                    console.error('Error initializing peminjamanTable:', e);
                }
            }
        }

        // Initialize dipinjamTable
        if ($('#dipinjamTable').length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#dipinjamTable')) {
                try {
                    $('#dipinjamTable').DataTable({
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
                    console.log('✓ dipinjamTable DataTable initialized');
                } catch (e) {
                    console.error('Error initializing dipinjamTable:', e);
                }
            }
        }
    }, 300);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('petugas.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Peminjaman_alatNEW\resources\views/petugas/peminjaman.blade.php ENDPATH**/ ?>