<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\JurnalHarian;
use App\Models\PenempatanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidasiController extends Controller
{
    /**
     * Bimbingan: Validasi Jurnal & Absensi, 2 tab (/guru/jurnal)
     * Data difilter berdasarkan siswa yang di-bimbing oleh guru ybs (lewat penempatan_magang)
     */
    public function index(Request $request)
    {
        $profile = auth()->user();
        $guru    = $profile->guru;

        // Guard: jika profile guru tidak punya relasi ke tabel guru
        if (! $guru) {
            Log::warning('Guru profile tidak memiliki relasi guru', [
                'profile_id' => $profile->id,
                'email'      => $profile->email,
            ]);
            return view('guru.jurnal', [
                'jurnalList'  => collect()->paginate(10),
                'statJurnal'  => ['menunggu' => 0, 'disetujui' => 0, 'revisi' => 0],
                'absensiList' => collect()->paginate(10),
                'statAbsensi' => ['menunggu' => 0, 'disetujui' => 0, 'ditolak' => 0],
                'siswaIds'    => collect(),
                'totalSiswaBimbingan' => 0,
            ]);
        }

        // Ambil semua siswa_id yang ada dalam penempatan dengan guru ini
        $siswaIds = PenempatanMagang::where('guru_id', $guru->id)
            ->pluck('siswa_id');

        $filterStatus = $request->query('status_jurnal');

        // ============================================
        // Tab 1: Jurnal Kegiatan
        // ============================================
        $jurnalQuery = JurnalHarian::with(['siswa.profile', 'siswa.penempatan.tempatMagang'])
            ->whereIn('siswa_id', $siswaIds)
            ->latest('tanggal');

        if ($filterStatus && in_array($filterStatus, ['menunggu', 'disetujui', 'revisi'])) {
            $jurnalQuery->where('status_verifikasi', $filterStatus);
        }

        $jurnalList = $jurnalQuery->paginate(15, ['*'], 'jurnal_page');

        $statJurnal = [
            'menunggu'  => JurnalHarian::whereIn('siswa_id', $siswaIds)->where('status_verifikasi', 'menunggu')->count(),
            'disetujui' => JurnalHarian::whereIn('siswa_id', $siswaIds)->where('status_verifikasi', 'disetujui')->count(),
            'revisi'    => JurnalHarian::whereIn('siswa_id', $siswaIds)->where('status_verifikasi', 'revisi')->count(),
        ];

        // ============================================
        // Tab 2: Absensi Siswa
        // ============================================
        $filterStatusAbsensi = $request->query('status_absensi');

        $absensiQuery = Absensi::with(['siswa.profile', 'siswa.penempatan.tempatMagang'])
            ->whereIn('siswa_id', $siswaIds)
            ->latest('tanggal');

        if ($filterStatusAbsensi && in_array($filterStatusAbsensi, ['pending', 'disetujui', 'ditolak'])) {
            $absensiQuery->where('status_validasi', $filterStatusAbsensi);
        }

        $absensiList = $absensiQuery->paginate(15, ['*'], 'absensi_page');

        $statAbsensi = [
            'menunggu'  => Absensi::whereIn('siswa_id', $siswaIds)->where('status_validasi', 'pending')->count(),
            'disetujui' => Absensi::whereIn('siswa_id', $siswaIds)->where('status_validasi', 'disetujui')->count(),
            'ditolak'   => Absensi::whereIn('siswa_id', $siswaIds)->where('status_validasi', 'ditolak')->count(),
        ];

        return view('guru.jurnal', compact(
            'jurnalList', 'statJurnal',
            'absensiList', 'statAbsensi',
            'siswaIds',
        ) + [
            'totalSiswaBimbingan' => $siswaIds->count(),
        ]);
    }

    /**
     * Modal Validasi Jurnal: Setujui Jurnal / Minta Revisi
     */
    public function validasiJurnal(Request $request, JurnalHarian $jurnal)
    {
        // Pastikan jurnal milik siswa bimbingan guru ini
        $guru = auth()->user()->guru;
        if ($guru) {
            $siswaIds = PenempatanMagang::where('guru_id', $guru->id)->pluck('siswa_id');
            abort_unless($siswaIds->contains($jurnal->siswa_id), 403, 'Jurnal ini bukan dari siswa bimbingan Anda.');
        }

        $validated = $request->validate([
            'keputusan'    => ['required', 'in:disetujui,revisi'],
            'catatan_guru' => ['nullable', 'string', 'max:500'],
            'catatan'      => ['nullable', 'string', 'max:500'],
        ]);

        $catatan = $validated['catatan_guru'] ?? $validated['catatan'] ?? null;

        $jurnal->update([
            'status_verifikasi' => $validated['keputusan'],
            'catatan_guru'      => $catatan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Validasi jurnal berhasil disimpan.',
            'status'  => $validated['keputusan'],
        ]);
    }

    /**
     * Modal Validasi Absensi: khusus pengajuan Sakit/Izin — Setujui / Tolak
     */
    public function validasiAbsensi(Request $request, Absensi $absensi)
    {
        // Pastikan absensi milik siswa bimbingan guru ini
        $guru = auth()->user()->guru;
        if ($guru) {
            $siswaIds = PenempatanMagang::where('guru_id', $guru->id)->pluck('siswa_id');
            abort_unless($siswaIds->contains($absensi->siswa_id), 403, 'Absensi ini bukan dari siswa bimbingan Anda.');
        }

        $validated = $request->validate([
            'keputusan'    => ['required', 'in:disetujui,ditolak'],
            'catatan_guru' => ['nullable', 'string', 'max:500'],
            'catatan'      => ['nullable', 'string', 'max:500'],
        ]);

        $catatan = $validated['catatan_guru'] ?? $validated['catatan'] ?? null;

        $absensi->update([
            'status_validasi' => $validated['keputusan'],
            'catatan_guru'    => $catatan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Validasi absensi berhasil disimpan.',
            'status'  => $validated['keputusan'],
        ]);
    }
}