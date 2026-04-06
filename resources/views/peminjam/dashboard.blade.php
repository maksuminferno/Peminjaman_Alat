@extends('peminjam.layout')

@section('title', 'Dashboard Peminjam - Sistem Peminjaman Alat')

@section('content')

<div class="container-fluid px-0">

    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2>
                   <i class="fas fa-tachometer-alt text-orange me-2"></i>

                    Dashboard Peminjam
                </h2>
                <p class="text-muted mb-0">
                    Selamat datang di sistem peminjaman alat Anda
                </p>
            </div>
            <div class="text-end">
                <h5>
                    Halo, {{ $user->nama ?? Auth::user()->name ?? 'Peminjam' }}!
                </h5>
                <small class="text-muted">
                    Terakhir login: {{ \Carbon\Carbon::now()->format('j F Y') }}
                </small>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $totalPeminjaman }}</h3>
                        <p class="mb-0">Total Pinjaman</p>
                    </div>
                    <i class="fas fa-history fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $belumDikembalikan }}</h3>
                        <p class="mb-0">Belum Dikembalikan</p>
                    </div>
                    <i class="fas fa-exclamation-circle fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $sudahDikembalikan }}</h3>
                        <p class="mb-0">Sudah Dikembalikan</p>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $terlambat }}</h3>
                        <p class="mb-0">Terlambat</p>
                    </div>
                    <i class="fas fa-clock fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-12 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>Rp {{ number_format($totalDenda ?? 0, 0, ',', '.') }}</h3>
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
