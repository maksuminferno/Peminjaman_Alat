@extends('peminjam.layout')

@section('title', 'Profil Saya - Sistem Peminjaman Alat')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-user text-primary me-2"></i>Profil Saya</h2>
                <p class="text-muted mb-0">Kelola informasi profil pribadi Anda</p>
            </div>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="row">
        <div class="col-xl-4">
            <div class="card card-custom text-center">
                <div class="card-body p-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama ?? Auth::user()->name ?? 'User') }}&background=3498db&color=fff" alt="Profile Picture" class="rounded-circle mb-3" width="120" height="120">
                    <h4>{{ $user->nama ?? Auth::user()->name ?? 'Nama Peminjam' }}</h4>
                    <p class="text-muted mb-1">{{ $user->email ?? Auth::user()->email ?? 'Email tidak tersedia' }}</p>
                    <p class="text-muted mb-0">Peminjam</p>
                    
                    <div class="mt-4">
                        <button class="btn btn-custom"><i class="fas fa-camera me-2"></i>Ganti Foto</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-8">
            <div class="card card-custom">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-edit me-2 text-primary"></i>Edit Profil</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->name ?? 'Nama Peminjam' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="{{ Auth::user()->email ?? 'email@example.com' }}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control" value="+62 812-3456-7890">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Bergabung</label>
                                    <input type="text" class="form-control" value="01 Januari 2024" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control" rows="3">Jl. Contoh Alamat No. 123, Kota Contoh</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Catatan Tambahan</label>
                                    <textarea class="form-control" rows="3">-</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-custom">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Security Settings -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-lock me-2 text-primary"></i>Pengaturan Keamanan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-key fa-2x text-warning mb-3"></i>
                                    <h6 class="card-title">Ganti Kata Sandi</h6>
                                    <p class="card-text text-muted">Perbarui kata sandi secara berkala untuk keamanan akun</p>
                                    <button class="btn btn-outline-primary">Ubah Kata Sandi</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-shield-alt fa-2x text-success mb-3"></i>
                                    <h6 class="card-title">Aktivitas Login</h6>
                                    <p class="card-text text-muted">Lihat riwayat aktivitas login terakhir Anda</p>
                                    <button class="btn btn-outline-primary">Lihat Aktivitas</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Additional scripts can be added here if needed
</script>
@endsection