<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard siswa dengan data aktual dari profil, penempatan, absensi, dan jurnal.
     */
    public function index()
    {
        $siswa = auth()->user()->siswa;

        abort_if(! $siswa, 403, 'Data siswa tidak ditemukan untuk akun ini.');

        $siswa->load('profile');
        $penempatan = $siswa->penempatan()
            ->with(['tempatMagang', 'guru.profile'])
            ->first();

        $statusPengajuan = $penempatan?->status_pengesahan ?? 'belum_mengajukan';
        $magangAktif = $statusPengajuan === 'disahkan';
        $magangSelesai = $statusPengajuan === 'lulus_magang';

        $hariKe = 0;
        $totalHariMagang = 0;
        $tanggalSelesaiMagang = '-';
        $progresPersen = 0;

        if ($penempatan) {
            $mulai = Carbon::parse($penempatan->tanggal_mulai)->startOfDay();
            $selesai = Carbon::parse($penempatan->tanggal_selesai)->startOfDay();
            $hariIni = now()->startOfDay();

            $totalHariMagang = max(1, $mulai->diffInDays($selesai) + 1);
            $tanggalSelesaiMagang = $selesai->translatedFormat('d M Y');

            if (($magangAktif || $magangSelesai) && $hariIni->gte($mulai)) {
                $hariKe = min($totalHariMagang, $mulai->diffInDays($hariIni) + 1);
                $progresPersen = $magangSelesai
                    ? 100
                    : (int) round(($hariKe / $totalHariMagang) * 100);
            }
        }

        $totalKehadiran = $siswa->absensi()->where('status', 'hadir')->count();
        $totalJurnal = $siswa->jurnalHarian()->count();
        $jurnalTerverifikasi = $siswa->jurnalHarian()->where('status_verifikasi', 'disetujui')->count();
        $sudahAbsenHariIni = $siswa->absensi()->whereDate('tanggal', today())->exists();

        return view('siswa.dashboard', [
            'penempatan' => $penempatan,
            'namaSiswa' => $siswa->profile->nama ?? 'Siswa',
            'namaPerusahaan' => $penempatan?->tempatMagang?->nama_perusahaan ?? '-',
            'alamatSingkat' => $penempatan?->tempatMagang?->alamat,
            'namaGuru' => $penempatan?->guru?->profile?->nama ?? 'Belum ditentukan',
            'nipGuru' => $penempatan?->guru?->nip,
            'statusPengajuan' => $statusPengajuan,
            'hariKe' => $hariKe,
            'totalHariMagang' => $totalHariMagang,
            'tanggalSelesaiMagang' => $tanggalSelesaiMagang,
            'progresPersen' => $progresPersen,
            'totalKehadiran' => $totalKehadiran,
            'totalJurnal' => $totalJurnal,
            'jurnalTerverifikasi' => $jurnalTerverifikasi,
            'sudahAbsenHariIni' => $sudahAbsenHariIni,
            'magangAktif' => $magangAktif,
        ]);
    }
}
