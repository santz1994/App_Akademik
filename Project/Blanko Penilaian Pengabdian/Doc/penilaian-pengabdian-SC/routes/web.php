<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\TahunPenilaianController;
use App\Http\Controllers\KategoriKinerjaController;
use App\Http\Controllers\KompetensiController;
use App\Http\Controllers\PerformanceRatingController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PangkalanController;

// Root redirect
Route::get('/', fn() => redirect()->route('login'));

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------------------------------------------------------------
// ADMIN routes
// ---------------------------------------------------------------
Route::middleware(['auth', 'role.admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // User Account
    Route::resource('users', UserManagementController::class)->except(['show']);

    // Karyawan
    Route::resource('karyawan', KaryawanController::class)->except(['show']);

    // Tahun Penilaian
    Route::resource('tahun-penilaian', TahunPenilaianController::class)
        ->parameters(['tahun-penilaian' => 'tahunPenilaian'])
        ->except(['show']);

    // Kategori Kinerja
    Route::resource('kategori-kinerja', KategoriKinerjaController::class)
        ->parameters(['kategori-kinerja' => 'kategoriKinerja'])
        ->except(['show']);

    // Pangkalan Job
    Route::resource('pangkalan', PangkalanController::class)->except(['show']);

    // Kompetensi
    Route::resource('kompetensi', KompetensiController::class)->except(['show']);

    // Performance Rating
    Route::resource('performance-rating', PerformanceRatingController::class)
        ->parameters(['performance-rating' => 'performanceRating'])
        ->except(['show']);

    // Mutasi
    Route::get('mutasi', [MutasiController::class, 'index'])->name('mutasi.index');
    Route::post('mutasi/{karyawan}/assign', [MutasiController::class, 'assign'])->name('mutasi.assign');
    Route::post('mutasi/bulk-assign', [MutasiController::class, 'bulkAssign'])->name('mutasi.bulk-assign');

    // Transaksi
    Route::resource('transaksi', TransaksiController::class)->except(['show']);
    Route::delete('transaksi/{karyawan}/hapus-semua', [TransaksiController::class, 'destroyByKaryawan'])
        ->name('transaksi.hapus-karyawan');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

// ---------------------------------------------------------------
// USER routes
// ---------------------------------------------------------------
Route::middleware(['auth', 'role.user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
});

