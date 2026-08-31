<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard siswa dengan data aktual dari profil, penempatan, pengajuan, absensi, dan jurnal.
     */
    public function index()
    {
        $siswa = auth()->user()->siswa;

        abort_if(! $siswa, 403, 'Data siswa tidak ditemukan untuk akun ini.');

        $siswa->load('profile');

        $penempatan = $siswa->penempatan()
            ->with(['tempatMagang', 'guru.profile'])
            ->first();

        $pengajuan = PengajuanMagang::where('siswa_id', $siswa->id)
            ->with(['tempatMagang'])
            ->latest()
            ->first();

        $statusPengajuan = 'belum_mengajukan';
        if ($penempatan) {
            $statusPengajuan = $penempatan->status_pengesahan;
        } elseif ($pengajuan) {
            $statusPengajuan = match ($pengajuan->status) {
                'disetujui' => 'disahkan',
                'ditolak' => 'ditolak',
                default => 'menunggu',
            };
        }

        $magangAktif = $statusPengajuan === 'disahkan';
        $magangSelesai = $statusPengajuan === 'lulus_magang';

        $hariKe = 0;
        $totalHariMagang = 0;
        $tanggalSelesaiMagang = '-';
        $progresPersen = 0;

        $targetPlacement = $penempatan ?? $pengajuan;

        if ($targetPlacement) {
            $mulai = Carbon::parse($targetPlacement->tanggal_mulai)->startOfDay();
            $selesai = Carbon::parse($targetPlacement->tanggal_selesai)->startOfDay();
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

        $namaPerusahaan = $penempatan?->tempatMagang?->nama_perusahaan
            ?? $pengajuan?->tempatMagang?->nama_perusahaan
            ?? '-';

        $alamatSingkat = $penempatan?->tempatMagang?->alamat
            ?? $pengajuan?->tempatMagang?->alamat;

        $totalKehadiran = $siswa->absensi()->where('status', 'hadir')->count();
        $totalJurnal = $siswa->jurnalHarian()->count();
        $jurnalTerverifikasi = $siswa->jurnalHarian()->where('status_verifikasi', 'disetujui')->count();
        $sudahAbsenHariIni = $siswa->absensi()->whereDate('tanggal', today())->exists();

        return view('siswa.dashboard', [
            'penempatan' => $penempatan,
            'pengajuan' => $pengajuan,
            'namaSiswa' => $siswa->profile->nama ?? 'Siswa',
            'namaPerusahaan' => $namaPerusahaan,
            'alamatSingkat' => $alamatSingkat,
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
