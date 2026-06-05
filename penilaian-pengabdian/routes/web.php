<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\KepalaController;
use App\Http\Controllers\SettingLembagaController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\LaporanFormatController;
use App\Http\Controllers\PenilaianMetodeController;

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($user->is_kepala) {
            return redirect()->route('kepala.dashboard');
        }
        return redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
});

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/activate', [AuthController::class, 'showActivate'])->name('activate');
    Route::post('/activate', [AuthController::class, 'activate'])->name('activate.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------------------------------------------------------------
// ADMIN routes
// ---------------------------------------------------------------
Route::middleware(['auth', 'role.admin'])->prefix('admin')->name('admin.')->group(function () {

    // Root redirect to dashboard
    Route::get('/', fn() => redirect()->route('admin.dashboard'));

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // User Account
    Route::resource('users', UserManagementController::class)->except(['show']);
    Route::post('users/import', [UserManagementController::class, 'import'])->name('users.import');

    // Karyawan
    Route::resource('karyawan', KaryawanController::class)->except(['show']);
    Route::get('karyawan/{karyawan}/profil-pdf', [KaryawanController::class, 'profilePdf'])
        ->name('karyawan.profile-pdf');
    Route::patch('karyawan/{karyawan}/toggle-status', [KaryawanController::class, 'toggleStatus'])
        ->name('karyawan.toggle-status');
    Route::post('karyawan/import', [KaryawanController::class, 'import'])->name('karyawan.import');

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
    Route::patch('pangkalan/{pangkalan}/toggle-status', [PangkalanController::class, 'toggleStatus'])
        ->name('pangkalan.toggle-status');

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
    Route::get('mutasi/pangkalan', [MutasiController::class, 'pangkalanIndex'])->name('mutasi.pangkalan');
    Route::post('mutasi/pangkalan/{karyawan}/assign', [MutasiController::class, 'assignPangkalan'])->name('mutasi.assign-pangkalan');
    Route::post('mutasi/pangkalan/bulk-assign', [MutasiController::class, 'bulkAssignPangkalan'])->name('mutasi.bulk-assign-pangkalan');

    // Transaksi
    Route::resource('transaksi', TransaksiController::class)->except(['show']);
    Route::delete('transaksi/{karyawan}/hapus-semua', [TransaksiController::class, 'destroyByKaryawan'])
        ->name('transaksi.hapus-karyawan');
    Route::post('transaksi/import', [TransaksiController::class, 'import'])
        ->name('transaksi.import');
    Route::post('transaksi/lock', [TransaksiController::class, 'lock'])
        ->name('transaksi.lock');
    Route::post('transaksi/unlock', [TransaksiController::class, 'unlock'])
        ->name('transaksi.unlock');
    Route::post('transaksi/batch-unlock', [TransaksiController::class, 'batchUnlock'])
        ->name('transaksi.batch-unlock');
    Route::get('transaksi/unlock-requests', [TransaksiController::class, 'unlockRequests'])
        ->name('transaksi.unlock-requests');
    Route::put('transaksi/unlock-requests/{unlockRequest}', [TransaksiController::class, 'reviewUnlockRequest'])
        ->name('transaksi.review-unlock-request');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/perorangan', [LaporanController::class, 'perorangan'])->name('laporan.perorangan');
    Route::get('/laporan/format', [LaporanFormatController::class, 'edit'])->name('laporan.format.edit');
    Route::put('/laporan/format', [LaporanFormatController::class, 'update'])->name('laporan.format.update');
    Route::get('/penilaian/metode', [PenilaianMetodeController::class, 'edit'])->name('penilaian-metode.edit');
    Route::put('/penilaian/metode', [PenilaianMetodeController::class, 'update'])->name('penilaian-metode.update');
    Route::get('/laporan/print', [LaporanController::class, 'printView'])->name('laporan.print');
    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    Route::get('/laporan/csv', [LaporanController::class, 'exportCsv'])->name('laporan.csv');
    Route::get('/laporan/perorangan-pdf', [LaporanController::class, 'peroranganPdf'])->name('laporan.perorangan-pdf');

    // Setting Lembaga
    Route::get('/setting-lembaga', [SettingLembagaController::class, 'edit'])->name('setting-lembaga.edit');
    Route::put('/setting-lembaga', [SettingLembagaController::class, 'update'])->name('setting-lembaga.update');
});

// ---------------------------------------------------------------
// USER routes
// ---------------------------------------------------------------
Route::middleware(['auth', 'role.user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/', fn() => redirect()->route('user.dashboard'));
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/laporan', [LaporanController::class, 'userIndex'])->name('laporan.index');
    Route::get('/laporan/perorangan', [LaporanController::class, 'userPerorangan'])->name('laporan.perorangan');
    Route::get('/laporan/print', [LaporanController::class, 'printView'])->name('laporan.print');
    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    Route::get('/laporan/csv', [LaporanController::class, 'exportCsv'])->name('laporan.csv');
    Route::get('/laporan/perorangan-pdf', [LaporanController::class, 'peroranganPdf'])->name('laporan.perorangan-pdf');
});

// ---------------------------------------------------------------
// KEPALA routes
// ---------------------------------------------------------------
Route::middleware(['auth', 'role.kepala'])->prefix('kepala')->name('kepala.')->group(function () {
    Route::get('/', fn() => redirect()->route('kepala.dashboard'));
    Route::get('/dashboard', [KepalaController::class, 'dashboard'])->name('dashboard');

    Route::get('/transaksi', [TransaksiController::class, 'kepalaIndex'])->name('transaksi.index');
    Route::get('/transaksi/create', [TransaksiController::class, 'kepalaCreate'])->name('transaksi.create');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::post('/transaksi/submit-final', [TransaksiController::class, 'submitFinal'])->name('transaksi.submit-final');
    Route::post('/transaksi/request-unlock', [TransaksiController::class, 'requestUnlock'])->name('transaksi.request-unlock');

    Route::get('/laporan', [LaporanController::class, 'kepalaIndex'])->name('laporan.index');
    Route::get('/laporan/perorangan', [LaporanController::class, 'kepalaPerorangan'])->name('laporan.perorangan');
    Route::get('/laporan/print', [LaporanController::class, 'printView'])->name('laporan.print');
    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    Route::get('/laporan/csv', [LaporanController::class, 'exportCsv'])->name('laporan.csv');
    Route::get('/laporan/perorangan-pdf', [LaporanController::class, 'peroranganPdf'])->name('laporan.perorangan-pdf');
});

Route::middleware('auth')->get('/help-qna', [HelpController::class, 'index'])->name('help.index');
Route::middleware('auth')->get('/help-qna/template/{entity}/{format}', [HelpController::class, 'downloadImportTemplate'])
    ->where('entity', 'user|karyawan')
    ->where('format', 'csv|xlsx')
    ->name('help.template.import');

