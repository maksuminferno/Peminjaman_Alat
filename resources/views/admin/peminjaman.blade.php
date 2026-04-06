@extends('admin.layout')

@section('title', 'Daftar Peminjaman - Sistem Peminjaman Alat')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-history text-orange me-2"></i>Daftar Peminjaman</h2>
                <p class="text-muted mb-0">Semua data peminjaman alat</p>
            </div>
        </div>
    </div>


    <!-- Peminjaman Table -->
    <div class="card card-custom table-custom">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-orange"></i>Daftar Peminjaman</h5>
          
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="adminPeminjamanTable">
                    <thead>
                        <tr>
                            <th>Nama User</th>
                            <th>Nama Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Disetujui Oleh</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $peminjamanItem)
                            @foreach($peminjamanItem->detailPeminjaman as $detail)
                            <tr>
                                <td>{{ $peminjamanItem->user->nama ?? 'N/A' }}</td>
                                <td>{{ $detail->alat->nama_alat }}</td>
                                <td>{{ \Carbon\Carbon::parse($peminjamanItem->tanggal_pinjam)->format('d M Y') }}</td>
                                <td>
                                    @if($peminjamanItem->disetujui_oleh)
                                        <span class="text-orange">
                                            <i class="fas fa-user-check me-1"></i>{{ $peminjamanItem->petugas->nama ?? 'N/A' }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge
                                        @if($peminjamanItem->status == 'dipinjam') status-dipinjam
                                        @elseif($peminjamanItem->status == 'dikembalikan') status-dikembalikan
                                        @elseif($peminjamanItem->status == 'terlambat') status-belum_dikembalikan
                                        @elseif($peminjamanItem->status == 'menunggu persetujuan') status-belum_dikembalikan
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $peminjamanItem->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.deletePeminjaman', ['id' => $peminjamanItem->id_peminjaman]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peminjaman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @empty
                        <tr>
                            <td class="text-center">Tidak ada data peminjaman</td>
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
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    setTimeout(function() {
        if ($('#adminPeminjamanTable').length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#adminPeminjamanTable')) {
                try {
                    $('#adminPeminjamanTable').DataTable({
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
                    console.log('✓ adminPeminjamanTable DataTable initialized');
                } catch (e) {
                    console.error('Error:', e);
                }
            }
        }
    }, 300);
});
</script>
@endsection