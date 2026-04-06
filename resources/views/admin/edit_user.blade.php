@extends('admin.layout')

@section('title', 'Edit User - Sistem Peminjaman Alat')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-user-edit text-orange me-2"></i>Edit User</h2>
                <p class="text-muted mb-0">Ubah informasi pengguna</p>
            </div>
        </div>
    </div>

    <!-- Edit User Form -->
    <div class="card card-custom">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-edit me-2 text-orange"></i>Form Edit User</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.updateUser', ['id' => $user->id_user]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="peminjam" {{ old('role', $user->role) == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="no_telp" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="password" class="form-label">Password (kosongkan jika tidak ingin diubah)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary me-md-2">Batal</a>
                    <button type="submit" class="btn btn-orange">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection