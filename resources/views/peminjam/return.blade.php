@extends('peminjam.layout')

@section('title', 'Pengembalian Alat - Sistem Peminjaman Alat')

@section('content')
<div class="container-fluid">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-calendar-check text-orange me-2"></i>Pengembalian Alat</h2>
                <p class="text-muted mb-0">Daftar alat yang sedang dipinjam oleh Anda</p>
            </div>
        </div>
    </div>

    <!-- Active Borrowings Table -->
    <div class="card card-custom table-custom">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-orange"></i>Daftar Alat Dipinjam</h5>
            <button type="button" class="btn btn-orange" id="btnReturnSelected" onclick="returnSelected()" disabled>
                <i class="fas fa-calendar-check me-2"></i>Kembalikan Alat Dipilih
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th width="50"><input type="checkbox" id="selectAll" onchange="toggleSelectAll()"></th>
                            <th>Kode Barang</th>
                            <th>Nama Alat</th>
                            <th>Jumlah Dipinjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali Rencana</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanAktif as $peminjaman)
                        @php
                            $detailsData = $peminjaman->detailPeminjaman->map(function($item) {
                                return [
                                    "id_alat" => $item->id_alat,
                                    "kode_barang" => $item->kode_barang,
                                    "nama_alat" => $item->alat->nama_alat,
                                    "jumlah" => $item->jumlah
                                ];
                            })->toArray();
                        @endphp
                        <tr>
                            <td>
                                @if($peminjaman->status == 'dipinjam' || $peminjaman->status == 'terlambat')
                                    <input type="checkbox" class="item-checkbox"
                                        data-id-peminjaman="{{ $peminjaman->id_peminjaman }}"
                                        data-details="{{ json_encode($detailsData) }}"
                                        onchange="updateReturnButton()">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @foreach($peminjaman->detailPeminjaman as $index => $detail)
                                    {{ $detail->kode_barang ?? '-' }}
                                    @if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($peminjaman->detailPeminjaman as $index => $detail)
                                    {{ $detail->alat->nama_alat }}
                                    @if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($peminjaman->detailPeminjaman as $index => $detail)
                                    {{ $detail->jumlah }}
                                    @if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d M Y') }}</td>

                            <td>
                                <span class="status-badge
                                    @if($peminjaman->status == 'dipinjam') status-dipinjam
                                    @elseif($peminjaman->status == 'terlambat') status-belum_dikembalikan
                                    @elseif($peminjaman->status == 'menunggu persetujuan') status-belum_dikembalikan
                                    @elseif($peminjaman->status == 'dikembalikan') status-dikembalikan
                                    @elseif($peminjaman->status == 'ditolak') status-ditolak
                                    @endif">
                                    @if($peminjaman->status == 'menunggu persetujuan')
                                        Menunggu Persetujuan
                                    @elseif($peminjaman->status == 'dipinjam')
                                        Dipinjam
                                    @elseif($peminjaman->status == 'dikembalikan')
                                        Dikembalikan
                                    @elseif($peminjaman->status == 'ditolak')
                                        Ditolak
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if($peminjaman->status == 'dipinjam' || $peminjaman->status == 'terlambat')
                                    <button type="button" class="btn btn-sm btn-orange" data-bs-toggle="modal" data-bs-target="#confirmReturnModal"
                                        data-id="{{ $peminjaman->id_peminjaman }}"
                                        data-details="{{ json_encode($detailsData) }}">
                                        <i class="fas fa-calendar-check me-1"></i>Kembalikan
                                    </button>
                                @elseif($peminjaman->status == 'menunggu persetujuan')
                                    <span class="text-muted"><i class="fas fa-clock me-1"></i>Menunggu Persetujuan Petugas</span>
                                @elseif($peminjaman->status == 'ditolak')
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#alasanDitolakModal"
                                        data-alasan="{{ $peminjaman->alasan_ditolak ?? 'Tidak ada alasan' }}">
                                        <i class="fas fa-info-circle me-1"></i>Lihat Alasan
                                    </button>
                                @elseif($peminjaman->status == 'dikembalikan')
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada alat yang sedang dipinjam</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmReturnModal" tabindex="-1" aria-labelledby="confirmReturnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmReturnModalLabel">Konfirmasi Pengembalian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="returnForm" method="POST" action="{{ route('peminjam.storeReturn') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_peminjaman" id="peminjamanId">

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pengembalian</label>
                            <input type="text" class="form-control" id="tanggalKembali" readonly>
                        </div>

                        <div id="equipmentDetails">
                            <!-- Equipment details will be populated dynamically -->
                        </div>

                        <div class="mb-3 mt-4">
                            <label class="form-label"><i class="fas fa-camera me-2"></i>Foto Bukti Pengembalian</label>
                            <input type="file" class="form-control" name="bukti_foto" id="buktiFoto" accept="image/*" onchange="previewFoto(this)">
                            <div class="form-text">Upload foto kondisi alat saat dikembalikan (opsional, format: JPG, PNG, max 5MB)</div>
                            <div id="fotoPreview" class="mt-2" style="display: none;">
                                <img id="preview" src="" alt="Preview Foto" class="img-thumbnail" style="max-width: 100%; max-height: 300px;">
                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="hapusFoto()">
                                    <i class="fas fa-trash me-1"></i>Hapus Foto
                                </button>
                            </div>
                        </div>

                        <p class="text-center mt-3">Apakah Anda yakin ingin mengembalikan alat ini?</p>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="returnForm" class="btn btn-orange">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Damage Description Modal -->
    <div class="modal fade" id="damageDescriptionModal" tabindex="-1" aria-labelledby="damageDescriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="damageDescriptionModalLabel"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Deskripsi Kerusakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="damageDescriptionForm">
                        <input type="hidden" id="damageTempIndex">
                        <input type="hidden" id="damageModalType" value="individual">
                        <div class="mb-3">
                            <label class="form-label">Nama Alat</label>
                            <input type="text" class="form-control" id="damageAlatName" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Kerusakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="damageDescription" rows="4" placeholder="Jelaskan kondisi kerusakan alat secara detail..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-orange" onclick="saveDamageDescription()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal for Selected Items -->
    <div class="modal fade" id="confirmReturnSelectedModal" tabindex="-1" aria-labelledby="confirmReturnSelectedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmReturnSelectedModalLabel">Konfirmasi Pengembalian Alat Dipilih</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="returnSelectedForm" method="POST" action="{{ route('peminjam.storeReturn') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_peminjaman" id="peminjamanIdSelected">

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pengembalian</label>
                            <input type="text" class="form-control" id="tanggalKembaliSelected" readonly>
                        </div>

                        <div id="selectedEquipmentDetails">
                            <!-- Selected equipment details will be populated dynamically -->
                        </div>

                        <div class="mb-3 mt-4">
                            <label class="form-label"><i class="fas fa-camera me-2"></i>Foto Bukti Pengembalian</label>
                            <input type="file" class="form-control" name="bukti_foto" id="buktiFotoSelected" accept="image/*" onchange="previewFotoSelected(this)">
                            <div class="form-text">Upload foto kondisi alat saat dikembalikan (opsional, format: JPG, PNG, max 5MB)</div>
                            <div id="fotoPreviewSelected" class="mt-2" style="display: none;">
                                <img id="previewSelected" src="" alt="Preview Foto" class="img-thumbnail" style="max-width: 100%; max-height: 300px;">
                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="hapusFotoSelected()">
                                    <i class="fas fa-trash me-1"></i>Hapus Foto
                                </button>
                            </div>
                        </div>

                        <p class="text-center mt-3">Apakah Anda yakin ingin mengembalikan alat yang dipilih?</p>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="returnSelectedForm" class="btn btn-orange">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alasan Ditolak Modal -->
    <div class="modal fade" id="alasanDitolakModal" tabindex="-1" aria-labelledby="alasanDitolakModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alasanDitolakModalLabel"><i class="fas fa-times-circle text-danger me-2"></i>Peminjaman Ditolak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peminjaman Anda Ditolak!</strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="alasanDitolakText" rows="5" readonly></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Store damage descriptions
    window.damageDescriptions = {};

    // Set today's date for the return date field
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });

        // Set the return date to today
        document.getElementById('tanggalKembali').value = today;
        document.getElementById('tanggalKembaliSelected').value = today;

        // Handle modal data population for individual return
        var confirmReturnModal = document.getElementById('confirmReturnModal');
        confirmReturnModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var details = JSON.parse(button.getAttribute('data-details'));

            document.getElementById('equipmentDetails').innerHTML = '';
            window.damageDescriptions = {}; // Reset damage descriptions

            var equipmentDetailsHtml = '';
            details.forEach(function(detail, index) {
                equipmentDetailsHtml += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title">${detail.nama_alat}</h6>
                            <div class="mb-2">
                                <small class="text-muted">Kode Barang: <strong>${detail.kode_barang || '-'}</strong></small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kondisi Alat</label>
                                <select class="form-select" name="kondisi_alat[${index}]" id="kondisi_alat_${index}" onchange="handleConditionChange(this, ${index}, '${detail.nama_alat}', 'individual')">
                                    <option value="baik" selected>Baik</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                            </div>
                            <div class="mb-3 damage-description-field" id="damage_field_${index}" style="display: none;">
                                <label class="form-label">Deskripsi Kerusakan <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="deskripsi_kerusakan[${index}]" rows="3" placeholder="Jelaskan kondisi kerusakan alat secara detail..."></textarea>
                            </div>
                            <input type="hidden" name="id_alat[${index}]" value="${detail.id_alat}">
                            <input type="hidden" name="jumlah_dikembalikan[${index}]" value="${detail.jumlah}">
                        </div>
                    </div>
                `;
            });

            document.getElementById('equipmentDetails').innerHTML = equipmentDetailsHtml;
            document.getElementById('peminjamanId').value = id;
        });
    });

    // Handle condition change - show/hide damage description field
    function handleConditionChange(selectElement, index, alatName, modalType) {
        const damageField = document.getElementById(`damage_field_${index}`);
        
        if (selectElement.value === 'rusak') {
            damageField.style.display = 'block';
        } else {
            damageField.style.display = 'none';
            // Clear the textarea when condition is changed to 'baik'
            const textarea = damageField.querySelector('textarea');
            if (textarea) {
                textarea.value = '';
            }
        }
    }

    // Toggle select all checkboxes
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.item-checkbox');

        checkboxes.forEach(function(checkbox) {
            checkbox.checked = selectAll.checked;
        });

        updateReturnButton();
    }

    // Update return button state
    function updateReturnButton() {
        const checkboxes = document.querySelectorAll('.item-checkbox:checked');
        const btnReturn = document.getElementById('btnReturnSelected');

        if (checkboxes.length > 0) {
            btnReturn.disabled = false;
            btnReturn.innerHTML = '<i class="fas fa-calendar-check me-2"></i>Kembalikan ' + checkboxes.length + ' Peminjaman Dipilih';
        } else {
            btnReturn.disabled = true;
            btnReturn.innerHTML = '<i class="fas fa-calendar-check me-2"></i>Kembalikan Alat Dipilih';
        }

        // Uncheck select all if any checkbox is unchecked
        const selectAll = document.getElementById('selectAll');
        const allCheckboxes = document.querySelectorAll('.item-checkbox');
        selectAll.checked = checkboxes.length === allCheckboxes.length;
    }

    // Return selected items
    function returnSelected() {
        const checkboxes = document.querySelectorAll('.item-checkbox:checked');

        if (checkboxes.length === 0) {
            alert('Pilih minimal 1 peminjaman untuk dikembalikan');
            return;
        }

        // Build equipment details from selected checkboxes
        let allItems = [];
        let peminjamanIds = [];

        checkboxes.forEach(function(checkbox) {
            const details = JSON.parse(checkbox.getAttribute('data-details'));
            const idPeminjaman = checkbox.getAttribute('data-id-peminjaman');
            peminjamanIds.push(idPeminjaman);

            details.forEach(function(detail) {
                allItems.push({
                    id_peminjaman: idPeminjaman,
                    id_alat: detail.id_alat,
                    nama_alat: detail.nama_alat,
                    jumlah: detail.jumlah
                });
            });
        });

        // Build the equipment details HTML
        let equipmentDetailsHtml = '';
        let globalIndex = 0;

        // Group by peminjaman
        const groupedByPeminjaman = {};
        allItems.forEach(function(item) {
            if (!groupedByPeminjaman[item.id_peminjaman]) {
                groupedByPeminjaman[item.id_peminjaman] = [];
            }
            groupedByPeminjaman[item.id_peminjaman].push(item);
        });

        for (const [idPeminjaman, items] of Object.entries(groupedByPeminjaman)) {
            equipmentDetailsHtml += `<div class="card mb-3"><div class="card-header bg-orange text-white">ID Peminjaman: PJN${idPeminjaman}</div><div class="card-body">`;

            items.forEach(function(detail) {
                equipmentDetailsHtml += `
                    <div class="mb-3 pb-3" style="border-bottom: 1px solid #eee;">
                        <h6 class="card-title">${detail.nama_alat}</h6>
                        <div class="mb-2">
                            <small class="text-muted">Kode Barang: <strong>${detail.kode_barang || '-'}</strong></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kondisi Alat</label>
                            <select class="form-select" name="kondisi_alat[${globalIndex}]" id="kondisi_alat_${globalIndex}" onchange="handleConditionChange(this, ${globalIndex}, '${detail.nama_alat}', 'selected')">
                                <option value="baik" selected>Baik</option>
                                <option value="rusak">Rusak</option>
                            </select>
                        </div>
                        <div class="mb-3 damage-description-field" id="damage_field_${globalIndex}" style="display: none;">
                            <label class="form-label">Deskripsi Kerusakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="deskripsi_kerusakan[${globalIndex}]" rows="3" placeholder="Jelaskan kondisi kerusakan alat secara detail..."></textarea>
                        </div>
                        <input type="hidden" name="id_alat[${globalIndex}]" value="${detail.id_alat}">
                        <input type="hidden" name="jumlah_dikembalikan[${globalIndex}]" value="${detail.jumlah}">
                        <input type="hidden" name="id_peminjaman_item[${globalIndex}]" value="${idPeminjaman}">
                    </div>
                `;
                globalIndex++;
            });

            equipmentDetailsHtml += `</div></div>`;
        }

        document.getElementById('selectedEquipmentDetails').innerHTML = equipmentDetailsHtml;

        // Set the first peminjaman ID (for compatibility with controller)
        document.getElementById('peminjamanIdSelected').value = peminjamanIds[0];

        // Show the modal
        var modal = new bootstrap.Modal(document.getElementById('confirmReturnSelectedModal'));
        modal.show();
    }

    // Form validation and submit with AJAX for individual return
    document.addEventListener('DOMContentLoaded', function() {
        const returnForm = document.getElementById('returnForm');
        const submitBtn = document.querySelector('#confirmReturnModal button[type="submit"]');
        
        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Check if there are any damaged fields
                const damagedFields = document.querySelectorAll('#equipmentDetails select[name^="kondisi_alat["][value="rusak"]');
                let hasError = false;

                damagedFields.forEach(function(select) {
                    const indexMatch = select.name.match(/\[(\d+)\]/);
                    if (indexMatch) {
                        const index = indexMatch[1];
                        const textarea = document.querySelector(`textarea[name="deskripsi_kerusakan[${index}]"]`);

                        if (!textarea || textarea.value.trim() === '') {
                            hasError = true;
                            if (select.closest('.card')) {
                                select.closest('.card').classList.add('border-danger');
                            }
                        } else {
                            if (select.closest('.card')) {
                                select.closest('.card').classList.remove('border-danger');
                            }
                        }
                    }
                });

                if (hasError) {
                    alert('Mohon isi deskripsi kerusakan untuk alat yang rusak!');
                    return;
                }

                // Submit form via AJAX
                const formData = new FormData(returnForm);
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

                fetch(returnForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(async response => {
                    console.log('Response status:', response.status);
                    console.log('Response ok:', response.ok);
                    
                    // Clone response untuk logging jika error
                    const responseClone = response.clone();
                    
                    if (!response.ok) {
                        // Coba baca error detail dari response
                        const errorText = await responseClone.text();
                        console.error('Server error response:', errorText);
                        
                        // Coba parse sebagai JSON jika mungkin
                        try {
                            const errorJson = JSON.parse(errorText);
                            throw new Error(errorJson.error || errorJson.message || `Server error: ${response.status}`);
                        } catch (e) {
                            throw new Error(`Server error: ${response.status} - ${errorText.substring(0, 100)}`);
                        }
                    }
                    
                    return response.json();
                })
                .then(data => {
                    console.log('Success response:', data);
                    
                    if (data.success) {
                        // Show success message
                        showSuccessAlert('Pengembalian berhasil diproses! Foto bukti telah diupload.');
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('confirmReturnModal'));
                        if (modal) {
                            modal.hide();
                        }
                        // Reload page after 2 seconds
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showErrorAlert(data.error || 'Terjadi kesalahan saat memproses pengembalian');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Konfirmasi';
                    }
                })
                .catch(error => {
                    console.error('Full error:', error);
                    showServerError('Terjadi kesalahan: ' + error.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Konfirmasi';
                });
            });
        }
    });

    // Form validation and submit with AJAX for selected items return
    document.addEventListener('DOMContentLoaded', function() {
        const returnSelectedForm = document.getElementById('returnSelectedForm');
        const submitBtnSelected = document.querySelector('#confirmReturnSelectedModal button[type="submit"]');
        
        if (submitBtnSelected) {
            submitBtnSelected.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Check if there are any damaged fields
                const damagedFields = document.querySelectorAll('#selectedEquipmentDetails select[name^="kondisi_alat["][value="rusak"]');
                let hasError = false;

                damagedFields.forEach(function(select) {
                    const indexMatch = select.name.match(/\[(\d+)\]/);
                    if (indexMatch) {
                        const index = indexMatch[1];
                        const textarea = document.querySelector(`textarea[name="deskripsi_kerusakan[${index}]"]`);

                        if (!textarea || textarea.value.trim() === '') {
                            hasError = true;
                            if (select.closest('.card')) {
                                select.closest('.card').classList.add('border-danger');
                            }
                        } else {
                            if (select.closest('.card')) {
                                select.closest('.card').classList.remove('border-danger');
                            }
                        }
                    }
                });

                if (hasError) {
                    alert('Mohon isi deskripsi kerusakan untuk alat yang rusak!');
                    return;
                }

                // Submit form via AJAX
                const formData = new FormData(returnSelectedForm);
                
                submitBtnSelected.disabled = true;
                submitBtnSelected.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

                fetch(returnSelectedForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showSuccessAlert('Pengembalian berhasil diproses! Foto bukti telah diupload.');
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('confirmReturnSelectedModal'));
                        if (modal) {
                            modal.hide();
                        }
                        // Reload page after 2 seconds
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showErrorAlert(data.error || 'Terjadi kesalahan saat memproses pengembalian');
                        submitBtnSelected.disabled = false;
                        submitBtnSelected.innerHTML = 'Konfirmasi';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showServerError('Terjadi kesalahan saat memproses pengembalian: ' + error.message);
                    submitBtnSelected.disabled = false;
                    submitBtnSelected.innerHTML = 'Konfirmasi';
                });
            });
        }
    });

    // Helper function to show success alert
    function showSuccessAlert(message) {
        const alertHtml = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert-success');
            if (alert) alert.remove();
        }, 5000);
    }

    // Helper function to show error alert
    function showErrorAlert(message) {
        const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
    }

    // Helper function to show server error alert
    function showServerError(message) {
        const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
    }

    // Handle alasan ditolak modal
    var alasanDitolakModal = document.getElementById('alasanDitolakModal');
    alasanDitolakModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var alasan = button.getAttribute('data-alasan');

        document.getElementById('alasanDitolakText').value = alasan || 'Tidak ada alasan yang diberikan';
    });

    // Preview foto saat dipilih (modal individual)
    function previewFoto(input) {
        const fotoPreview = document.getElementById('fotoPreview');
        const preview = document.getElementById('preview');
        
        if (input.files && input.files[0]) {
            // Validasi ukuran file (max 5MB)
            if (input.files[0].size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB');
                input.value = '';
                fotoPreview.style.display = 'none';
                return;
            }
            
            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(input.files[0].type)) {
                alert('Format file tidak didukung! Gunakan JPG, PNG, atau GIF');
                input.value = '';
                fotoPreview.style.display = 'none';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                fotoPreview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Hapus foto (modal individual)
    function hapusFoto() {
        document.getElementById('buktiFoto').value = '';
        document.getElementById('fotoPreview').style.display = 'none';
        document.getElementById('preview').src = '';
    }

    // Preview foto saat dipilih (modal multiple)
    function previewFotoSelected(input) {
        const fotoPreview = document.getElementById('fotoPreviewSelected');
        const preview = document.getElementById('previewSelected');
        
        if (input.files && input.files[0]) {
            // Validasi ukuran file (max 5MB)
            if (input.files[0].size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB');
                input.value = '';
                fotoPreview.style.display = 'none';
                return;
            }
            
            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(input.files[0].type)) {
                alert('Format file tidak didukung! Gunakan JPG, PNG, atau GIF');
                input.value = '';
                fotoPreview.style.display = 'none';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                fotoPreview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Hapus foto (modal multiple)
    function hapusFotoSelected() {
        document.getElementById('buktiFotoSelected').value = '';
        document.getElementById('fotoPreviewSelected').style.display = 'none';
        document.getElementById('previewSelected').src = '';
    }

    // Reset form saat modal ditutup
    document.getElementById('confirmReturnModal').addEventListener('hidden.bs.modal', function() {
        hapusFoto();
    });

    document.getElementById('confirmReturnSelectedModal').addEventListener('hidden.bs.modal', function() {
        hapusFotoSelected();
    });
</script>
@endsection
