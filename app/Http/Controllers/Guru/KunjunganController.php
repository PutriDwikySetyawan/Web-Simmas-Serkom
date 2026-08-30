<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\TempatMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KunjunganController extends Controller
{
    /**
     * Bimbingan: Kunjungan Lapangan (/guru/kunjungan)
     */
    public function index()
    {
        $guru = auth()->user()->guru;

        // ============================================
        // Timeline kronologis kunjungan guru ini
        // ============================================
        $kunjunganList = Kunjungan::with('tempatMagang')
            ->where('guru_id', $guru->id)
            ->latest('tanggal')
            ->get();

        // ============================================
        // 3 Stat Card
        // ============================================
        $totalKunjungan = $kunjunganList->count();
        $kunjunganBulanIni = $kunjunganList->filter(
            fn ($k) => \Carbon\Carbon::parse($k->tanggal)->isCurrentMonth()
        )->count();
        $totalDudiDikunjungi = $kunjunganList->pluck('tempat_magang_id')->unique()->count();

        // Dropdown DUDI untuk Form Tambah Kunjungan
        $dudiList = TempatMagang::where('status_verifikasi', 'terverifikasi')->get();

        return view('guru.kunjungan', compact(
            'kunjunganList', 'totalKunjungan', 'kunjunganBulanIni',
            'totalDudiDikunjungi', 'dudiList'
        ));
    }

    /**
     * Simpan catatan kunjungan industri baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tempat_magang_id' => ['required', 'exists:tempat_magang,id'],
            'tanggal'          => ['required', 'date'],
            'catatan'          => ['required', 'string'],
            'photo'            => ['nullable', 'image', 'max:2048'],
        ]);

        $guru = auth()->user()->guru;

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $photoUrl = $request->file('photo')->store('kunjungan', 'public');
        }

        $kunjungan = Kunjungan::create([
            'guru_id'          => $guru->id,
            'tempat_magang_id' => $validated['tempat_magang_id'],
            'tanggal'          => $validated['tanggal'],
            'catatan'          => $validated['catatan'],
            'photo_url'        => $photoUrl,
        ]);

        return response()->json([
            'message'   => 'Kunjungan berhasil dicatat.',
            'kunjungan' => $kunjungan->load('tempatMagang'),
        ]);
    }

    /**
     * Update catatan/evaluasi hasil kunjungan
     */
    public function update(Request $request, Kunjungan $kunjungan)
    {
        $validated = $request->validate([
            'tempat_magang_id' => ['required', 'exists:tempat_magang,id'],
            'tanggal'          => ['required', 'date'],
            'catatan'          => ['required', 'string'],
        ]);

        $kunjungan->update($validated);

        return response()->json(['message' => 'Catatan kunjungan berhasil diperbarui.']);
    }

    /**
     * Hapus entri riwayat kunjungan
     */
    public function destroy(Kunjungan $kunjungan)
    {
        if ($kunjungan->photo_url) {
            Storage::disk('public')->delete($kunjungan->photo_url);
        }

        $kunjungan->delete();

        return response()->json(['message' => 'Catatan kunjungan berhasil dihapus.']);
    }
}