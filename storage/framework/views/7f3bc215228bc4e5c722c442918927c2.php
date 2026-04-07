<?php $__env->startSection('title', 'Data Alat - Sistem Peminjaman Alat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-boxes text-orange me-2"></i>Data Alat</h2>
                <p class="text-muted mb-0">Kelola peralatan sistem</p>
            </div>
            <div>
              
                
            </div>
        </div>
    </div>

    <!-- Equipment Table -->
    <div class="card card-custom table-custom">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-orange"></i>Daftar Pengguna</h5>
               <div class="mb-4">
  <button type="button" class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#addAlatModal">
                    <i class="fas fa-plus text-white me-2"></i>Tambah Alat
                </button>
    </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="alatTable">
                    <thead>
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Alat</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Stok</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $alat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($item->kode_barang ?? '-'); ?></td>
                            <td><?php echo e($item->nama_alat); ?></td>
                            <td><?php echo e($item->kategori->nama_kategori ?? 'N/A'); ?></td>
                            <td><?php echo e($item->lokasi ?? '-'); ?></td>
                            <td><?php echo e($item->stok); ?></td>
                            <td>
                                <span class="status-badge
                                    <?php if($item->kondisi == 'baik'): ?> status-dipinjam
                                    <?php else: ?> status-belum_dikembalikan
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($item->kondisi)); ?>

                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editAlat(<?php echo e($item->id_alat); ?>, '<?php echo e($item->nama_alat); ?>', <?php echo e($item->id_kategori); ?>, <?php echo e($item->stok); ?>, '<?php echo e($item->deskripsi); ?>', '<?php echo e($item->lokasi); ?>')" data-bs-toggle="modal" data-bs-target="#editAlatModal">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </button>
                                    <form action="<?php echo e(route('admin.deleteAlat', ['id' => $item->id_alat])); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alat ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="text-center">Tidak ada data alat</td>
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
    
    <!-- Add Equipment Modal -->
    <div class="modal fade" id="addAlatModal" tabindex="-1" aria-labelledby="addAlatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAlatModalLabel">Tambah Alat Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('admin.storeAlat')); ?>" method="POST" id="addAlatForm">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_alat" class="form-label">Nama Alat</label>
                                <input type="text" class="form-control" id="nama_alat" name="nama_alat" required list="alatSuggestions">
                                <datalist id="alatSuggestions">
                                    <!-- Options will be populated dynamically based on selected category -->
                                </datalist>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="id_kategori" class="form-label">Kategori</label>
                                <select class="form-select" id="id_kategori" name="id_kategori" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php $__currentLoopData = $kategoriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($kategori->id_kategori); ?>"><?php echo e($kategori->nama_kategori); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stok" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="stok" name="stok" min="1" value="1" required onchange="checkStockAndShowItems()">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Contoh: Rak A1">
                            </div>
                        </div>
                        
                        <!-- Kode Barang untuk stok = 1 -->
                        <div id="kodeBarangSingle" class="mb-3">
                            <label for="kode_barang" class="form-label">Kode Barang</label>
                            <input type="text" class="form-control" id="kode_barang" name="kode_barang" placeholder="Masukkan kode barang">
                            <div class="form-text">Kode unik untuk identifikasi alat ini</div>
                        </div>

                        <!-- Kode Barang Dasar untuk stok > 1 (auto-generate) -->
                        <div id="kodeBarangBase" class="mb-3" style="display: none;">
                            <label for="kode_barang_base" class="form-label">Kode Barang Dasar</label>
                            <input type="text" class="form-control" id="kode_barang_base" name="kode_barang_base" placeholder="Contoh: label-1">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Sistem akan otomatis membuat kode berurutan. Contoh: <code>label-1</code> → <code>label-2</code>, <code>label-3</code>, dst.
                            </div>
                            <div id="kodePreview" class="mt-2" style="display: none;">
                                <small class="text-muted">Preview kode yang akan dibuat:</small>
                                <div id="kodePreviewList" class="bg-light p-2 rounded" style="max-height: 100px; overflow-y: auto; font-size: 0.85rem;"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi alat"></textarea>
                        </div>

                        <!-- Lokasi untuk setiap item (stock > 1) -->
                        <div id="itemsSection" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-boxes me-2"></i>Daftar Item</h6>
                                </div>
                                <div class="card-body" id="itemsContainer">
                                    <!-- Dynamic items will be added here -->
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-orange">Simpan Alat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Equipment Modal -->
    <div class="modal fade" id="editAlatModal" tabindex="-1" aria-labelledby="editAlatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAlatModalLabel">Edit Alat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAlatForm" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_nama_alat" class="form-label">Nama Alat</label>
                                <input type="text" class="form-control" id="edit_nama_alat" name="nama_alat" required list="editAlatSuggestions">
                                <datalist id="editAlatSuggestions">
                                    <!-- Options will be populated dynamically based on selected category -->
                                </datalist>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_kode_barang" class="form-label">Kode Barang</label>
                                <input type="text" class="form-control" id="edit_kode_barang" name="kode_barang" placeholder="Masukkan kode barang">
                                <div class="form-text">Kode unik untuk identifikasi alat</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_id_kategori" class="form-label">Kategori</label>
                                <select class="form-select" id="edit_id_kategori" name="id_kategori" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php $__currentLoopData = $kategoriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($kategori->id_kategori); ?>"><?php echo e($kategori->nama_kategori); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_stok" class="form-label">Stok Saat Ini</label>
                                <input type="number" class="form-control" id="edit_stok_display" disabled readonly>
                                <input type="hidden" id="edit_stok" name="stok">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_lokasi" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="edit_lokasi" name="lokasi" placeholder="Contoh: Rak A1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tambah_stok" class="form-label">Tambah Stok</label>
                                <input type="number" class="form-control" id="tambah_stok" name="tambah_stok" min="0" value="0" placeholder="Opsional">
                                <small class="text-muted">Stok saat ini: <span id="current_stock_display"></span></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi alat"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="edit_kondisi" class="form-label">Kondisi</label>
                                <select class="form-select" id="edit_kondisi" name="kondisi" required>
                                    <option value="">-- Pilih Kondisi --</option>
                                    <option value="baik">Baik</option>
                                    <option value="sedang">Sedang</option>
                                    <option value="diperbaiki">Diperbaiki</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-orange">Perbarui Alat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Check stock and show/hide items section
    function checkStockAndShowItems() {
        const stok = parseInt(document.getElementById('stok').value) || 0;
        const itemsSection = document.getElementById('itemsSection');
        const kodeBarangSingle = document.getElementById('kodeBarangSingle');
        const kodeBarangBase = document.getElementById('kodeBarangBase');
        const itemsContainer = document.getElementById('itemsContainer');

        if (stok === 1) {
            // Show single code barang field
            itemsSection.style.display = 'none';
            kodeBarangSingle.style.display = 'block';
            kodeBarangBase.style.display = 'none';
            itemsContainer.innerHTML = '';
        } else if (stok > 1) {
            // Show base code field and location inputs
            kodeBarangSingle.style.display = 'none';
            kodeBarangBase.style.display = 'block';
            itemsSection.style.display = 'block';
            itemsContainer.innerHTML = '';

            for (let i = 0; i < stok; i++) {
                const itemHtml = `
                    <div class="mb-2">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <small class="text-muted fw-bold">Item #${i + 1}</small>
                            </div>
                            <div class="col-md-6">
                                <select class="form-select form-select-sm" name="items[${i}][kondisi]" required>
                                    <option value="">-- Kondisi --</option>
                                    <option value="baik" selected>Baik</option>
                                    <option value="diperbaiki">Diperbaiki</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                `;
                itemsContainer.innerHTML += itemHtml;
            }
        } else {
            itemsSection.style.display = 'none';
            kodeBarangSingle.style.display = 'block';
            kodeBarangBase.style.display = 'none';
            itemsContainer.innerHTML = '';
        }
    }

    // Generate sequential kode_barang and show preview
    document.addEventListener('DOMContentLoaded', function() {
        const kodeBarangBaseInput = document.getElementById('kode_barang_base');
        const stokInput = document.getElementById('stok');
        const kodePreview = document.getElementById('kodePreview');
        const kodePreviewList = document.getElementById('kodePreviewList');

        function generateKodePreview() {
            const baseKode = kodeBarangBaseInput.value.trim();
            const stok = parseInt(stokInput.value) || 0;

            if (!baseKode || stok <= 1) {
                kodePreview.style.display = 'none';
                return;
            }

            // Parse base code to extract prefix and number
            // Support patterns like: label-1, BRG001, item_001, etc.
            let generatedKodes = [];

            // Try to match pattern: text + separator + number
            const match = baseKode.match(/^(.*?)(\d+)$/);

            if (match) {
                const prefix = match[1];
                const startNum = parseInt(match[2]);
                const numLength = match[2].length; // Preserve zero-padding

                for (let i = 0; i < Math.min(stok, 10); i++) { // Show max 10 previews
                    const num = startNum + i;
                    const paddedNum = String(num).padStart(numLength, '0');
                    generatedKodes.push(`${prefix}${paddedNum}`);
                }

                if (stok > 10) {
                    generatedKodes.push(`... dan ${stok - 10} kode lainnya`);
                }
            } else {
                // If no number pattern, just append index
                for (let i = 0; i < Math.min(stok, 10); i++) {
                    generatedKodes.push(`${baseKode}-${i + 1}`);
                }

                if (stok > 10) {
                    generatedKodes.push(`... dan ${stok - 10} kode lainnya`);
                }
            }

            kodePreviewList.innerHTML = generatedKodes.map(k => `<div><code>${k}</code></div>`).join('');
            kodePreview.style.display = 'block';
        }

        if (kodeBarangBaseInput) {
            kodeBarangBaseInput.addEventListener('input', generateKodePreview);
        }

        if (stokInput) {
            stokInput.addEventListener('change', generateKodePreview);
        }
    });

    // Validate form before submit
    document.getElementById('addAlatForm').addEventListener('submit', function(e) {
        const stok = parseInt(document.getElementById('stok').value) || 0;
        let hasError = false;

        if (stok === 1) {
            // Validate single kode barang
            const kodeBarangInput = document.getElementById('kode_barang');
            if (kodeBarangInput && !kodeBarangInput.value.trim()) {
                hasError = true;
                kodeBarangInput.classList.add('is-invalid');
            } else if (kodeBarangInput) {
                kodeBarangInput.classList.remove('is-invalid');
            }
        } else if (stok > 1) {
            // Validate base kode barang
            const kodeBarangBaseInput = document.getElementById('kode_barang_base');
            if (kodeBarangBaseInput && !kodeBarangBaseInput.value.trim()) {
                hasError = true;
                kodeBarangBaseInput.classList.add('is-invalid');
            } else if (kodeBarangBaseInput) {
                kodeBarangBaseInput.classList.remove('is-invalid');
            }
        }

        if (hasError) {
            e.preventDefault();
            alert('Mohon isi kode barang!');
        }
    });

    function editAlat(id, nama_alat, id_kategori, stok, deskripsi, lokasi) {
        // Fetch the equipment details including condition
        fetch(`/admin/alat/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_nama_alat').value = data.nama_alat;
                document.getElementById('edit_kode_barang').value = data.kode_barang || '';
                document.getElementById('edit_id_kategori').value = data.id_kategori;
                document.getElementById('edit_stok_display').value = data.stok;
                document.getElementById('edit_stok').value = data.stok;
                document.getElementById('current_stock_display').textContent = data.stok;
                document.getElementById('tambah_stok').value = 0;
                document.getElementById('edit_kondisi').value = data.kondisi;
                document.getElementById('edit_deskripsi').value = data.deskripsi || '';
                document.getElementById('edit_lokasi').value = data.lokasi || '';
                document.getElementById('editAlatForm').action = '/admin/alat/' + id;

                // Load equipment suggestions for the selected category
                if (data.id_kategori) {
                    fetch(`/admin/alat-by-kategori/${data.id_kategori}`)
                        .then(response => response.json())
                        .then(suggestions => {
                            const datalist = document.getElementById('editAlatSuggestions');
                            if (datalist) {
                                // Clear existing options
                                datalist.innerHTML = '';

                                // Add new options based on fetched data
                                suggestions.forEach(alat => {
                                    const option = document.createElement('option');
                                    option.value = alat.nama_alat;
                                    option.textContent = alat.nama_alat;
                                    datalist.appendChild(option);
                                });
                            }
                        })
                        .catch(error => console.error('Error fetching equipment for edit form:', error));
                }
            })
            .catch(error => console.error('Error fetching equipment details:', error));
    }

    // Function to load equipment based on selected category for add form
    function setupCategoryEquipmentLoader(formType) {
        let kategoriSelect, namaAlatInput, datalist;
        
        if (formType === 'add') {
            kategoriSelect = document.getElementById('id_kategori');
            namaAlatInput = document.getElementById('nama_alat');
            datalist = document.getElementById('alatSuggestions');
        } else if (formType === 'edit') {
            kategoriSelect = document.getElementById('edit_id_kategori');
            namaAlatInput = document.getElementById('edit_nama_alat');
            datalist = document.getElementById('editAlatSuggestions');
        }
        
        if (kategoriSelect && namaAlatInput && datalist) {
            kategoriSelect.addEventListener('change', function() {
                const selectedCategoryId = this.value;
                
                if (selectedCategoryId) {
                    // Fetch equipment based on selected category
                    fetch(`/admin/alat-by-kategori/${selectedCategoryId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Clear existing options
                            datalist.innerHTML = '';
                            
                            // Add new options based on fetched data
                            data.forEach(alat => {
                                const option = document.createElement('option');
                                option.value = alat.nama_alat;
                                option.textContent = alat.nama_alat;
                                datalist.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching equipment:', error));
                } else {
                    // Clear the datalist if no category is selected
                    datalist.innerHTML = '';
                }
            });
        }
    }

    // Setup for both add and edit forms
    document.addEventListener('DOMContentLoaded', function() {
        setupCategoryEquipmentLoader('add');
        
        // Initialize single code barang field visibility
        checkStockAndShowItems();

        // Also setup for edit form when the modal is shown
        const editModal = document.getElementById('editAlatModal');
        if (editModal) {
            editModal.addEventListener('shown.bs.modal', function() {
                setupCategoryEquipmentLoader('edit');
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    setTimeout(function() {
        if ($('#alatTable').length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#alatTable')) {
                try {
                    $('#alatTable').DataTable({
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
                    console.log('✓ alatTable DataTable initialized');
                } catch (e) {
                    console.error('Error:', e);
                }
            }
        }
    }, 300);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Peminjaman_alatNEW\resources\views/admin/alat.blade.php ENDPATH**/ ?>