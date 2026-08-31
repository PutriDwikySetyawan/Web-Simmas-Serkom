<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\PengajuanMagang;
use App\Models\PenempatanMagang;
use App\Models\Siswa;
use App\Models\TempatMagang;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Global search endpoint untuk Admin.
     * Mengembalikan hasil pencarian dari Siswa, Guru, DUDI, dan Penempatan.
     */
    public function __invoke(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // ── Siswa ──────────────────────────────────────────────
        $siswaList = Siswa::with('profile')
            ->where(function ($query) use ($q) {
                $query->where('nis', 'like', "%{$q}%")
                      ->orWhere('kelas', 'like', "%{$q}%")
                      ->orWhereHas('profile', fn ($p) => $p->where('nama', 'like', "%{$q}%"));
            })
            ->limit(5)
            ->get();

        foreach ($siswaList as $siswa) {
            $results[] = [
                'type'     => 'siswa',
                'icon'     => 'bi-person-fill',
                'label'    => $siswa->profile->nama ?? $siswa->nis,
                'sublabel' => 'Siswa · ' . ($siswa->kelas ?? '-') . ' · NIS ' . $siswa->nis,
                'url'      => route('admin.siswa.index') . '?search=' . urlencode($q),
            ];
        }

        // ── Guru ───────────────────────────────────────────────
        $guruList = Guru::with('profile')
            ->where(function ($query) use ($q) {
                $query->whereHas('profile', fn ($p) => $p->where('nama', 'like', "%{$q}%"))
                      ->orWhere('nip', 'like', "%{$q}%");
            })
            ->limit(4)
            ->get();

        foreach ($guruList as $guru) {
            $results[] = [
                'type'     => 'guru',
                'icon'     => 'bi-person-badge-fill',
                'label'    => $guru->profile->nama ?? $guru->nip,
                'sublabel' => 'Guru · NIP ' . $guru->nip,
                'url'      => route('admin.guru.index') . '?search=' . urlencode($q),
            ];
        }

        // ── DUDI (Tempat Magang) ───────────────────────────────
        $dudiList = TempatMagang::where(function ($query) use ($q) {
                $query->where('nama_perusahaan', 'like', "%{$q}%")
                      ->orWhere('bidang_usaha', 'like', "%{$q}%")
                      ->orWhere('alamat', 'like', "%{$q}%")
                      ->orWhere('nama_pic', 'like', "%{$q}%");
            })
            ->limit(4)
            ->get();

        foreach ($dudiList as $dudi) {
            $results[] = [
                'type'     => 'dudi',
                'icon'     => 'bi-building-fill',
                'label'    => $dudi->nama_perusahaan,
                'sublabel' => 'DUDI · ' . ($dudi->bidang_usaha ?? '-'),
                'url'      => route('admin.dudi.index') . '?search=' . urlencode($q),
            ];
        }

        // ── Penempatan Magang ──────────────────────────────────
        $penempatanList = PenempatanMagang::with(['siswa.profile', 'tempatMagang'])
            ->whereHas('siswa', fn ($query) =>
                $query->where('nis', 'like', "%{$q}%")
                      ->orWhereHas('profile', fn ($p) => $p->where('nama', 'like', "%{$q}%"))
            )
            ->orWhereHas('tempatMagang', fn ($query) =>
                $query->where('nama_perusahaan', 'like', "%{$q}%")
            )
            ->limit(4)
            ->get();

        foreach ($penempatanList as $penempatan) {
            $results[] = [
                'type'     => 'penempatan',
                'icon'     => 'bi-diagram-3-fill',
                'label'    => ($penempatan->siswa->profile->nama ?? '-') . ' → ' . ($penempatan->tempatMagang->nama_perusahaan ?? '-'),
                'sublabel' => 'Penempatan · ' . ucfirst(str_replace('_', ' ', $penempatan->status_pengesahan)),
                'url'      => route('admin.penempatan.index') . '?search=' . urlencode($q),
            ];
        }

        // ── Pengajuan (menunggu/ditolak) ───────────────────────
        $pengajuanList = PengajuanMagang::with(['siswa.profile', 'tempatMagang'])
            ->whereHas('siswa', fn ($query) =>
                $query->where('nis', 'like', "%{$q}%")
                      ->orWhereHas('profile', fn ($p) => $p->where('nama', 'like', "%{$q}%"))
            )
            ->whereIn('status', ['menunggu', 'ditolak'])
            ->limit(3)
            ->get();

        foreach ($pengajuanList as $pengajuan) {
            $results[] = [
                'type'     => 'pengajuan',
                'icon'     => 'bi-file-earmark-text-fill',
                'label'    => ($pengajuan->siswa->profile->nama ?? '-') . ' → ' . ($pengajuan->tempatMagang->nama_perusahaan ?? '-'),
                'sublabel' => 'Pengajuan · ' . ucfirst($pengajuan->status),
                'url'      => route('admin.penempatan.index') . '?search=' . urlencode($q),
            ];
        }

        return response()->json([
            'results' => $results,
            'query'   => $q,
        ]);
    }
}
