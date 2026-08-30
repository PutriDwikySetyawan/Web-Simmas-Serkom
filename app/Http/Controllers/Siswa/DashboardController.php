<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;


class DashboardController extends Controller
{
    /**
     * Utama: Dashboard Siswa (/siswa/dashboard)
     */
    public function index()
    {
        $siswa = auth()->user()->siswa;

        // ============================================
        // Banner Penempatan: tempat magang & guru pembimbing
        // ============================================
        $penempatan = $siswa->penempatan()->with(['tempatMagang', 'guru.profile'])->first();

        // ============================================
        // Progres magang: hari ke berapa dari total periode
        // ============================================
        $progresMagang = null;
        if ($penempatan) {
            $mulai   = \Carbon\Carbon::parse($penempatan->tanggal_mulai);
            $selesai = \Carbon\Carbon::parse($penempatan->tanggal_selesai);
            $hariKe  = max(1, now()->diffInDays($mulai) + 1);
            $totalHari = $mulai->diffInDays($selesai);

            $progresMagang = [
                'hari_ke'    => $hariKe,
                'total_hari' => $totalHari,
                'selesai_pada' => $selesai->translatedFormat('d M Y'),
            ];
        }

        // ============================================
        // 2 Stat Card
        // ============================================
        $totalKehadiran = $siswa->absensi()->where('status', 'hadir')->count();

        $jurnalDitulis = $siswa->jurnalHarian()->count();
        $jurnalTerverifikasi = $siswa->jurnalHarian()->where('status_verifikasi', 'disetujui')->count();

        // ============================================
        // Status absensi hari ini (untuk banner "Isi Absensi")
        // ============================================
        $absensiHariIni = $siswa->absensi()->whereDate('tanggal', today())->first();

        return view('siswa.dashboard', compact(
            'penempatan', 'progresMagang', 'totalKehadiran',
            'jurnalDitulis', 'jurnalTerverifikasi', 'absensiHariIni'
        ));
    }
}