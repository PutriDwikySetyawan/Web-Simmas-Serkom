<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\JurnalHarian;
use App\Models\PenempatanMagang;

class DashboardController extends Controller
{
    /**
     * Dashboard guru dengan data aktual siswa bimbingan, jurnal, dan absensi.
     */
    public function index()
    {
        $guru = auth()->user()->guru;

        abort_if(! $guru, 403, 'Data guru tidak ditemukan untuk akun ini.');

        $guru->load('profile');

        $penempatanAktif = PenempatanMagang::query()
            ->with([
                'siswa.profile',
                'siswa.absensi' => fn ($query) => $query->whereDate('tanggal', today()),
                'tempatMagang',
            ])
            ->where('guru_id', $guru->id)
            ->where('status_pengesahan', 'disahkan')
            ->latest()
            ->get();

        $siswaIds = $penempatanAktif->pluck('siswa_id');
        $totalSiswaBimbingan = $siswaIds->count();
        $kehadiranHadir = Absensi::whereIn('siswa_id', $siswaIds)
            ->whereDate('tanggal', today())
            ->where('status', 'hadir')
            ->count();

        $jurnalBelumDinilai = JurnalHarian::whereIn('siswa_id', $siswaIds)
            ->where('status_verifikasi', 'menunggu')
            ->count();

        $jurnalPerluEvaluasi = JurnalHarian::with('siswa.profile')
            ->whereIn('siswa_id', $siswaIds)
            ->where('status_verifikasi', 'menunggu')
            ->latest('tanggal')
            ->limit(5)
            ->get()
            ->map(function (JurnalHarian $jurnal) {
                $jurnal->siswa_nama = $jurnal->siswa->profile->nama ?? '-';
                $jurnal->deskripsi = $jurnal->kegiatan;

                return $jurnal;
            });

        $siswaBimbingan = $penempatanAktif->map(function (PenempatanMagang $penempatan) {
            $status = $penempatan->siswa->absensi->first()?->status ?? 'belum_absen';

            return (object) [
                'nama' => $penempatan->siswa->profile->nama ?? '-',
                'tempat_magang' => $penempatan->tempatMagang->nama_perusahaan ?? '-',
                'status_kehadiran' => match ($status) {
                    'hadir' => 'Hadir',
                    'sakit' => 'Sakit',
                    'izin' => 'Izin',
                    'alfa' => 'Alfa',
                    default => 'Belum Absen',
                },
            ];
        });

        return view('guru.dashboard', compact(
            'totalSiswaBimbingan',
            'jurnalBelumDinilai',
            'kehadiranHadir',
            'jurnalPerluEvaluasi',
            'siswaBimbingan'
        ) + [
            'guruName' => $guru->profile->nama ?? 'Guru Pembimbing',
            'kehadiranTotal' => $totalSiswaBimbingan,
        ]);
    }
}
