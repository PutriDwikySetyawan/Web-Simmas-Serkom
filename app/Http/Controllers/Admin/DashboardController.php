<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\TempatMagang;
use App\Models\PengajuanMagang;
use App\Models\PenempatanMagang;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;

/**
 * ============================================================
 * CONTROLLER: ADMIN DASHBOARD
 * ============================================================
 *
 * Menangani 3 halaman pada menu "Overview" milik Administrator:
 * 1. Dashboard Statistik      -> GET /admin/dashboard
 * 2. Monitoring Global        -> GET /admin/monitoring
 * 3. Detail Monitoring Siswa  -> GET /admin/monitoring/{siswa}/detail (AJAX/modal)
 */
class DashboardController extends Controller
{
    /**
     * ------------------------------------------------------
     * 1. DASHBOARD STATISTIK (/admin/dashboard)
     * ------------------------------------------------------
     * Menampilkan:
     * - 4 Stat Card (Total Siswa, Guru Aktif, Mitra DUDI Terverifikasi,
     *   Pengajuan Menunggu Validasi)
     * - Grafik tren pengajuan 6 bulan terakhir
     * - Widget distribusi status pengajuan (disetujui/menunggu/ditolak)
     * - Tabel distribusi DUDI (kuota & siswa aktif)
     * - Widget 5 log aktivitas terbaru
     */
    public function index(): View
    {
        // ---------------------------------------------------
        // STAT CARD
        // ---------------------------------------------------
        $totalSiswa = Siswa::count();

        $guruPembimbingAktif = Guru::where('is_active', true)->count();

        $mitraDudiTerverifikasi = TempatMagang::where('status_verifikasi', 'terverifikasi')->count();

        $pengajuanMenungguValidasi = PengajuanMagang::where('status', 'menunggu')->count();

        // ---------------------------------------------------
        // WIDGET STATUS PENGAJUAN
        // ---------------------------------------------------
        // Dipakai widget "Status Pengajuan" di dashboard: rekap
        // jumlah pengajuan magang per status (disetujui/menunggu/
        // ditolak). $pengajuanMenungguValidasi di atas dipakai
        // ulang di sini supaya tidak query 2x untuk angka yang sama.
        $statusPengajuan = [
            'disetujui' => PengajuanMagang::where('status', 'disetujui')->count(),
            'menunggu'  => $pengajuanMenungguValidasi,
            'ditolak'   => PengajuanMagang::where('status', 'ditolak')->count(),
        ];

        // ---------------------------------------------------
        // GRAFIK TREN PENGAJUAN (6 BULAN TERAKHIR)
        // ---------------------------------------------------
        // Hasil akhir berupa array label bulan (Apr, Mei, ...)
        // dan array jumlah pengajuan per bulan, siap dipakai Chart.js
        $labelBulan = [];
        $dataPengajuan = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);

            $labelBulan[] = $bulan->translatedFormat('M'); // contoh: "Apr"

