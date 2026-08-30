<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JurnalHarian;
use App\Models\PenempatanMagang;

class DashboardController extends Controller
{
    /**
     * Overview: Dashboard Guru Pembimbing (/guru/dashboard)
     */
    public function index()
    {
        $guru = auth()->user()->guru;

        // ============================================
        // Daftar penempatan siswa bimbingan guru ini
        // ============================================
        $penempatanAktif = PenempatanMagang::with(['siswa.profile', 'siswa.absensi', 'tempatMagang'])
            ->where('guru_id', $guru->id)
            ->where('status_pengesahan', '!=', 'lulus_magang')
            ->get();

        $siswaIds = $penempatanAktif->pluck('siswa_id');

        // ============================================
        // 3 Stat Card
        // ============================================
        $totalSiswaBimbingan = $penempatanAktif->count();

        $jurnalBelumDinilai = JurnalHarian::whereIn('siswa_id', $siswaIds)
            ->where('status_verifikasi', 'menunggu')
            ->count();

        $hadirHariIni = \App\Models\Absensi::whereIn('siswa_id', $siswaIds)
            ->whereDate('tanggal', today())
            ->where('status', 'hadir')
            ->count();

        $rasioKehadiran = $totalSiswaBimbingan > 0
            ? round(($hadirHariIni / $totalSiswaBimbingan) * 100)
            : 0;

        // ============================================
        // Widget: Jurnal Perlu Evaluasi (terbaru, menunggu validasi)
        // ============================================
        $jurnalPerluEvaluasi = JurnalHarian::with('siswa.profile')
            ->whereIn('siswa_id', $siswaIds)
            ->where('status_verifikasi', 'menunggu')
            ->latest('tanggal')
            ->limit(5)
            ->get();

        // ============================================
        // Widget: Daftar Siswa Bimbingan + status kehadiran hari ini
        // ============================================
        $daftarSiswaBimbingan = $penempatanAktif->map(function ($penempatan) {
            $absensiHariIni = $penempatan->siswa->absensi()
                ->whereDate('tanggal', today())
                ->first();

            return [
                'siswa'  => $penempatan->siswa,
                'status' => $absensiHariIni->status ?? 'belum_absen',
            ];
        });

        return view('guru.dashboard', compact(
            'totalSiswaBimbingan', 'jurnalBelumDinilai', 'rasioKehadiran',
            'jurnalPerluEvaluasi', 'daftarSiswaBimbingan'
        ));
    }
}