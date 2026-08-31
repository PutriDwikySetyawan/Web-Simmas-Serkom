<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Guru;
use App\Models\PengajuanMagang;
use App\Models\PenempatanMagang;
use App\Models\Siswa;
use App\Models\TempatMagang;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * ============================================================
 * CONTROLLER: ADMIN DASHBOARD
 * ============================================================
 */
class DashboardController extends Controller
{
    /**
     * 1. DASHBOARD STATISTIK (/admin/dashboard)
     */
    public function index(): View
    {
        // ---------------------------------------------------
        // STAT CARD
        // ---------------------------------------------------
        $totalSiswa = Siswa::count();

        $guruPembimbingAktif = Guru::where('is_active', true)->count();

        $mitraDudiTerverifikasi = TempatMagang::where('status_verifikasi', 'terverifikasi')->count();

        // Pengajuan menunggu validasi dihitung dari tabel pengajuan_magang (dan penempatan_magang jika ada)
        $pengajuanMenungguValidasi = PengajuanMagang::where('status', 'menunggu')->count()
            + PenempatanMagang::where('status_pengesahan', 'menunggu')->count();

        // ---------------------------------------------------
        // WIDGET STATUS PENGAJUAN (REALTIME)
        // ---------------------------------------------------
        $statusPengajuan = [
            'disetujui' => PenempatanMagang::whereIn('status_pengesahan', ['disahkan', 'lulus_magang'])->count()
                + PengajuanMagang::where('status', 'disetujui')->count(),
            'menunggu'  => $pengajuanMenungguValidasi,
            'ditolak'   => PengajuanMagang::where('status', 'ditolak')->count()
                + PenempatanMagang::where('status_pengesahan', 'ditolak')->count(),
        ];

        // ---------------------------------------------------
        // GRAFIK TREN PENGAJUAN (6 BULAN TERAKHIR)
        // ---------------------------------------------------
        $labelBulan = [];
        $dataPengajuan = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);

            $labelBulan[] = $bulan->translatedFormat('M');

            $dataPengajuan[] = PengajuanMagang::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count()
                + PenempatanMagang::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
        }

        // ---------------------------------------------------
        // TABEL DISTRIBUSI DUDI
        // ---------------------------------------------------
        $distribusiDudi = TempatMagang::withCount([
            'penempatan as siswa_aktif_count' => function ($query) {
                $query->whereNotIn('status_pengesahan', ['ditolak', 'lulus_magang']);
            },
        ])
            ->orderByDesc('siswa_aktif_count')
            ->take(5)
            ->get();

        // ---------------------------------------------------
        // WIDGET LOG AKTIVITAS TERBARU (5 TERAKHIR)
        // ---------------------------------------------------
        $activityLogs = ActivityLog::latest('created_at')->take(5)->get();

        return view('admin.dashboard', [
            'totalSiswa'                => $totalSiswa,
            'guruPembimbingAktif'       => $guruPembimbingAktif,
            'mitraDudiTerverifikasi'    => $mitraDudiTerverifikasi,
            'pengajuanMenungguValidasi' => $pengajuanMenungguValidasi,
            'statusPengajuan'           => $statusPengajuan,
            'labelBulan'                => $labelBulan,
            'dataPengajuan'             => $dataPengajuan,
            'distribusiDudi'            => $distribusiDudi,
            'activityLogs'              => $activityLogs,
        ]);
    }

    /**
     * 2. MONITORING GLOBAL (/admin/monitoring)
     */
    public function monitoring(Request $request): View
    {
        $keyword = $request->input('q');
        $kelas   = $request->input('kelas');

        $query = Siswa::with([
            'profile',
            'penempatan.tempatMagang',
            'penempatan.guru.profile',
            'absensi',
            'jurnalHarian' => function ($q) {
                $q->latest('tanggal');
            },
        ]);

        if ($keyword) {
            $query->where('nis', 'like', "%{$keyword}%")
                ->orWhereHas('profile', function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%");
                });
        }

        if ($kelas && $kelas !== 'semua') {
            $query->where('kelas', $kelas);
        }

        $siswaList = $query->orderBy('kelas')->paginate(10)->withQueryString();

        $siswaList->getCollection()->transform(function (Siswa $siswa) {
            $siswa->rekap_hadir = $siswa->absensi->where('status', 'hadir')->count();
            $siswa->rekap_sakit = $siswa->absensi->where('status', 'sakit')->count();
            $siswa->rekap_izin  = $siswa->absensi->where('status', 'izin')->count();
            $siswa->rekap_alfa  = $siswa->absensi->where('status', 'alfa')->count();

            $siswa->jumlah_jurnal = $siswa->jurnalHarian->count();

            $terakhirAbsensi = $siswa->absensi->max('created_at');
            $terakhirJurnal  = $siswa->jurnalHarian->max('created_at');
            $siswa->terakhir_aktif = collect([$terakhirAbsensi, $terakhirJurnal])
                ->filter()
                ->max();

            $siswa->status_keaktifan = $this->hitungStatusKeaktifan(
                $siswa->rekap_alfa,
                $siswa->terakhir_aktif
            );

            return $siswa;
        });

        $kelasList = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('admin.monitoring', [
            'siswaList' => $siswaList,
            'kelasList' => $kelasList,
            'keyword'   => $keyword,
            'kelasAktif' => $kelas ?? 'semua',
        ]);
    }

    /**
     * 3. DETAIL MONITORING SISWA (Modal Dialog, via AJAX)
     */
    public function detail(Siswa $siswa): JsonResponse
    {
        $siswa->load([
            'profile',
            'penempatan.tempatMagang',
            'penempatan.guru.profile',
            'absensi',
            'jurnalHarian' => function ($q) {
                $q->latest('tanggal');
            },
        ]);

        $penempatanAktif = $siswa->penempatan;

        return response()->json([
            'nama_siswa'      => $siswa->profile->nama ?? '-',
            'nis'             => $siswa->nis,
            'kelas'           => $siswa->kelas,
            'tempat_magang'   => $penempatanAktif->tempatMagang->nama_perusahaan ?? 'Belum Ditempatkan',
            'guru_pembimbing' => $penempatanAktif->guru->profile->nama ?? '-',

            'rekap' => [
                'hadir' => $siswa->absensi->where('status', 'hadir')->count(),
                'sakit' => $siswa->absensi->where('status', 'sakit')->count(),
                'izin'  => $siswa->absensi->where('status', 'izin')->count(),
                'alfa'  => $siswa->absensi->where('status', 'alfa')->count(),
            ],

            'jurnal_terakhir' => $siswa->jurnalHarian->first()->kegiatan ?? 'Belum ada jurnal yang ditulis.',
        ]);
    }

    private function hitungStatusKeaktifan(int $jumlahAlfa, ?string $terakhirAktif): string
    {
        if ($jumlahAlfa >= 3) {
            return 'Bermasalah';
        }

        if (! $terakhirAktif || Carbon::parse($terakhirAktif)->lt(Carbon::now()->subDays(3))) {
            return 'Perlu Perhatian';
        }

        return 'Aktif';
    }
}
