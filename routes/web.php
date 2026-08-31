<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;

// ============================================================
// ADMIN
// ============================================================

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SiswaController as AdminSiswaController;
use App\Http\Controllers\Admin\DudiController;
use App\Http\Controllers\Admin\PenempatanController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LogController;

// ============================================================
// GURU
// ============================================================

use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\SiswaBimbinganController;
use App\Http\Controllers\Guru\ValidasiController;
use App\Http\Controllers\Guru\KunjunganController;

// ============================================================
// SISWA
// ============================================================

use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\PengajuanController;
use App\Http\Controllers\Siswa\AbsensiController;
use App\Http\Controllers\Siswa\JurnalController;
use App\Http\Controllers\Siswa\ProfilController as SiswaProfilController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| SIMMAS — Sistem Informasi Manajemen Magang Siswa
|--------------------------------------------------------------------------
*/
                                                                                                                                                                            
Route::get('/cek-session', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'auth_check' => auth()->check(),
        'user' => auth()->user(),
        'session' => session()->all(),
    ]);
});

// ============================================================
// PUBLIK — LANDING PAGE
// ============================================================

Route::get('/', [LandingController::class, 'index'])
    ->name('landing');


// ============================================================
// PUBLIK — LOGIN & LUPA PASSWORD
// ============================================================

// Halaman Login
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

// Proses Login
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

// Lupa Password
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
    ->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->name('password.email');


// ============================================================
// LOGOUT
// ============================================================

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ============================================================
// ADMIN
// ============================================================

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // --------------------------------------------------------
        // OVERVIEW
        // --------------------------------------------------------

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/monitoring', [AdminDashboardController::class, 'monitoring'])
            ->name('monitoring');

        Route::get('/monitoring/{siswa}/detail', [AdminDashboardController::class, 'detail'])
            ->name('monitoring.detail');


        // --------------------------------------------------------
        // MASTER DATA — GURU
        // --------------------------------------------------------

        Route::resource('guru', GuruController::class)
            ->except(['show']);

        Route::patch('/guru/{guru}/status', [GuruController::class, 'updateStatus'])
            ->name('guru.status');


        // --------------------------------------------------------
        // MASTER DATA — SISWA
        // --------------------------------------------------------

        Route::resource('siswa', AdminSiswaController::class)
            ->except(['show']);

        Route::patch('/siswa/{siswa}/status', [AdminSiswaController::class, 'updateStatus'])
            ->name('siswa.status');

        Route::post('/siswa/{siswa}/plotting', [AdminSiswaController::class, 'plotting'])
            ->name('siswa.plotting');


        // --------------------------------------------------------
        // MASTER DATA — TEMPAT MAGANG / DUDI
        // --------------------------------------------------------

        /*
         * URL tetap menggunakan /dudi agar sesuai dengan
         * sistem dan sidebar.
         *
         * Data sebenarnya menggunakan model TempatMagang.
         */

        Route::resource('dudi', DudiController::class)
            ->except(['show']);

        Route::patch('/dudi/{dudi}/verifikasi', [DudiController::class, 'updateVerifikasi'])
            ->name('dudi.verifikasi');


        // --------------------------------------------------------
        // MANAJEMEN — PENEMPATAN MAGANG
        // --------------------------------------------------------

        /*
         * URL tetap /penempatan.
         * Model yang digunakan adalah PenempatanMagang.
         */

        Route::resource('penempatan', PenempatanController::class)
            ->except(['show']);

        Route::patch(
            '/penempatan/{penempatan}/sahkan',
            [PenempatanController::class, 'sahkan']
        )->name('penempatan.sahkan');

        Route::patch(
            '/penempatan/{penempatan}/batalkan',
            [PenempatanController::class, 'batalkan']
        )->name('penempatan.batalkan');


        Route::patch('/penempatan/{penempatan}/validasi-pengajuan', [PenempatanController::class, 'validasiPengajuan'])
            ->name('penempatan.validasi-pengajuan');

        Route::patch('/penempatan/{penempatan}/tolak-pengajuan', [PenempatanController::class, 'tolakPengajuan'])
            ->name('penempatan.tolak-pengajuan');


        // --------------------------------------------------------
        // SISTEM — SETTINGS
        // --------------------------------------------------------

        Route::get('/settings', [SettingController::class, 'edit'])
            ->name('settings.edit');

        Route::put('/settings', [SettingController::class, 'update'])
            ->name('settings.update');


        // --------------------------------------------------------
        // SISTEM — LOG
        // --------------------------------------------------------

        Route::get('/logs', [LogController::class, 'index'])
            ->name('logs');

        Route::delete('/logs', [LogController::class, 'clear'])
            ->name('logs.clear');
    });


