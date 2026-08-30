<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PenempatanMagang;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaBimbinganController extends Controller
{
    /**
     * Bimbingan: Siswa Bimbingan (/guru/siswa)
     */
    public function index()
    {
        $guru = auth()->user()->guru;

        $siswaBimbingan = PenempatanMagang::with(['siswa.profile', 'tempatMagang'])
            ->where('guru_id', $guru->id)
            ->get();

        return view('guru.siswa', compact('siswaBimbingan'));
    }

    /**
     * Modal Form Penilaian: simpan/revisi nilai akhir magang siswa
     */
    public function simpanNilai(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nilai_akhir' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $guru = auth()->user()->guru;

        // Pastikan penempatan ini memang di bawah bimbingan guru yang login
        $penempatan = PenempatanMagang::where('siswa_id', $siswa->id)
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        $penempatan->update(['nilai_akhir' => $validated['nilai_akhir']]);

        return response()->json([
            'message' => 'Nilai akhir berhasil disimpan.',
            'nilai'   => $penempatan->nilai_akhir,
        ]);
    }
}