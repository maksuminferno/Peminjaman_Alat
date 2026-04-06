@extends('petugas.layout')

@section('title', 'Dashboard Petugas - Sistem Peminjaman Alat')

@section('content')
<div class="container-fluid px-0">

    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2>
                   <i class="fas fa-tachometer-alt text-orange me-2"></i>
                    Dashboard Petugas
                </h2>
                <p class="text-muted mb-0">
                    Ringkasan aktivitas sistem peminjaman alat
                </p>
            </div>
            <div class="text-end">
                <h5>
                    Halo, {{ Auth::user()->name ?? 'Petugas' }}!
                </h5>
                <small class="text-muted">
                    Terakhir login: {{ \Carbon\Carbon::now()->format('j F Y') }}
                </small>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row">
        @if($totalPengajuan > 0)
        <div class="col-md-12 mb-4">
            <div class="card card-custom border-warning" style="border: 2px solid #ffc107;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-warning">
                                <i class="fas fa-bell me-2"></i>Pengajuan Peminjaman Tersedia!
                            </h4>
                            <p class="mb-0">
                                Terdapat <strong>{{ $totalPengajuan }} pengajuan peminjaman</strong> yang menunggu persetujuan Anda.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('petugas.peminjaman') }}" class="btn btn-warning">
                                <i class="fas fa-arrow-right me-2"></i>Lihat Pengajuan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    
        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $totalAktif }}</h3>
                        <p class="mb-0">Peminjaman Aktif</p>
                    </div>
                    <i class="fas fa-clock fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $totalPengembalian }}</h3>
                        <p class="mb-0">Total Pengembalian</p>
                    </div>
                    <i class="fas fa-calendar-check fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $totalPengajuan }}</h3>
                        <p class="mb-0">Peminjaman Pending</p>
                    </div>
                    <i class="fas fa-hourglass-half fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $totalKeterlambatan }}</h3>
                        <p class="mb-0">Keterlambatan</p>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
                        <p class="mb-0">Total Denda</p>
                        <small class="text-muted">Terlambat: Rp {{ number_format($totalDendaKeterlambatan ?? 0, 0, ',', '.') }}</small><br>
                        <small class="text-muted">Kerusakan: Rp {{ number_format($totalDendaKerusakan ?? 0, 0, ',', '.') }}</small>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x text-orange"></i>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection