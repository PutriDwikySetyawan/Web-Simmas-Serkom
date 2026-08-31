<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\JurnalHarian;
use App\Models\PengajuanMagang;
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
        // Jumlah jurnal harian yang sudah disetujui guru pembimbing
        $jurnalDisetujui  = JurnalHarian::where('status_verifikasi', 'disetujui')->count();

        // Jumlah presensi yang tercatat pada bulan berjalan
        $presensiTercatat = Absensi::whereMonth('tanggal', now()->month)->count();

        // Jumlah siswa aktif magang / pengajuan
        $siswaAktif       = Siswa::whereIn('status', ['sedang_magang', 'pengajuan'])->count();

        // Jumlah mitra DUDI yang sudah terverifikasi
        $totalMitraDudi   = TempatMagang::where('status_verifikasi', 'terverifikasi')->count();

        // Jumlah guru pembimbing
        $totalGuru        = Guru::count();

        // Cuplikan permohonan magang terbaru untuk preview mockup dashboard hero landing page (realtime dari PengajuanMagang & PenempatanMagang)
        $pengajuanList = PengajuanMagang::with('siswa.profile')->latest()->get()->map(function ($item) {
            $item->status_display = match ($item->status) {
                'disetujui' => 'disahkan',
                'ditolak' => 'ditolak',
                default => 'menunggu',
            };
            return $item;
        });

        $penempatanList = PenempatanMagang::with('siswa.profile')->latest()->get()->map(function ($item) {
            $item->status_display = $item->status_pengesahan;
            return $item;
        });

        $pengajuanTerbaru = $pengajuanList->concat($penempatanList)
            ->sortByDesc('created_at')
            ->take(4)
            ->values();

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
