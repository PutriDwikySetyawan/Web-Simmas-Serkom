<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JurnalMagang;
use App\Models\AbsensiMagang;
use App\Models\PengajuanMagang;
use App\Models\PenempatanMagang;
use App\Models\TempatMagang;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Global search endpoint untuk Siswa (hanya melihat data miliknya sendiri).
     */
    public function __invoke(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $siswa = auth()->user()->siswa ?? null;
        $results = [];

        // ── DUDI / Tempat Magang (semua yang tersedia) ─────────
        $dudiList = TempatMagang::where('status_verifikasi', 'terverifikasi')
            ->where(function ($query) use ($q) {
                $query->where('nama_perusahaan', 'like', "%{$q}%")
                      ->orWhere('bidang_usaha', 'like', "%{$q}%")
                      ->orWhere('kota', 'like', "%{$q}%")
                      ->orWhere('alamat', 'like', "%{$q}%");
            })
            ->limit(5)
            ->get();

        foreach ($dudiList as $dudi) {
            $results[] = [
                'type'     => 'dudi',
                'icon'     => 'bi-building',
                'label'    => $dudi->nama_perusahaan,
                'sublabel' => ($dudi->bidang_usaha ?? '-') . ' · ' . ($dudi->kota ?? '-') . ' · Kuota: ' . ($dudi->sisa_kuota ?? 0),
                'url'      => url('siswa/pengajuan'),
            ];
        }

        if ($siswa) {
            // ── Pengajuan milik siswa ini ──────────────────────
            $pengajuanList = PengajuanMagang::with('tempatMagang')
                ->where('siswa_id', $siswa->id)
                ->whereHas('tempatMagang', fn ($q2) => $q2->where('nama_perusahaan', 'like', "%{$q}%"))
                ->limit(3)
                ->get();

            foreach ($pengajuanList as $pengajuan) {
                $results[] = [
                    'type'     => 'pengajuan',
                    'icon'     => 'bi-file-earmark-text',
                    'label'    => 'Pengajuan ke ' . ($pengajuan->tempatMagang->nama_perusahaan ?? '-'),
                    'sublabel' => 'Status: ' . ucfirst($pengajuan->status),
                    'url'      => url('siswa/pengajuan'),
                ];
            }

            // ── Jurnal milik siswa ini ─────────────────────────
            if (class_exists(JurnalMagang::class)) {
                $jurnalList = JurnalMagang::where('siswa_id', $siswa->id)
                    ->where(function ($query) use ($q) {
                        $query->where('kegiatan', 'like', "%{$q}%")
                              ->orWhere('deskripsi', 'like', "%{$q}%");
                    })
                    ->limit(4)
                    ->get();

                foreach ($jurnalList as $jurnal) {
                    $results[] = [
                        'type'     => 'jurnal',
                        'icon'     => 'bi-journal-text',
                        'label'    => \Illuminate\Support\Str::limit($jurnal->kegiatan ?? $jurnal->deskripsi, 50),
                        'sublabel' => 'Jurnal · ' . optional($jurnal->tanggal)->format('d M Y'),
                        'url'      => url('siswa/jurnal'),
                    ];
                }
            }
        }

        return response()->json([
            'results' => $results,
            'query'   => $q,
        ]);
    }
}
