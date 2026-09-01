<?php

use Illuminate\Support\Facades\Route;

// Import Controller Autentikasi dan Halaman Utama
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;

// ============================================================
// IMPORT CONTROLLER ROLE: ADMIN
// ============================================================
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SiswaController as AdminSiswaController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\DudiController;
use App\Http\Controllers\Admin\PenempatanController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\SearchController as AdminSearchController;

// ============================================================
// IMPORT CONTROLLER ROLE: GURU PEMBIMBING
// ============================================================
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\SiswaBimbinganController;
use App\Http\Controllers\Guru\ValidasiController;
use App\Http\Controllers\Guru\KunjunganController;

// ============================================================
// IMPORT CONTROLLER ROLE: SISWA MAGANG
// ============================================================
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\PengajuanController;
use App\Http\Controllers\Siswa\AbsensiController;
use App\Http\Controllers\Siswa\JurnalController;
use App\Http\Controllers\Siswa\ProfilController as SiswaProfilController;
use App\Http\Controllers\Siswa\SearchController as SiswaSearchController;

/*
|--------------------------------------------------------------------------
| Web Routes (Daftar Rute Web Aplikasi SIMMAS)
|--------------------------------------------------------------------------
| File ini mengatur seluruh URL endpoint, proteksi middleware auth/role,
| dan pemanggilan method controller yang bersangkutan.
|--------------------------------------------------------------------------
*/

// Endpoint uji coba untuk memeriksa data session dan user login aktif
Route::get('/cek-session', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'auth_check' => auth()->check(),
        'user' => auth()->user(),
        'session' => session()->all(),
    ]);
});

// ============================================================
// 1. PUBLIK — LANDING PAGE (HALAMAN UTAMA)
// ============================================================
// Menampilkan landing page publik aplikasi SIMMAS
Route::get('/', [LandingController::class, 'index'])
    ->name('landing');


// ============================================================
// 2. PUBLIK — AUTENTIKASI (LOGIN & LUPA PASSWORD)
// ============================================================

// Menampilkan formulir login
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

// Memproses input kredensial login (Email/Username & Password)
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

// Menampilkan formulir permintaan reset/lupa password
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
    ->name('password.request');

// Mengirim email link reset password ke user
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->name('password.email');