// ============================================================
// GURU PEMBIMBING
// ============================================================

Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {

        // --------------------------------------------------------
        // OVERVIEW
        // --------------------------------------------------------

        Route::get('/dashboard', [GuruDashboardController::class, 'index'])
            ->name('dashboard');


        // --------------------------------------------------------
        // BIMBINGAN — SISWA
        // --------------------------------------------------------

        Route::get('/siswa', [SiswaBimbinganController::class, 'index'])
            ->name('siswa.index');

        Route::put(
            '/siswa/{siswa}/nilai',
            [SiswaBimbinganController::class, 'simpanNilai']
        )->name('siswa.nilai');


        // --------------------------------------------------------
        // BIMBINGAN — JURNAL
        // --------------------------------------------------------

        Route::get('/jurnal', [ValidasiController::class, 'index'])
            ->name('jurnal.index');

        Route::put(
            '/jurnal/{jurnal}/validasi',
            [ValidasiController::class, 'validasiJurnal']
        )->name('jurnal.validasi');


        // --------------------------------------------------------
        // BIMBINGAN — ABSENSI
        // --------------------------------------------------------

        Route::put(
            '/absensi/{absensi}/validasi',
            [ValidasiController::class, 'validasiAbsensi']
        )->name('absensi.validasi');


        // --------------------------------------------------------
        // BIMBINGAN — KUNJUNGAN LAPANGAN
        // --------------------------------------------------------

        Route::get('/kunjungan', [KunjunganController::class, 'index'])
            ->name('kunjungan.index');

        Route::post('/kunjungan', [KunjunganController::class, 'store'])
            ->name('kunjungan.store');

        Route::put(
            '/kunjungan/{kunjungan}',
            [KunjunganController::class, 'update']
        )->name('kunjungan.update');

        Route::delete(
            '/kunjungan/{kunjungan}',
            [KunjunganController::class, 'destroy']
        )->name('kunjungan.destroy');
    });


// ============================================================
// SISWA MAGANG
// ============================================================

Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        // --------------------------------------------------------
        // UTAMA — DASHBOARD
        // --------------------------------------------------------

        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])
            ->name('dashboard');


        // --------------------------------------------------------
        // UTAMA — PENGAJUAN MAGANG
        // --------------------------------------------------------

        Route::get('/pengajuan', [PengajuanController::class, 'index'])
            ->name('pengajuan.index');

        Route::post('/pengajuan', [PengajuanController::class, 'store'])
            ->name('pengajuan.store');


        // --------------------------------------------------------
        // KEGIATAN MAGANG — ABSENSI
        // --------------------------------------------------------

        Route::get('/absensi', [AbsensiController::class, 'index'])
            ->name('absensi.index');
        Route::get('/absensi-harian', [AbsensiController::class, 'index']);

        Route::post('/absensi', [AbsensiController::class, 'store'])
            ->name('absensi.store');
        Route::post('/absensi-harian', [AbsensiController::class, 'store']);


        // --------------------------------------------------------
        // KEGIATAN MAGANG — JURNAL
        // --------------------------------------------------------

        Route::get('/jurnal', [JurnalController::class, 'index'])
            ->name('jurnal.index');
        Route::get('/jurnal-kegiatan', [JurnalController::class, 'index']);

        Route::post('/jurnal', [JurnalController::class, 'store'])
            ->name('jurnal.store');
        Route::post('/jurnal-kegiatan', [JurnalController::class, 'store']);

        Route::put(
            '/jurnal/{jurnal}',
            [JurnalController::class, 'update']
        )->name('jurnal.update');

        Route::delete(
            '/jurnal/{jurnal}',
            [JurnalController::class, 'destroy']
        )->name('jurnal.destroy');


        // --------------------------------------------------------
        // PROFIL SAYA
        // --------------------------------------------------------

        Route::get('/profil', [SiswaProfilController::class, 'index'])
            ->name('profil.index');

        Route::post('/profil', [SiswaProfilController::class, 'update'])
            ->name('profil.update');
        Route::put('/profil', [SiswaProfilController::class, 'update']);
    });
