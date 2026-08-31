<?php

namespace App\Http\Controllers;

// ============================================
// Import model — ditambah Guru untuk hitung total guru pembimbing
// ============================================
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\JurnalHarian;
use App\Models\PenempatanMagang;
use App\Models\Siswa;
use App\Models\TempatMagang;

class LandingController extends Controller
{
    /**
     * Tampilkan halaman landing page publik (/)
     */
    public function index()
    {
        // ============================================
        // Semua angka diambil langsung dari database, tidak ada nilai dummy
        // ============================================

        // Jumlah jurnal harian yang sudah disetujui guru pembimbing
        $jurnalDisetujui  = JurnalHarian::where('status_verifikasi', 'disetujui')->count();

        // Jumlah presensi yang tercatat pada bulan berjalan
        $presensiTercatat = Absensi::whereMonth('tanggal', now()->month)->count();

        // Jumlah siswa yang statusnya sedang aktif magang
        $siswaAktif       = Siswa::where('status', 'sedang_magang')->count();

        // Jumlah mitra DUDI yang sudah terverifikasi
        $totalMitraDudi   = TempatMagang::where('status_verifikasi', 'terverifikasi')->count();

        // Jumlah guru pembimbing — dipakai di preview dashboard hero landing page
        $totalGuru        = Guru::count();

        // Cuplikan permohonan magang terbaru untuk preview dashboard di hero landing page
        $pengajuanTerbaru = PenempatanMagang::with('siswa.profile')
            ->latest()
            ->take(4)
            ->get();

        // ============================================
        // Kirim semua variabel ke view landing.blade.php
        // ============================================
        return view('landing', compact(
            'jurnalDisetujui',
            'presensiTercatat',
            'siswaAktif',
            'totalMitraDudi',
            'totalGuru',
            'pengajuanTerbaru'
        ));
    }
}