// ============================================================
// 3. LOGOUT (KELUAR DARI SISTEM)
// ============================================================
// Memproses logout, menghapus session user (Wajib Login)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ============================================================
// 4. GROUP ROUTE: ADMIN (Wajib Login + Role Admin)
// ============================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')           // Awalan URL: /admin/...
    ->name('admin.')            // Awalan nama route: admin....
    ->group(function () {

        // --------------------------------------------------------
        // DASHBOARD & MONITORING
        // --------------------------------------------------------
        // Halaman Dashboard utama admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Halaman monitoring status magang seluruh siswa
        Route::get('/monitoring', [AdminDashboardController::class, 'monitoring'])
            ->name('monitoring');

        // Detail data monitoring siswa tertentu
        Route::get('/monitoring/{siswa}/detail', [AdminDashboardController::class, 'detail'])
            ->name('monitoring.detail');


        // --------------------------------------------------------
        // MASTER DATA — GURU
        // --------------------------------------------------------
        // CRUD Data Guru (Index, Create, Store, Edit, Update, Destroy)
        Route::resource('guru', GuruController::class)
            ->except(['show']);

        // Mengubah status aktif / non-aktif akun guru
        Route::patch('/guru/{guru}/status', [GuruController::class, 'updateStatus'])
            ->name('guru.status');


        // --------------------------------------------------------
        // MASTER DATA — SISWA
        // --------------------------------------------------------
        // CRUD Data Siswa
        Route::resource('siswa', AdminSiswaController::class)
            ->except(['show']);

        // Mengubah status aktif / non-aktif akun siswa
        Route::patch('/siswa/{siswa}/status', [AdminSiswaController::class, 'updateStatus'])
            ->name('siswa.status');

        // Melakukan plotting / penetapan guru pembimbing ke siswa
        Route::post('/siswa/{siswa}/plotting', [AdminSiswaController::class, 'plotting'])
            ->name('siswa.plotting');


        // --------------------------------------------------------
        // MASTER DATA — KELAS
        // --------------------------------------------------------
        // CRUD Data Kelas
        Route::resource('kelas', KelasController::class)
            ->parameters(['kelas' => 'kelas'])
            ->except(['show']);

        // Mengubah status aktif / non-aktif kelas
        Route::patch('/kelas/{kelas}/status', [KelasController::class, 'updateStatus'])
            ->name('kelas.status');


        // --------------------------------------------------------
        // MASTER DATA — JURUSAN / PROGRAM KEAHLIAN
        // --------------------------------------------------------
        // CRUD Data Jurusan / Program Keahlian
        Route::resource('jurusan', JurusanController::class)
            ->parameters(['jurusan' => 'jurusan'])
            ->except(['show']);

        // Mengubah status aktif / non-aktif jurusan
        Route::patch('/jurusan/{jurusan}/status', [JurusanController::class, 'updateStatus'])
            ->name('jurusan.status');


        // --------------------------------------------------------
        // MASTER DATA — TEMPAT MAGANG / DUDI
        // --------------------------------------------------------
        // CRUD Mitra Industri / Tempat Magang
        Route::resource('dudi', DudiController::class)
            ->except(['show']);

        // Verifikasi kelayakan mitra industri DUDI
        Route::patch('/dudi/{dudi}/verifikasi', [DudiController::class, 'updateVerifikasi'])
            ->name('dudi.verifikasi');


        // --------------------------------------------------------
        // MANAJEMEN — PENEMPATAN MAGANG
        // --------------------------------------------------------
        // CRUD Penempatan Magang Siswa
        Route::resource('penempatan', PenempatanController::class)
            ->except(['show']);

        // Mengesahkan penempatan magang siswa
        Route::patch(
            '/penempatan/{penempatan}/sahkan',
            [PenempatanController::class, 'sahkan']
        )->name('penempatan.sahkan');

        // Membatalkan penempatan magang siswa
        Route::patch(
            '/penempatan/{penempatan}/batalkan',
            [PenempatanController::class, 'batalkan']
        )->name('penempatan.batalkan');

        // Memvalidasi pengajuan mandiri yang diajukan siswa
        Route::patch('/penempatan/{penempatan}/validasi-pengajuan', [PenempatanController::class, 'validasiPengajuan'])
            ->name('penempatan.validasi-pengajuan');

        // Menolak permohonan pengajuan magang dari siswa beserta alasannya
        Route::patch('/penempatan/{penempatan}/tolak-pengajuan', [PenempatanController::class, 'tolakPengajuan'])
            ->name('penempatan.tolak-pengajuan');


        // --------------------------------------------------------
        // SISTEM — PENGATURAN (SETTINGS)
        // --------------------------------------------------------
        // Menampilkan form pengaturan sistem aplikasi
        Route::get('/settings', [SettingController::class, 'edit'])
            ->name('settings.edit');

        // Menyimpan perubahan konfigurasi sistem aplikasi
        Route::put('/settings', [SettingController::class, 'update'])
            ->name('settings.update');


        // --------------------------------------------------------
        // SISTEM — LOG AKTIVITAS
        // --------------------------------------------------------
        // Menampilkan riwayat log aktivitas pengguna sistem
        Route::get('/logs', [LogController::class, 'index'])
            ->name('logs');

        // Menghapus seluruh riwayat log aktivitas
        Route::delete('/logs', [LogController::class, 'clear'])
            ->name('logs.clear');

        // --------------------------------------------------------
        // PENCARIAN GLOBAL — TOPBAR
        // --------------------------------------------------------
        // Menangani input pencarian cepat dari navbar admin
        Route::get('/search', AdminSearchController::class)
            ->name('search');
    });


