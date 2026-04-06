@extends('peminjam.layout')

@section('title', 'Ajukan Peminjaman Alat - Sistem Peminjaman Alat')

@section('content')
<div class="container-fluid">
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-hand-point-right text-primary me-2"></i>Ajukan Peminjaman Alat</h2>
            </div>
        </div>
    </div>

    @if(isset($alat))
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('peminjam.storeBorrow') }}" method="POST">
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

                        <!-- Informasi Alat (READ ONLY) -->
                        <div class="mb-3">
                            <label for="nama_alat" class="form-label">Nama Alat</label>
                            <input type="text" class="form-control" value="{{ $alat->nama_alat }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" class="form-control" value="{{ $alat->kategori->nama_kategori }}" readonly>
                        </div>

                        <!-- Input yang boleh diisi peminjam -->
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah Pinjam</label>
                            <input type="number" name="jumlah" class="form-control" id="jumlah" min="1" value="1" required>
                            <div class="form-text">Jumlah peminjaman yang diinginkan</div>
                        </div>

                        <!-- Hidden field for tanggal_pinjam (will be set automatically to today) -->
                        <input type="hidden" name="tanggal_pinjam" value="{{ date('Y-m-d') }}">

                        <!-- User selects expected return date -->
                        <div class="mb-3">
                            <label for="tanggal_kembali_rencana" class="form-label">Tanggal Kembali Rencana</label>
                            <input type="date" name="tanggal_kembali_rencana" class="form-control" id="tanggal_kembali_rencana" required>
                            <div class="form-text">Pilih tanggal rencana pengembalian alat (minimal 1 hari dari hari ini)</div>
                        </div>

                        <!-- Hidden fields untuk data yang diisi otomatis sistem -->
                        <input type="hidden" name="id_alat" value="{{ $alat->id_alat }}">
                        <input type="hidden" name="id_user" value="{{ Auth::id() }}">

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('peminjam.tools') }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="text-muted">Silakan pilih alat terlebih dahulu</h5>
                    <p class="mb-3">Untuk mengajukan peminjaman, silakan kembali ke halaman daftar alat dan klik tombol "Pinjam" pada alat yang ingin dipinjam.</p>
                    <a href="{{ route('peminjam.tools') }}" class="btn btn-primary">Ke Daftar Alat</a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    // Validate amount and set minimum return date
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date for return date (tomorrow)
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const minReturnDate = tomorrow.toISOString().split('T')[0];
        document.getElementById('tanggal_kembali_rencana').min = minReturnDate;

        // Validate amount is at least 1
        document.getElementById('jumlah').addEventListener('input', function() {
            const enteredAmount = parseInt(this.value);

            if (enteredAmount < 1) {
                this.value = 1;
            }
        });
    });
</script>
@endsection