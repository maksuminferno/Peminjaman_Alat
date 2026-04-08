@extends('peminjam.layout')

@section('title', 'Ajukan Peminjaman Alat - Sistem Peminjaman Alat')

@section('content')
<div class="container-fluid">
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-hand-point-right text-orange me-2"></i>Ajukan Peminjaman Alat</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Borrowing Form Column -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ isset($alat) ? route('peminjam.storeBorrow') : route('peminjam.storeBorrowMultiple') }}" method="POST">
                        @csrf
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Debug info - remove after testing --}}
                        @if(session('debug_form_data'))
                            <div class="alert alert-info">
                                <strong>Debug Form Data:</strong>
                                <pre>{{ json_encode(session('debug_form_data'), JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif

                        <!-- Multiple Alat Selection (from tools page) -->
                        @if(isset($groupedAlatList) && $groupedAlatList->count() > 0)
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-shopping-cart text-orange me-2"></i>Alat yang Dipilih</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Alat</th>
                                            <th>Kategori</th>
                                            <th>Stok Tersedia</th>
                                            <th>Pilih Kode Barang</th>
                                            <th>Lokasi</th>
                                            <th>Jumlah Pinjam</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($groupedAlatList as $namaAlat => $tools)
                                            @php
                                                $totalStock = $tools->alatTersedia->sum('stok');
                                                $firstTool = $tools->firstTool ?? $tools->first();
                                                $toolIds = $tools->alatTersedia->pluck('id_alat')->toArray();
                                                $rowIndex = $loop->index;
                                            @endphp
                                        <tr>
                                            <td>{{ $namaAlat }}</td>
                                            <td>{{ $firstTool->kategori->nama_kategori }}</td>
                                            <td>{{ $totalStock }}</td>
                                            <td>
                                                <select class="form-select kode_barang_select" name="kode_barang[{{ $rowIndex }}]"
                                                        data-row-index="{{ $rowIndex }}"
                                                        required>
                                                    <option value="">-- Pilih Kode Barang --</option>
                                                    @foreach($tools->alatTersedia ?? [] as $alatItem)
                                                    <option value="{{ $alatItem->kode_barang }}"
                                                            data-id-alat="{{ $alatItem->id_alat }}"
                                                            data-lokasi="{{ $alatItem->lokasi }}"
                                                            data-stok="{{ $alatItem->stok }}">
                                                        {{ $alatItem->kode_barang }} - {{ $alatItem->lokasi }} (Stok: {{ $alatItem->stok }}, Kondisi: {{ $alatItem->kondisi ?? 'N/A' }})
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="alat_id[{{ $rowIndex }}]" class="alat_id_hidden" value="">
                                            </td>
                                            <td>
                                                <span class="lokasi_display_{{ $rowIndex }} text-muted">-</span>
                                            </td>
                                            <td>
                                                <input type="number" name="alat[{{ $rowIndex }}]"
                                                    class="form-control jumlah_input"
                                                    data-row-index="{{ $rowIndex }}"
                                                    min="1" max="{{ $totalStock }}" value="1"
                                                    style="width: 100px;" required>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <!-- Single Alat (from "Pinjam" button) -->
                        @if(isset($alat))
                        <div class="mb-3">
                            <label for="nama_alat" class="form-label">Nama Alat</label>
                            <input type="text" class="form-control" value="{{ $alat->nama_alat }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" class="form-control" value="{{ $alat->kategori->nama_kategori }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="kode_barang" class="form-label">Pilih Kode Barang</label>
                            <select class="form-select" id="kode_barang" name="kode_barang" required>
                                <option value="">-- Pilih Kode Barang --</option>
                                @foreach($alat->alatTersedia ?? [] as $alatItem)
                                <option value="{{ $alatItem->kode_barang }}"
                                        data-lokasi="{{ $alatItem->lokasi }}"
                                        data-stok="{{ $alatItem->stok }}"
                                        data-id-alat="{{ $alatItem->id_alat }}">
                                    {{ $alatItem->kode_barang }} - {{ $alatItem->lokasi }} (Stok: {{ $alatItem->stok }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi_display" readonly placeholder="Pilih kode barang untuk melihat lokasi">
                        </div>

                        <!-- Hidden field for the actual alat ID (will be set when kode_barang is selected) -->
                        <input type="hidden" name="id_alat" id="id_alat_hidden" value="">
                        <input type="hidden" name="jumlah" id="jumlah_hidden" value="1">
                        @endif

                        <!-- Hidden field for tanggal_pinjam (will be set automatically to today) -->
                        <input type="hidden" name="tanggal_pinjam" value="{{ date('Y-m-d') }}">

                        <!-- User selects expected return date -->
                        <div class="mb-3">
                            <label for="tanggal_kembali_rencana" class="form-label">Tanggal Kembali Rencana</label>
                            <input type="date" name="tanggal_kembali_rencana" class="form-control" id="tanggal_kembali_rencana" required>
                            <div class="form-text">Pilih tanggal rencana pengembalian alat (minimal 1 hari dari hari ini)</div>
                        </div>

                        <!-- Alasan Peminjaman -->
                        <div class="mb-3">
                            <label for="alasan" class="form-label">Alasan Peminjaman <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" 
                                      placeholder="Jelaskan alasan Anda meminjam alat ini (contoh: Untuk keperluan praktikum, penelitian, dll.)" required></textarea>
                            <div class="form-text">Mohon jelaskan alasan peminjaman secara detail dan jelas</div>
                        </div>

                        <!-- Hidden fields untuk data yang diisi otomatis sistem -->
                        <input type="hidden" name="id_user" value="{{ Auth::id() }}">

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('peminjam.tools') }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-orange">Ajukan Peminjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- History Column -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history me-2 text-orange"></i>Riwayat Terbaru</h5>
                </div>
                <div class="card-body">
                    @php
                        $user = Auth::user();
                        $userId = $user ? $user->id_user : null;
                        $recentPeminjaman = \App\Models\Peminjaman::with(['detailPeminjaman.alat', 'pengembalian'])
                            ->where('id_user', $userId)
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                        
                        // Debug info - uncomment for troubleshooting
                        // dump('Current User ID: ' . $userId);
                        // dump('Number of peminjaman found: ' . $recentPeminjaman->count());
                        // foreach($recentPeminjaman as $p) {
                        //     dump('Peminjaman ID: ' . $p->id_peminjaman . ', Status: ' . $p->status);
                        // }
                        
                        // Temporary debug - remove after testing
                        $allPeminjaman = \App\Models\Peminjaman::where('id_user', $userId)->get();
                        // dump('All peminjaman count: ' . $allPeminjaman->count());
                        // foreach($allPeminjaman as $p) {
                        //     dump('Peminjaman ID: ' . $p->id_peminjaman . ', Status: ' . $p->status . ', Created: ' . $p->created_at);
                        // }
                    @endphp

                
                    </div>

                    @if($recentPeminjaman->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Alat</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPeminjaman as $peminjaman)
                                        @foreach($peminjaman->detailPeminjaman as $detail)
                                        <tr>
                                            <td class="small">{{ Str::limit($detail->alat->nama_alat, 20) }}</td>
                                            <td>
                                                <span class="status-badge
                                                    @if($peminjaman->status == 'dipinjam') status-dipinjam
                                                    @elseif($peminjaman->status == 'dikembalikan') status-dikembalikan
                                                    @elseif($peminjaman->status == 'terlambat') status-belum_dikembalikan
                                                    @elseif($peminjaman->status == 'menunggu persetujuan') status-belum_dikembalikan
                                                    @elseif($peminjaman->status == 'ditolak') status-ditolak
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('peminjam.history') }}" class="btn btn-outline-orange btn-sm">Lihat Semua</a>
                        </div>
                    @else
                        <div class="text-center">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Belum ada riwayat peminjaman</p>
                            <small class="text-muted">User ID: {{ Auth::id() }}</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize return date
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date for return date (tomorrow)
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const minReturnDate = tomorrow.toISOString().split('T')[0];
        document.getElementById('tanggal_kembali_rencana').min = minReturnDate;

        // Handle single alat kode_barang change
        const kodeBarangSelect = document.getElementById('kode_barang');
        const lokasiDisplay = document.getElementById('lokasi_display');
        const idAlatHidden = document.getElementById('id_alat_hidden');

        if (kodeBarangSelect && lokasiDisplay) {
            kodeBarangSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const lokasi = selectedOption.getAttribute('data-lokasi');
                const idAlat = selectedOption.getAttribute('data-id-alat');
                
                lokasiDisplay.value = lokasi || '-';
                
                // Set the correct id_alat based on selected kode_barang
                if (idAlatHidden && idAlat) {
                    idAlatHidden.value = idAlat;
                }
            });
        }

        // Handle multiple alat kode_barang change
        document.querySelectorAll('.kode_barang_select').forEach(function(select) {
            select.addEventListener('change', function() {
                const rowIndex = this.getAttribute('data-row-index');
                const selectedOption = this.options[this.selectedIndex];
                const lokasi = selectedOption.getAttribute('data-lokasi');
                const stok = selectedOption.getAttribute('data-stok');
                const idAlat = selectedOption.getAttribute('data-id-alat');

                // Update lokasi display
                const lokasiDisplay = document.querySelector('.lokasi_display_' + rowIndex);
                if (lokasiDisplay) {
                    lokasiDisplay.textContent = lokasi || '-';
                }

                // Update the hidden alat_id field with the selected id_alat
                const alatIdHidden = document.querySelector('.alat_id_hidden[data-row-index="' + rowIndex + '"]') || 
                                     document.querySelector('input[name="alat_id[' + rowIndex + ']"]');
                if (alatIdHidden && idAlat) {
                    alatIdHidden.value = idAlat;
                }

                // Update max value for jumlah input
                const jumlahInput = document.querySelector('.jumlah_input[data-row-index="' + rowIndex + '"]');
                if (jumlahInput) {
                    jumlahInput.max = stok || 999;

                    // Reset value if it exceeds new max
                    if (parseInt(jumlahInput.value) > parseInt(stok)) {
                        jumlahInput.value = 1;
                    }
                }
            });
        });
    });
</script>
@endsection