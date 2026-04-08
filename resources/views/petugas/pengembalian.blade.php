@extends('petugas.layout')

@section('title', 'Pengembalian Alat - Sistem Peminjaman Alat')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengembalianList as $pengembalianItem)
                        <tr id="row-{{ $pengembalianItem->id_pengembalian }}">
                            <td>{{ sprintf('PGB%03d', $pengembalianItem->id_pengembalian) }}</td>
                            <td>{{ sprintf('PJN%03d', $pengembalianItem->peminjaman->id_peminjaman) }}</td>
                            <td>{{ $pengembalianItem->peminjaman->user->nama ?? 'N/A' }}</td>
                            <td>
                                @foreach($pengembalianItem->peminjaman->detailPeminjaman as $index => $detail)
                                    {{ $detail->alat->nama_alat }}
                                    @if(!$loop->last), @endif
                                @endforeach
                            </td>
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
                                @if($pengembalianItem->bukti_foto)
                                    <button type="button" class="btn btn-sm text-white view-foto-btn"
                                            style="background-color: #ff8c42; border-color: #ff8c42;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#fotoBuktiModal"
                                            data-foto="{{ asset($pengembalianItem->bukti_foto) }}"
                                            data-alat="{{ collect($pengembalianItem->peminjaman->detailPeminjaman)->pluck('alat.nama_alat')->join(', ') }}"
                                            data-id="{{ $pengembalianItem->id_pengembalian }}">
                                        <i class="fas fa-image me-1"></i>Lihat Foto
                                    </button>
                                @else
                                    <span class="text-muted"><i class="fas fa-camera-slash me-1"></i>Tidak ada foto</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada riwayat pengembalian</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Foto Bukti Modal -->
    <div class="modal fade" id="fotoBuktiModal" tabindex="-1" aria-labelledby="fotoBuktiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom-color: #ff8c42;">
                    <h5 class="modal-title" id="fotoBuktiModalLabel"><i class="fas fa-image me-2" style="color: #ff8c42;"></i>Bukti Foto Pengembalian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <label class="form-label text-start d-block fw-bold" style="color: #ff6b00;">Nama Alat</label>
                        <div class="mb-3 p-3 rounded" style="background-color: #fff5eb; border: 1px solid #ff8c42;">
                            <i class="fas fa-tools me-2" style="color: #ff8c42;"></i><span id="fotoBuktiAlat"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-start d-block fw-bold" style="color: #ff6b00;">Foto Bukti Pengembalian</label>
                        <div class="border rounded p-2" style="background-color: #fff5eb; border-color: #ff8c42 !important;">
                            <img id="fotoBuktiImage" src="" alt="Bukti Foto Pengembalian" class="img-fluid" style="max-width: 100%; max-height: 500px;">
                        </div>
                    </div>

                    <!-- Form Kondisi Kerusakan (hidden by default) -->
                    <div id="formKerusakanSection" style="display: none;" class="mt-3">
                        <div class="card" style="border-color: #ff8c42;">
                            <div class="card-header text-white" style="background-color: #ff8c42;">
                                <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Form Penilaian Kerusakan</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-start d-block fw-bold" style="color: #ff6b00;">Kondisi Alat Saat Dikembalikan</label>
                                    <div class="d-flex justify-content-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kondisi_alat" id="kondisiBaik" value="baik" checked>
                                            <label class="form-check-label fw-bold" for="kondisiBaik" style="color: #28a745;">
                                                <i class="fas fa-check-circle me-1"></i>Baik
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kondisi_alat" id="kondisiRusak" value="rusak">
                                            <label class="form-check-label fw-bold" style="color: #dc3545;">
                                                <i class="fas fa-times-circle me-1"></i>Rusak
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Persentase Kerusakan (only shown when rusak selected) -->
                                <div id="persenKerusakanSection" style="display: none;">
                                    <div class="mb-3">
                                        <label for="persenKerusakan" class="form-label fw-bold" style="color: #ff6b00;">Persentase Kerusakan (%)</label>
                                        <input type="number" class="form-control" id="persenKerusakan" min="1" max="100" value="0" placeholder="Masukkan persentase kerusakan (1-100)" style="border-color: #ff8c42;">
                                        <div class="form-text">Masukkan berapa persen kerusakan alat</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="hargaAlatInput" class="form-label fw-bold" style="color: #ff6b00;">Harga Alat (Rp)</label>
                                        <input type="number" class="form-control" id="hargaAlatInput" min="0" value="0" placeholder="Masukkan harga alat" style="border-color: #ff8c42;">
                                        <div class="form-text">Masukkan harga beli alat baru untuk perhitungan denda</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold" style="color: #ff6b00;">Perhitungan Denda</label>
                                        <div class="p-3 rounded text-white" style="background: linear-gradient(135deg, #ff6b00 0%, #ff8c42 100%);">
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <small class="text-white-50">Harga Alat</small>
                                                    <div class="fw-bold fs-6" id="hargaAlatDisplay">Rp 0</div>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <small class="text-white-50">Persentase Kerusakan</small>
                                                    <div class="fw-bold fs-6" id="persenDisplay">0%</div>
                                                </div>
                                            </div>
                                            <hr class="text-white">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <small class="text-white-50">Denda Kerusakan</small>
                                                    <div class="fw-bold fs-4" id="dendaKerusakanDisplay">Rp 0</div>
                                                </div>
                                            </div>
                                            <div class="mt-2 p-2 rounded" style="background-color: rgba(255,255,255,0.2);">
                                                <small><strong>Rumus:</strong> Harga Alat × (Persentase / 100)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="downloadFoto" href="#" class="btn text-white" style="background-color: #ff8c42; border-color: #ff8c42;" download>
                        <i class="fas fa-download me-1"></i>Download Foto
                    </a>
                    <button type="button" id="btnBelumDikembalikan" class="btn text-white" style="background-color: #ffc107; border-color: #ffc107;">
                        <i class="fas fa-times me-1"></i>Belum Dikembalikan
                    </button>
                    <button type="button" id="btnSudahDikembalikan" class="btn text-white" style="background-color: #ff6b00; border-color: #ff6b00;">
                        <i class="fas fa-check me-1"></i>Sudah Dikembalikan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle foto bukti modal
    var fotoModal = document.getElementById('fotoBuktiModal');
    var currentIdPengembalian = null;
    var currentDetailIds = [];

    fotoModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var fotoUrl = button.getAttribute('data-foto');
        var alatName = button.getAttribute('data-alat');
        var idPengembalian = button.getAttribute('data-id');

        // Store current id_pengembalian
        currentIdPengembalian = idPengembalian;

        // Update modal content
        document.getElementById('fotoBuktiImage').src = fotoUrl;
        document.getElementById('fotoBuktiAlat').textContent = alatName || 'N/A';
        document.getElementById('downloadFoto').href = fotoUrl;

        // Reset form
        document.getElementById('formKerusakanSection').style.display = 'none';
        document.getElementById('persenKerusakanSection').style.display = 'none';
        document.getElementById('kondisiBaik').checked = true;
        document.getElementById('persenKerusakan').value = 0;
        document.getElementById('dendaKerusakan').value = 0;
        document.getElementById('dendaKerusakanDisplay').textContent = 'Rp 0';
        document.getElementById('persenDisplay').textContent = '0%';

        // Fetch alat details
        fetch('/petugas/api/pengembalian/' + idPengembalian + '/details')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentDetailIds = data.details;
                    
                    // Show form when "Sudah Dikembalikan" is clicked
                    document.getElementById('btnSudahDikembalikan').onclick = function() {
                        showKerusakanForm();
                    };
                }
            })
            .catch(error => console.error('Error fetching details:', error));
    });

    // Reset currentIdPengembalian when modal is closed
    fotoModal.addEventListener('hidden.bs.modal', function () {
        currentIdPengembalian = null;
        currentDetailIds = [];
        // Reset button handler
        document.getElementById('btnSudahDikembalikan').onclick = handleSudahDikembalikan;
    });

    // Handle "Belum Dikembalikan" button - reset to dipinjam status
    document.getElementById('btnBelumDikembalikan').addEventListener('click', function() {
        if (!currentIdPengembalian) return;

        if (!confirm('Apakah Anda yakin bukti foto ini tidak valid? Status akan dikembalikan ke "Menunggu Verifikasi" dan peminjam dapat upload ulang.')) {
            return;
        }

        // Send AJAX request
        fetch('/petugas/pengembalian/' + currentIdPengembalian + '/tolak-verifikasi', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                alasan: 'Bukti foto tidak valid'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                var modal = bootstrap.Modal.getInstance(fotoModal);
                modal.hide();

                // Show success message
                alert('Bukti foto ditolak! Peminjam akan mendapat notifikasi untuk upload ulang.');
                
                // Reload page to refresh table
                window.location.reload();
            } else {
                alert('Terjadi kesalahan: ' + (data.message || 'Gagal menolak verifikasi'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menolak verifikasi');
        });
    });

    // Show damage form
    function showKerusakanForm() {
        document.getElementById('formKerusakanSection').style.display = 'block';

        // Change button to submit form
        var btnSudah = document.getElementById('btnSudahDikembalikan');
        btnSudah.onclick = submitKonfirmasi;
        btnSudah.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Konfirmasi & Hapus';
    }

    // Handle radio button change
    document.addEventListener('change', function(e) {
        if (e.target.name === 'kondisi_alat') {
            if (e.target.value === 'rusak') {
                document.getElementById('persenKerusakanSection').style.display = 'block';
            } else {
                document.getElementById('persenKerusakanSection').style.display = 'none';
                document.getElementById('persenKerusakan').value = 0;
                document.getElementById('dendaKerusakanDisplay').textContent = 'Rp 0';
                document.getElementById('persenDisplay').textContent = '0%';
            }
        }
    });

    // Calculate damage
    document.getElementById('persenKerusakan').addEventListener('input', function() {
        hitungDenda();
    });

    document.getElementById('hargaAlatInput').addEventListener('input', function() {
        hitungDenda();
    });

    function hitungDenda() {
        var hargaAlat = parseFloat(document.getElementById('hargaAlatInput').value) || 0;
        var persen = parseFloat(document.getElementById('persenKerusakan').value) || 0;

        // Validate percentage
        if (persen < 0) persen = 0;
        if (persen > 100) persen = 100;

        // Calculate: harga_alat × (persen / 100)
        var denda = hargaAlat * (persen / 100);

        // Update display
        document.getElementById('persenDisplay').textContent = persen + '%';
        document.getElementById('hargaAlatDisplay').textContent = formatRupiah(hargaAlat);
        document.getElementById('dendaKerusakanDisplay').textContent = formatRupiah(Math.round(denda));
    }

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    // Handle "Sudah Dikembalikan" button with form
    function handleSudahDikembalikan() {
        if (!currentIdPengembalian) return;

        // Show damage form first
        showKerusakanForm();
    }

    // Initial button handler (will be overridden when modal opens)
    document.getElementById('btnSudahDikembalikan').addEventListener('click', function() {
        handleSudahDikembalikan();
    });

    // Submit confirmation after filling form
    function submitKonfirmasi() {
        var kondisi = document.querySelector('input[name="kondisi_alat"]:checked').value;
        var persen = parseFloat(document.getElementById('persenKerusakan').value) || 0;
        var hargaAlat = parseFloat(document.getElementById('hargaAlatInput').value) || 0;
        var denda = hargaAlat * (persen / 100);

        // Validate if rusak
        if (kondisi === 'rusak' && (persen < 1 || persen > 100)) {
            alert('Mohon masukkan persentase kerusakan antara 1-100%');
            return;
        }

        if (kondisi === 'rusak' && hargaAlat <= 0) {
            alert('Mohon masukkan harga alat yang valid');
            return;
        }

        if (!confirm('Apakah Anda yakin alat sudah dikembalikan? Data akan dihapus dari daftar.')) {
            return;
        }

        // Send AJAX request
        var formData = {
            kondisi: kondisi,
            persen_kerusakan: kondisi === 'rusak' ? persen : 0,
            denda_kerusakan: kondisi === 'rusak' ? Math.round(denda) : 0
        };

        fetch('/petugas/pengembalian/' + currentIdPengembalian + '/confirm-returned', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove row from table
                var row = document.getElementById('row-' + currentIdPengembalian);
                if (row) {
                    row.remove();
                }

                // Close modal
                var modal = bootstrap.Modal.getInstance(fotoModal);
                modal.hide();

                // Show success message with damage info
                var message = 'Data berhasil dihapus!';
                if (kondisi === 'rusak') {
                    message += '\n\nKondisi: Rusak\nPersentase: ' + persen + '%\nDenda: ' + formatRupiah(denda);
                }
                alert(message);

                // Check if table is empty
                var tbody = document.querySelector('#petugasPengembalianTable tbody');
                if (tbody.querySelectorAll('tr').length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center">Belum ada riwayat pengembalian</td></tr>';
                }
            } else {
                alert('Terjadi kesalahan: ' + (data.message || 'Gagal menghapus data'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data');
        });
    }
});
</script>
@endsection

@section('scripts')
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
@endsection
