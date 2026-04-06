@extends('peminjam.layout')

@section('title', 'Pengaturan - Sistem Peminjaman Alat')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-cog text-primary me-2"></i>Pengaturan Akun</h2>
                <p class="text-muted mb-0">Atur preferensi dan pengaturan akun Anda</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-sliders-h me-2 text-primary"></i>Opsi Pengaturan</h5>
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action active">
                            <i class="fas fa-user-circle me-2"></i>Profil Umum
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-lock me-2"></i>Keamanan
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-bell me-2"></i>Notifikasi
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-envelope me-2"></i>Email & Kontak
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-history me-2"></i>Aktivitas Login
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-download me-2"></i>Ekspor Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card card-custom">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-user-circle me-2 text-primary"></i>Profil Umum</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Depan</label>
                                    <input type="text" class="form-control" value="{{ $user->nama ?? Auth::user()->name ?? 'Nama Peminjam' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Belakang</label>
                                    <input type="text" class="form-control" value="Doe">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="{{ $user->email ?? Auth::user()->email ?? 'email@example.com' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control" value="+62 812-3456-7890">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" value="1990-01-01">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select class="form-select">
                                        <option>Laki-laki</option>
                                        <option>Perempuan</option>
                                        <option>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" rows="3">Jl. Contoh Alamat No. 123, Kota Contoh</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Bio Singkat</label>
                            <textarea class="form-control" rows="3">Deskripsi singkat tentang diri Anda...</textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-custom">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card card-custom mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-language me-2 text-primary"></i>Preferensi Bahasa & Wilayah</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Bahasa</label>
                                <select class="form-select">
                                    <option>Bahasa Indonesia</option>
                                    <option>English</option>
                                    <option>Bahasa Jawa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Zona Waktu</label>
                                <select class="form-select">
                                    <option>Asia/Jakarta (GMT+7)</option>
                                    <option>Asia/Makassar (GMT+8)</option>
                                    <option>Asia/Jayapura (GMT+9)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="autoDetectLang" checked>
                        <label class="form-check-label" for="autoDetectLang">
                            Deteksi bahasa otomatis berdasarkan browser
                        </label>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-custom">Simpan Preferensi</button>
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