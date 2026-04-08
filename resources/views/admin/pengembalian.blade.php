@extends('admin.layout')

@section('title', 'Daftar Pengembalian - Sistem Peminjaman Alat')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-calendar-check text-orange me-2"></i>Daftar Pengembalian</h2>
                <p class="text-muted mb-0">Semua data pengembalian alat</p>
            </div>
        </div>
    </div>

    <!-- Pengembalian Table -->
    <div class="card card-custom table-custom">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-orange"></i>Daftar Pengembalian</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="adminPengembalianTable">
                    <thead>
                        <tr>
                            <th>Nama User</th>
                            <th>Nama Alat</th>
                            <th>Tanggal Kembali Rencana</th>
                            <th>Tanggal Kembali</th>
                            <th>Kondisi</th>
                            <th>Status Denda</th>
                            <th>Denda</th>
                            <th>Status Verifikasi</th>
                            <th>Bukti Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengembalian as $pengembalianItem)
                            @foreach($pengembalianItem->peminjaman->detailPeminjaman as $detail)
                            <tr>
                                <td>{{ $pengembalianItem->peminjaman->user->nama ?? 'N/A' }}</td>
                                <td>{{ $detail->alat->nama_alat }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengembalianItem->peminjaman->tanggal_kembali_rencana)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengembalianItem->tanggal_kembali)->format('d M Y') }}</td>
                                <td>
                                    <span class="status-badge
                                        @if($pengembalianItem->kondisi_alat == 'baik') status-dikembalikan
                                        @else status-belum_dikembalikan
                                        @endif">
                                        {{ ucfirst($pengembalianItem->kondisi_alat) }}
                                    </span>
                                </td>
                                <td>
                                    @if($pengembalianItem->denda > 0)
                                        <span class="status-badge status-belum_dikembalikan">
                                            <i class="fas fa-exclamation-circle me-1"></i>Ada Denda
                                        </span>
                                    @else
                                        <span class="status-badge status-dikembalikan">
                                            <i class="fas fa-check-circle me-1"></i>Tidak Ada Denda
                                        </span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($pengembalianItem->denda, 0, ',', '.') }}</td>
                                <td>
                                    @if($pengembalianItem->peminjaman->status == 'menunggu verifikasi pengembalian')
                                        <span class="status-badge status-belum_dikembalikan">
                                            <i class="fas fa-hourglass-half me-1"></i>Menunggu Verifikasi
                                        </span>
                                    @elseif($pengembalianItem->peminjaman->status == 'dikembalikan')
                                        <span class="status-badge status-dikembalikan">
                                            <i class="fas fa-check-circle me-1"></i>Diterima
                                        </span>
                                    @else
                                        <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $pengembalianItem->peminjaman->status)) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pengembalianItem->bukti_foto)
                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="showFoto('{{ asset($pengembalianItem->bukti_foto) }}', '{{ $detail->alat->nama_alat }}')" data-bs-toggle="modal" data-bs-target="#fotoModal">
                                            <i class="fas fa-eye me-1"></i>Lihat
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($pengembalianItem->peminjaman->status == 'menunggu verifikasi pengembalian')
                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="openVerifikasiModal({{ $pengembalianItem->id_pengembalian }}, {{ json_encode($pengembalianItem->peminjaman->detailPeminjaman->map(function($d) {
                                            return [
                                                'id_alat' => $d->id_alat,
                                                'nama_alat' => $d->alat->nama_alat,
                                                'jumlah' => $d->jumlah
                                            ];
                                        })) }})">
                                            <i class="fas fa-clipboard-check me-1"></i>Verifikasi
                                        </button>
                                        @endif
                                        <form action="{{ route('admin.deletePengembalian', ['id' => $pengembalianItem->id_pengembalian]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash me-1"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @empty
                        <tr>
                            <td class="text-center" colspan="10">Tidak ada data pengembalian</td>
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
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Foto Bukti Modal -->
    <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fotoModalLabel">
                        <i class="fas fa-image text-info me-2"></i>Bukti Foto Pengembalian
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-muted mb-3"><strong id="fotoAlatName">-</strong></p>
                    <img id="fotoBukti" src="" alt="Bukti Foto" class="img-fluid rounded" style="max-width: 100%; max-height: 70vh;">
                </div>
                <div class="modal-footer">
                    <a id="downloadFoto" href="#" class="btn btn-outline-primary" download>
                        <i class="fas fa-download me-1"></i>Download
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Verifikasi Pengembalian -->
    <div class="modal fade" id="verifikasiModal" tabindex="-1" aria-labelledby="verifikasiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verifikasiModalLabel">
                        <i class="fas fa-clipboard-check me-2"></i>Verifikasi Pengembalian
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="verifikasiAlatList">
                        <!-- Alat list will be populated dynamically -->
                    </div>
                    <input type="hidden" id="verifikasiIdPengembalian">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-outline-danger" onclick="submitVerifikasi('tolak')">
                        <i class="fas fa-times me-1"></i>Tolak
                    </button>
                    <button type="button" class="btn btn-success" onclick="submitVerifikasi('terima')">
                        <i class="fas fa-check me-1"></i>Terima
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showFoto(fotoUrl, alatName) {
        document.getElementById('fotoBukti').src = fotoUrl;
        document.getElementById('fotoAlatName').textContent = alatName || 'N/A';
        document.getElementById('downloadFoto').href = fotoUrl;
    }

    function openVerifikasiModal(idPengembalian, detailList) {
        document.getElementById('verifikasiIdPengembalian').value = idPengembalian;
        
        let html = '<div class="alert alert-warning"><i class="fas fa-info-circle me-2"></i>Pilih kondisi untuk setiap alat:</div>';
        html += '<div class="table-responsive"><table class="table table-bordered">';
        html += '<thead><tr><th>Nama Alat</th><th>Jumlah</th><th>Kondisi</th></tr></thead>';
        html += '<tbody>';
        
        detailList.forEach(function(detail, index) {
            html += '<tr>';
            html += '<td>' + detail.nama_alat + '</td>';
            html += '<td>' + detail.jumlah + '</td>';
            html += '<td>';
            html += '<div class="form-check form-check-inline">';
            html += '<input class="form-check-input" type="radio" name="kondisi_alat[' + index + ']" id="kondisi_baik_' + index + '" value="baik" checked>';
            html += '<label class="form-check-label text-success" for="kondisi_baik_' + index + '"><i class="fas fa-check-circle me-1"></i>Baik</label>';
            html += '</div>';
            html += '<div class="form-check form-check-inline">';
            html += '<input class="form-check-input" type="radio" name="kondisi_alat[' + index + ']" id="kondisi_rusak_' + index + '" value="rusak">';
            html += '<label class="form-check-label text-danger" for="kondisi_rusak_' + index + '"><i class="fas fa-times-circle me-1"></i>Rusak</label>';
            html += '</div>';
            html += '<input type="hidden" name="jumlah_dikembalikan[' + index + ']" value="' + detail.jumlah + '">';
            html += '<input type="hidden" name="id_alat[' + index + ']" value="' + detail.id_alat + '">';
            html += '</td>';
            html += '</tr>';
        });
        
        html += '</tbody></table></div>';
        html += '<div class="alert alert-info mt-2"><i class="fas fa-info-circle me-2"></i><strong>Catatan:</strong> Alat yang rusak tidak akan masuk kembali ke stok dan akan dikenakan denda kerusakan.</div>';
        
        document.getElementById('verifikasiAlatList').innerHTML = html;
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('verifikasiModal'));
        modal.show();
    }

    function submitVerifikasi(action) {
        const idPengembalian = document.getElementById('verifikasiIdPengembalian').value;
        const actionText = action === 'terima' ? 'menerima' : 'menolak';
        
        // Collect condition data
        const formData = {
            action: action,
            kondisi_alat: {},
            jumlah_dikembalikan: {},
            id_alat: {}
        };
        
        document.querySelectorAll('input[name^="kondisi_alat["]').forEach(function(radio) {
            if (radio.checked || radio.type === 'hidden') {
                if (radio.type === 'radio' && radio.checked) {
                    formData.kondisi_alat[radio.name.match(/\[(\d+)\]/)[1]] = radio.value;
                }
            }
        });
        
        document.querySelectorAll('input[name^="jumlah_dikembalikan["]').forEach(function(input) {
            formData.jumlah_dikembalikan[input.name.match(/\[(\d+)\]/)[1]] = input.value;
        });
        
        document.querySelectorAll('input[name^="id_alat["]').forEach(function(input) {
            formData.id_alat[input.name.match(/\[(\d+)\]/)[1]] = input.value;
        });
        
        // Show loading
        const loadingMsg = document.createElement('div');
        loadingMsg.className = 'alert alert-info alert-dismissible fade show';
        loadingMsg.id = 'loadingMsg';
        loadingMsg.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses verifikasi...';
        document.querySelector('.header-section').after(loadingMsg);
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('verifikasiModal'));
        if (modal) modal.hide();

        // Send AJAX request
        fetch('/admin/pengembalian/' + idPengembalian + '/verifikasi', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let message = `Pengembalian berhasil ${actionText}!`;
                if (data.message && data.message.includes('Denda')) {
                    message += '\n' + data.message.match(/Denda.*/)[0];
                }
                showSuccessAlert(message);
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showErrorAlert(data.error || `Gagal ${actionText} pengembalian`);
                document.getElementById('loadingMsg')?.remove();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorAlert('Terjadi kesalahan saat memproses verifikasi');
            document.getElementById('loadingMsg')?.remove();
        });
    }

    function showSuccessAlert(message) {
        const alertHtml = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
        setTimeout(() => {
            const alert = document.querySelector('.alert-success');
            if (alert) alert.remove();
        }, 5000);
    }

    function showErrorAlert(message) {
        const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
    }
</script>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    setTimeout(function() {
        if ($('#adminPengembalianTable').length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#adminPengembalianTable')) {
                try {
                    $('#adminPengembalianTable').DataTable({
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
                    console.log('✓ adminPengembalianTable DataTable initialized');
                } catch (e) {
                    console.error('Error:', e);
                }
            }
        }
    }, 300);
});
</script>
@endsection