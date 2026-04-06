@extends('admin.layout')

@section('title', 'Dashboard Admin - Sistem Peminjaman Alat')

@section('content')
<div class="container-fluid px-0">

    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2>
                   <i class="fas fa-tachometer-alt text-orange me-2"></i>

                    Dashboard Admin
                </h2>
                <p class="text-muted mb-0">
                    Ringkasan sistem peminjaman alat
                </p>
            </div>
            <div class="text-end">
                <h5>
                    Halo, {{ Auth::user()->name ?? 'Admin' }}!
                </h5>
                <small class="text-muted">
                    Terakhir login: {{ \Carbon\Carbon::now()->format('j F Y') }}
                </small>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $totalUsers }}</h3>
                        <p class="mb-0">Total Peminjam</p>
                    </div>
                    <i class="fas fa-user fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $totalPetugas }}</h3>
                        <p class="mb-0">Total Petugas</p>
                    </div>
                    <i class="fas fa-user-tie fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $totalAlat }}</h3>
                        <p class="mb-0">Total Alat</p>
                    </div>
                    <i class="fas fa-boxes fa-2x text-orange"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>{{ $totalPeminjaman }}</h3>
                        <p class="mb-0">Total Peminjaman</p>
                    </div>
                    <i class="fas fa-history fa-2x text-orange"></i>
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
    </div>

</div>
@endsection