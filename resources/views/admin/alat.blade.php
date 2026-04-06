@extends('admin.layout')

@section('title', 'Data Alat - Sistem Peminjaman Alat')

@section('content')
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
                        @forelse($alat as $item)
                        <tr>
                            <td>{{ $item->kode_barang ?? '-' }}</td>
                            <td>{{ $item->nama_alat }}</td>
                            <td>{{ $item->kategori->nama_kategori ?? 'N/A' }}</td>
                            <td>{{ $item->lokasi ?? '-' }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>
                                <span class="status-badge
                                    @if($item->kondisi == 'baik') status-dipinjam
                                    @else status-belum_dikembalikan
                                    @endif">
                                    {{ ucfirst($item->kondisi) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editAlat({{ $item->id_alat }}, '{{ $item->nama_alat }}', {{ $item->id_kategori }}, {{ $item->stok }}, '{{ $item->deskripsi }}', '{{ $item->lokasi }}')" data-bs-toggle="modal" data-bs-target="#editAlatModal">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </button>
                                    <form action="{{ route('admin.deleteAlat', ['id' => $item->id_alat]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center">Tidak ada data alat</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        @endforelse
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
                    <form action="{{ route('admin.storeAlat') }}" method="POST" id="addAlatForm">
                        @csrf
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
                                    @foreach($kategoriList as $kategori)
                                        <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                                    @endforeach
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
                        
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi alat"></textarea>
                        </div>

                        <!-- Dynamic Items Section (shown when stock > 1) -->
                        <div id="itemsSection" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-barcode me-2"></i>Detail Kode Barang per Item</h6>
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
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="edit_nama_alat" class="form-label">Nama Alat</label>
                                <input type="text" class="form-control" id="edit_nama_alat" name="nama_alat" required list="editAlatSuggestions">
                                <datalist id="editAlatSuggestions">
                                    <!-- Options will be populated dynamically based on selected category -->
                                </datalist>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_id_kategori" class="form-label">Kategori</label>
                                <select class="form-select" id="edit_id_kategori" name="id_kategori" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoriList as $kategori)
                                        <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                                    @endforeach
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
        const itemsContainer = document.getElementById('itemsContainer');

        if (stok === 1) {
            // Show single code barang field
            itemsSection.style.display = 'none';
            kodeBarangSingle.style.display = 'block';
            itemsContainer.innerHTML = '';
        } else if (stok > 1) {
            // Show multiple items section
            kodeBarangSingle.style.display = 'none';
            itemsSection.style.display = 'block';
            itemsContainer.innerHTML = '';

            for (let i = 0; i < stok; i++) {
                const itemHtml = `
                    <div class="card mb-2">
                        <div class="card-body py-2">
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label small">Kode Barang #${i + 1}</label>
                                    <input type="text" class="form-control form-control-sm" name="items[${i}][kode_barang]" placeholder="Kode barang">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Lokasi</label>
                                    <input type="text" class="form-control form-control-sm" name="items[${i}][lokasi]" placeholder="Lokasi item">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Kondisi</label>
                                    <select class="form-select form-select-sm" name="items[${i}][kondisi]" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="baik">Baik</option>
                                        <option value="diperbaiki">Diperbaiki</option>
                                        <option value="rusak">Rusak</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Item #${i + 1}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                itemsContainer.innerHTML += itemHtml;
            }
        } else {
            itemsSection.style.display = 'none';
            kodeBarangSingle.style.display = 'block';
            itemsContainer.innerHTML = '';
        }
    }

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
            // Validate multiple items kode barang
            const kodeBarangInputs = this.querySelectorAll('input[name^="items["][name$="[kode_barang]"]');

            kodeBarangInputs.forEach((input, index) => {
                if (!input.value.trim()) {
                    hasError = true;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
        }

        if (hasError) {
            e.preventDefault();
            alert('Mohon isi kode barang untuk semua item!');
        }
    });

    function editAlat(id, nama_alat, id_kategori, stok, deskripsi, lokasi) {
        // Fetch the equipment details including condition
        fetch(`/admin/alat/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_nama_alat').value = data.nama_alat;
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
@endsection

@section('scripts')
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
@endsection
