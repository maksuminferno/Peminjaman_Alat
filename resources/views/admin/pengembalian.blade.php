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
                                    @if($pengembalianItem->bukti_foto)
                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="showFoto('{{ asset($pengembalianItem->bukti_foto) }}', '{{ $detail->alat->nama_alat }}')" data-bs-toggle="modal" data-bs-target="#fotoModal">
                                            <i class="fas fa-eye me-1"></i>Lihat
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.deletePengembalian', ['id' => $pengembalianItem->id_pengembalian]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengembalian ini?')">
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
                            <td class="text-center">Tidak ada data pengembalian</td>
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
</div>

<script>
    function showFoto(fotoUrl, alatName) {
        document.getElementById('fotoBukti').src = fotoUrl;
        document.getElementById('fotoAlatName').textContent = alatName || 'N/A';
        document.getElementById('downloadFoto').href = fotoUrl;
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