// ============================================================
// 5. GROUP ROUTE: GURU PEMBIMBING (Wajib Login + Role Guru)
// ============================================================
Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')            // Awalan URL: /guru/...
    ->name('guru.')             // Awalan nama route: guru....
    ->group(function () {

        // --------------------------------------------------------
        // DASHBOARD
        // --------------------------------------------------------
        // Halaman Dashboard overview guru pembimbing
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])
            ->name('dashboard');


        // --------------------------------------------------------
        // BIMBINGAN — DATA SISWA & PENILAIAN
        // --------------------------------------------------------
        // Menampilkan daftar siswa yang dibimbing oleh guru tersebut
        Route::get('/siswa', [SiswaBimbinganController::class, 'index'])
            ->name('siswa.index');

        // Menyimpan / memperbarui nilai magang siswa
        Route::put(
            '/siswa/{siswa}/nilai',
            [SiswaBimbinganController::class, 'simpanNilai']
        )->name('siswa.nilai');


        // --------------------------------------------------------
        // BIMBINGAN — VALIDASI JURNAL
        // --------------------------------------------------------
        // Menampilkan daftar jurnal kegiatan siswa untuk divalidasi
        Route::get('/jurnal', [ValidasiController::class, 'index'])
            ->name('jurnal.index');

        // Memberikan persetujuan / catatan validasi jurnal kegiatan
        Route::put(
            '/jurnal/{jurnal}/validasi',
            [ValidasiController::class, 'validasiJurnal']
        )->name('jurnal.validasi');


        // --------------------------------------------------------
        // BIMBINGAN — VALIDASI ABSENSI
        // --------------------------------------------------------
        // Memvalidasi kehadiran / ketidakhadiran siswa magang
        Route::put(
            '/absensi/{absensi}/validasi',
            [ValidasiController::class, 'validasiAbsensi']
        )->name('absensi.validasi');


        // --------------------------------------------------------
        // BIMBINGAN — KUNJUNGAN LAPANGAN (MONITORING DUDI)
        // --------------------------------------------------------
        // Daftar riwayat monitoring / kunjungan guru ke tempat DUDI
        Route::get('/kunjungan', [KunjunganController::class, 'index'])
            ->name('kunjungan.index');

        // Menyimpan catatan kunjungan baru ke DUDI
        Route::post('/kunjungan', [KunjunganController::class, 'store'])
            ->name('kunjungan.store');

        // Memperbarui data catatan kunjungan
        Route::put(
            '/kunjungan/{kunjungan}',
            [KunjunganController::class, 'update']
        )->name('kunjungan.update');

        // Menghapus data kunjungan
        Route::delete(
            '/kunjungan/{kunjungan}',
            [KunjunganController::class, 'destroy']
        )->name('kunjungan.destroy');
    });


// ============================================================
// 6. GROUP ROUTE: SISWA MAGANG (Wajib Login + Role Siswa)
// ============================================================
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')           // Awalan URL: /siswa/...
    ->name('siswa.')            // Awalan nama route: siswa....
    ->group(function () {

        // --------------------------------------------------------
        // UTAMA — DASHBOARD
        // --------------------------------------------------------
        // Halaman ringkasan informasi magang siswa
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])
            ->name('dashboard');


        // --------------------------------------------------------
        // UTAMA — PENGAJUAN MAGANG
        // --------------------------------------------------------
        // Halaman tampilan status & formulir pengajuan tempat magang
        Route::get('/pengajuan', [PengajuanController::class, 'index'])
            ->name('pengajuan.index');

        // Menyimpan formulir pengajuan tempat magang mandiri
        Route::post('/pengajuan', [PengajuanController::class, 'store'])
            ->name('pengajuan.store');


        // --------------------------------------------------------
        // KEGIATAN MAGANG — ABSENSI
        // --------------------------------------------------------
        // Halaman riwayat dan formulir absensi harian
        Route::get('/absensi', [AbsensiController::class, 'index'])
            ->name('absensi.index');
        Route::get('/absensi-harian', [AbsensiController::class, 'index']);

        // Mengirim data check-in absensi harian
        Route::post('/absensi', [AbsensiController::class, 'store'])
            ->name('absensi.store');
        Route::post('/absensi-harian', [AbsensiController::class, 'store']);


        // --------------------------------------------------------
        // KEGIATAN MAGANG — JURNAL
        // --------------------------------------------------------
        // Halaman riwayat jurnal harian siswa
        Route::get('/jurnal', [JurnalController::class, 'index'])
            ->name('jurnal.index');
        Route::get('/jurnal-kegiatan', [JurnalController::class, 'index']);

        // Menyimpan input jurnal kegiatan baru
        Route::post('/jurnal', [JurnalController::class, 'store'])
            ->name('jurnal.store');
        Route::post('/jurnal-kegiatan', [JurnalController::class, 'store']);

        // Memperbarui isi catatan jurnal harian
        Route::put(
            '/jurnal/{jurnal}',
            [JurnalController::class, 'update']
        )->name('jurnal.update');

        // Menghapus data jurnal harian
        Route::delete(
            '/jurnal/{jurnal}',
            [JurnalController::class, 'destroy']
        )->name('jurnal.destroy');


        // --------------------------------------------------------
        // PROFIL SAYA
        // --------------------------------------------------------
        // Halaman informasi biodata siswa
        Route::get('/profil', [SiswaProfilController::class, 'index'])
            ->name('profil.index');

        // Memperbarui data profil & password siswa
        Route::post('/profil', [SiswaProfilController::class, 'update'])
            ->name('profil.update');
        Route::put('/profil', [SiswaProfilController::class, 'update']);

        // --------------------------------------------------------
        // PENCARIAN GLOBAL — TOPBAR
        // --------------------------------------------------------
        // Menangani input pencarian cepat dari navbar siswa
        Route::get('/search', SiswaSearchController::class)
            ->name('search');
    });
