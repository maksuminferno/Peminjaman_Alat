@extends('peminjam.layout')

@section('title', 'Riwayat Peminjaman - Sistem Peminjaman Alat')

@section('content')
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
                <table class="table table-hover datatable" id="historyTable">
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
                        @forelse($peminjamanList as $peminjaman)
                        <tr>
                            <td>{{ sprintf('#PJN%03d', $peminjaman->id_peminjaman) }}</td>
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
                                @if($peminjaman->pengembalian)
                                    {{ \Carbon\Carbon::parse($peminjaman->pengembalian->tanggal_kembali)->format('d M Y') }}
                                @elseif($peminjaman->status == 'ditolak')
                                    <span class="text-muted">-</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge
                                    @if($peminjaman->status == 'dipinjam') status-dipinjam
                                    @elseif($peminjaman->status == 'dikembalikan') status-dikembalikan
                                    @elseif($peminjaman->status == 'terlambat') status-belum_dikembalikan
                                    @elseif($peminjaman->status == 'menunggu persetujuan') status-belum_dikembalikan
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
                                @if($peminjaman->status == 'ditolak' && $peminjaman->alasan_ditolak)
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-1" onclick="showAlasanDitolak('{{ addslashes($peminjaman->alasan_ditolak) }}', '{{ sprintf('#PJN%03d', $peminjaman->id_peminjaman) }}')" data-bs-toggle="modal" data-bs-target="#alasanDitolakModal">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                @endif
                            </td>
                            <td>
                                @if($peminjaman->status == 'dikembalikan' && $peminjaman->pengembalian)
                                    <strong class="text-danger">Rp {{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}</strong>
                                    @if($peminjaman->pengembalian->denda_kerusakan > 0)
                                        <br><small class="text-muted">(Kerusakan: Rp {{ number_format($peminjaman->pengembalian->denda_kerusakan, 0, ',', '.') }})</small>
                                    @endif
                                @elseif($peminjaman->pengembalian && $peminjaman->pengembalian->denda > 0)
                                    Rp {{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}
                                @else
                                    <span class="text-success">Rp 0</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data peminjaman</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
@endsection

@section('scripts')
<script>
    function showAlasanDitolak(alasan, peminjamanId) {
        document.getElementById('alasanPeminjamanId').textContent = peminjamanId;
        document.getElementById('alasanDitolakText').textContent = alasan;
    }

    $(document).ready(function() {
        // Initialize DataTable
        if ($('#historyTable').length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#historyTable')) {
                try {
                    $('#historyTable').DataTable({
                        paging: true,
                        searching: true,
                        ordering: true,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                        pageLength: 10,
                        responsive: true,
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
                    console.log('✓ historyTable DataTable initialized');
                } catch (e) {
                    console.error('Error initializing DataTable:', e);
                }
            }
        }
    });
</script>
@endsection