<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes - with admin role middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('storeUser');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('editUser');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('updateUser');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('deleteUser');
    Route::get('/alat', [AdminController::class, 'alat'])->name('alat');
    Route::post('/alat', [AdminController::class, 'storeAlat'])->name('storeAlat');
    Route::delete('/alat/bulk-delete', [AdminController::class, 'bulkDeleteAlat'])->name('bulkDeleteAlat');
    Route::put('/alat/{id}', [AdminController::class, 'updateAlat'])->name('updateAlat');
    Route::delete('/alat/{id}', [AdminController::class, 'deleteAlat'])->name('deleteAlat');
    Route::get('/alat/{id}/edit', [AdminController::class, 'editAlatDetails'])->name('alat.edit.details');
    Route::get('/alat-by-kategori/{id_kategori}', [AdminController::class, 'getAlatByKategori'])->name('alat.by.kategori');
    Route::get('/kategori', [AdminController::class, 'kategori'])->name('kategori');
    Route::post('/kategori', [AdminController::class, 'storeKategori'])->name('storeKategori');
    Route::put('/kategori/{id}', [AdminController::class, 'updateKategori'])->name('updateKategori');
    Route::delete('/kategori/{id}', [AdminController::class, 'deleteKategori'])->name('deleteKategori');
    Route::get('/kategori/{id}/edit', [AdminController::class, 'editKategoriDetails'])->name('kategori.edit.details');
    Route::get('/peminjaman', [AdminController::class, 'peminjaman'])->name('peminjaman');
    Route::post('/peminjaman', [AdminController::class, 'storePeminjaman'])->name('storePeminjaman');
    Route::put('/peminjaman/{id}', [AdminController::class, 'updatePeminjaman'])->name('updatePeminjaman');
    Route::delete('/peminjaman/{id}', [AdminController::class, 'deletePeminjaman'])->name('deletePeminjaman');
    Route::get('/pengembalian', [AdminController::class, 'pengembalian'])->name('pengembalian');
    Route::put('/pengembalian/{id}', [AdminController::class, 'updatePengembalian'])->name('updatePengembalian');
    Route::post('/pengembalian/{id}/verifikasi', [AdminController::class, 'verifikasiPengembalian'])->name('verifikasiPengembalian');
    Route::post('/pengembalian/{id}/tolak', [AdminController::class, 'tolakPengembalian'])->name('tolakPengembalian');
    Route::delete('/pengembalian/{id}', [AdminController::class, 'deletePengembalian'])->name('deletePengembalian');
    Route::get('/log-aktivitas', [AdminController::class, 'logAktivitas'])->name('log_aktivitas');
    Route::get('/api/peminjaman/riwayat/{username}', [AdminController::class, 'getRiwayatPeminjaman'])->name('riwayat.peminjaman');
});

// Peminjam routes - with borrower role middleware
Route::middleware(['auth', 'peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
    Route::get('/dashboard', [PeminjamanController::class, 'index'])->name('dashboard');
    Route::get('/profile', [PeminjamanController::class, 'profile'])->name('profile');
    Route::get('/settings', [PeminjamanController::class, 'settings'])->name('settings');
    Route::get('/history', [PeminjamanController::class, 'history'])->name('history');
    Route::get('/struk/{id}', [PeminjamanController::class, 'struk'])->name('struk');
    Route::get('/', [PeminjamanController::class, 'tools'])->name('tools');
    Route::get('/borrow', [PeminjamanController::class, 'borrow'])->name('borrow');
    Route::get('/ajukan-peminjaman/{id?}', [PeminjamanController::class, 'showBorrowForm'])->name('ajukan.peminjaman');
    Route::post('/store-borrow', [PeminjamanController::class, 'storeBorrow'])->name('storeBorrow');
    Route::post('/store-borrow-multiple', [PeminjamanController::class, 'storeBorrowMultiple'])->name('storeBorrowMultiple');
    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalian'])->name('pengembalian');
    Route::post('/store-return', [PeminjamanController::class, 'storeReturn'])->name('storeReturn');
});

// Petugas routes - with petugas role middleware
Route::middleware(['auth', 'petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/', [\App\Http\Controllers\PetugasController::class, 'dashboard'])->name('dashboard');
    Route::get('/peminjaman', [\App\Http\Controllers\PetugasController::class, 'peminjaman'])->name('peminjaman');
    Route::get('/api/peminjaman/{id}/details', [\App\Http\Controllers\PetugasController::class, 'getPeminjamanDetails'])->name('peminjaman.details');
    Route::post('/peminjaman/{id}/approve', [\App\Http\Controllers\PetugasController::class, 'approvePeminjaman'])->name('peminjaman.approve');
    Route::post('/peminjaman/{id}/reject', [\App\Http\Controllers\PetugasController::class, 'rejectPeminjaman'])->name('peminjaman.reject');
    Route::delete('/peminjaman/{id}', [\App\Http\Controllers\PetugasController::class, 'deletePeminjaman'])->name('deletePeminjaman');
    Route::get('/pengembalian', [\App\Http\Controllers\PetugasController::class, 'pengembalian'])->name('pengembalian');
    Route::post('/pengembalian', [\App\Http\Controllers\PetugasController::class, 'storePengembalian'])->name('pengembalian.store');
    Route::delete('/pengembalian/{id}', [\App\Http\Controllers\PetugasController::class, 'deletePengembalian'])->name('deletePengembalian');
    Route::post('/pengembalian/{id}/confirm-returned', [\App\Http\Controllers\PetugasController::class, 'confirmReturned'])->name('confirmReturned');
    Route::post('/pengembalian/{id}/tolak-verifikasi', [\App\Http\Controllers\PetugasController::class, 'tolakVerifikasi'])->name('tolakVerifikasi');
    Route::get('/api/pengembalian/{id}/details', [\App\Http\Controllers\PetugasController::class, 'getPengembalianDetails'])->name('api.pengembalian.details');
    Route::get('/laporan', [\App\Http\Controllers\PetugasController::class, 'laporan'])->name('laporan');
    Route::get('/export-pengembalian', [\App\Http\Controllers\PetugasController::class, 'exportPengembalian'])->name('export-pengembalian');
});