            $dataPengajuan[] = PengajuanMagang::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
        }

        // ---------------------------------------------------
        // TABEL DISTRIBUSI DUDI
        // ---------------------------------------------------
        // Menampilkan nama mitra, kuota, dan jumlah siswa yang
        // SEDANG aktif magang (status_pengesahan belum lulus)
        $distribusiDudi = TempatMagang::withCount([
            'penempatan as siswa_aktif_count' => function ($query) {
                $query->where('status_pengesahan', '!=', 'lulus_magang');
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
     * ------------------------------------------------------
     * 2. MONITORING GLOBAL (/admin/monitoring)
     * ------------------------------------------------------
     * Tabel besar berisi seluruh siswa beserta rekap kehadiran,
     * jumlah jurnal, dan status keaktifan. Mendukung pencarian
     * (nama/NIS) dan filter kelas.
     */
    public function monitoring(Request $request): View
    {
        $keyword = $request->input('q');
        $kelas   = $request->input('kelas');

        // ---------------------------------------------------
        // QUERY SISWA + RELASI YANG DIBUTUHKAN
        // ---------------------------------------------------
        // Eager load supaya tidak N+1 query:
        // - profile        -> nama siswa
        // - penempatan     -> tempat magang & guru pembimbing aktif
        // - absensi        -> untuk hitung rekap H/S/I/A
        // - jurnalHarian    -> untuk hitung jumlah jurnal & jurnal terakhir
        $query = Siswa::with([
            'profile',
            'penempatan.tempatMagang',
            'penempatan.guru.profile',
            'absensi',
            'jurnalHarian' => function ($q) {
                $q->latest('tanggal');
            },
        ]);

        // Filter pencarian nama / NIS
        if ($keyword) {
            $query->where('nis', 'like', "%{$keyword}%")
                ->orWhereHas('profile', function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%");
                });
        }

        // Filter kelas
        if ($kelas && $kelas !== 'semua') {
            $query->where('kelas', $kelas);
        }

        $siswaList = $query->orderBy('kelas')->paginate(10)->withQueryString();

        // ---------------------------------------------------
        // TAMBAHKAN DATA TURUNAN (COMPUTED) PER SISWA
        // ---------------------------------------------------
        // Dilakukan di PHP karena datanya butuh diringkas
        // per siswa (rekap presensi, status, waktu aktif terakhir)
        $siswaList->getCollection()->transform(function (Siswa $siswa) {
            $siswa->rekap_hadir = $siswa->absensi->where('status', 'hadir')->count();
            $siswa->rekap_sakit = $siswa->absensi->where('status', 'sakit')->count();
            $siswa->rekap_izin  = $siswa->absensi->where('status', 'izin')->count();
            $siswa->rekap_alfa  = $siswa->absensi->where('status', 'alfa')->count();

            $siswa->jumlah_jurnal = $siswa->jurnalHarian->count();

            // Waktu aktivitas terakhir = yang terbaru antara absensi & jurnal
            $terakhirAbsensi = $siswa->absensi->max('created_at');
            $terakhirJurnal  = $siswa->jurnalHarian->max('created_at');
            $siswa->terakhir_aktif = collect([$terakhirAbsensi, $terakhirJurnal])
                ->filter()
                ->max();

            // Status keaktifan siswa (Aktif / Perlu Perhatian / Bermasalah)
            $siswa->status_keaktifan = $this->hitungStatusKeaktifan(
                $siswa->rekap_alfa,
                $siswa->terakhir_aktif
            );

            return $siswa;
        });

        // Daftar kelas unik untuk dropdown filter
        $kelasList = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('admin.monitoring', [
            'siswaList' => $siswaList,
            'kelasList' => $kelasList,
            'keyword'   => $keyword,
            'kelasAktif' => $kelas ?? 'semua',
        ]);
    }

    /**
     * ------------------------------------------------------
     * 3. DETAIL MONITORING SISWA (Modal Dialog, via AJAX)
     * ------------------------------------------------------
     * Dipanggil saat tombol "Detail" di tabel monitoring diklik.
     * Mengembalikan JSON agar bisa langsung dirender ke dalam
     * modal tanpa reload halaman.
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

            // 4 kartu rekap kehadiran
            'rekap' => [
                'hadir' => $siswa->absensi->where('status', 'hadir')->count(),
                'sakit' => $siswa->absensi->where('status', 'sakit')->count(),
                'izin'  => $siswa->absensi->where('status', 'izin')->count(),
                'alfa'  => $siswa->absensi->where('status', 'alfa')->count(),
            ],

            // Kutipan jurnal harian terakhir
            'jurnal_terakhir' => $siswa->jurnalHarian->first()->kegiatan ?? 'Belum ada jurnal yang ditulis.',
        ]);
    }

    /**
     * ------------------------------------------------------
     * HELPER: HITUNG STATUS KEAKTIFAN SISWA
     * ------------------------------------------------------
     * Aturan sederhana untuk menentukan badge status pada
     * tabel Monitoring Global:
     *
     * - "Bermasalah"      : jumlah alfa >= 3
     * - "Perlu Perhatian" : tidak ada aktivitas (absensi/jurnal)
     *                       dalam 3 hari terakhir
     * - "Aktif"           : selain kondisi di atas
     */
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