@extends('petugas.layout')

@section('title', 'Laporan - Sistem Peminjaman Alat')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-file-alt text-orange me-2"></i>Laporan Pengembalian Alat</h2>
                <p class="text-muted mb-0">Daftar pengembalian alat yang telah dilakukan</p>
            </div>
            <div>
                <a href="{{ route('petugas.export-pengembalian') }}" class="btn btn-success">
                    <i class="fas fa-file-excel me-2"></i>Cetak Laporan ke Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Returns Table -->
    <div class="card card-custom table-custom">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-calendar-check me-2 text-orange"></i>Daftar Pengembalian</h5>
            <a href="{{ route('petugas.pengembalian') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="petugasLaporanTable">
                    <thead>
                        <tr>
                            <th>ID Pengembalian</th>
                       
                            <th>Nama Peminjam</th>
                            <th>Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali Rencana</th>
                            <th>Tanggal Kembali</th>
                            <th>Denda Terlambat</th>
                            <th>Denda Kerusakan</th>
                            <th>Total Denda</th>
                            <th>Kondisi</th>
                            <th>Status Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPengembalian as $pengembalian)
                        <tr>
                            <td>{{ sprintf('PGB%03d', $pengembalian->id_pengembalian) }}</td>
                           
                            <td>{{ $pengembalian->peminjaman->user->nama ?? 'N/A' }}</td>
                            <td>
                                @foreach($pengembalian->peminjaman->detailPeminjaman as $index => $detail)
                                    {{ $detail->alat->nama_alat }}
                                    @if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>{{ \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_kembali_rencana)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($pengembalian->tanggal_kembali)->format('d M Y') }}</td>
                            <td>Rp {{ number_format($pengembalian->denda_keterlambatan ?? 0, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($pengembalian->denda_kerusakan ?? 0, 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</strong></td>
                            <td>
                                <span class="status-badge
                                    @if($pengembalian->kondisi_alat == 'baik') status-dikembalikan
                                    @else status-belum_dikembalikan
                                    @endif">
                                    {{ ucfirst($pengembalian->kondisi_alat) }}
                                </span>
                            </td>
                            <td>
                                @if($pengembalian->denda > 0)
                                    <span class="status-badge status-belum_dikembalikan">
                                        <i class="fas fa-exclamation-circle me-1"></i>Ada Denda
                                    </span>
                                @else
                                    <span class="status-badge status-dikembalikan">
                                        <i class="fas fa-check-circle me-1"></i>Tidak Ada Denda
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="12">Tidak ada data pengembalian</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    setTimeout(function() {
        if ($('#petugasLaporanTable').length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#petugasLaporanTable')) {
                try {
                    $('#petugasLaporanTable').DataTable({
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
                    console.log('✓ petugasLaporanTable DataTable initialized');
                } catch (e) {
                    console.error('Error:', e);
                }
            }
        }
    }, 300);
});
</script>
@endsection
