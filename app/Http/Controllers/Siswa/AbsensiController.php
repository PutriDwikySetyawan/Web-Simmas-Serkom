<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    /**
     * Kegiatan Magang: Absensi Harian (/siswa/absensi)
     */
    public function index(Request $request)
    {
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan untuk akun ini.');
        }

        // ============================================
        // Status kehadiran hari ini (Clock In/Out)
        // ============================================
        $absensiHariIni = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', today())
            ->first();

        // ============================================
        // Filter bulan (default bulan berjalan)
        // ============================================
        $bulanDipilih = $request->input('bulan', now()->format('Y-m'));
        $tahun = (int) substr($bulanDipilih, 0, 4);
        $bulan = (int) substr($bulanDipilih, 5, 2);

        // ============================================
        // Riwayat absensi bulan ini
        // ============================================
        $riwayatAbsensi = Absensi::where('siswa_id', $siswa->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'desc')
            ->get();

        // ============================================
        // Rekap Statistik Bulan Ini
        // ============================================
        $totalHadir = $riwayatAbsensi->where('status', 'hadir')->count();
        $totalSakit = $riwayatAbsensi->where('status', 'sakit')->count();
        $totalIzin  = $riwayatAbsensi->where('status', 'izin')->count();
        $totalAlfa  = $riwayatAbsensi->where('status', 'alfa')->count();

        return view('siswa.absensi', compact(
            'siswa',
            'absensiHariIni',
            'riwayatAbsensi',
            'bulanDipilih',
            'totalHadir',
            'totalSakit',
            'totalIzin',
            'totalAlfa'
        ));
    }

    /**
     * Modal Presensi: simpan absensi harian (Clock In / Clock Out) dengan foto bukti
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe'    => ['nullable', 'in:masuk,pulang'],
            'status'  => ['required', 'in:hadir,sakit,izin,alfa'],
            'tanggal' => ['required', 'date'],
            'jam'     => ['required', 'string'],
            'photo'   => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ], [
            'status.required'  => 'Status kehadiran wajib dipilih.',
            'tanggal.required' => 'Tanggal presensi wajib diisi.',
            'jam.required'     => 'Jam presensi wajib diisi.',
            'photo.image'      => 'File bukti harus berupa gambar.',
            'photo.max'        => 'Ukuran foto maksimal 4MB.',
        ]);

        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Profil siswa tidak ditemukan.'], 403);
            }
            return back()->with('error', 'Profil siswa tidak ditemukan.');
        }

        $tipe = $validated['tipe'] ?? 'masuk';

        // Cek record absensi untuk tanggal ini
        $absensi = Absensi::firstOrNew([
            'siswa_id' => $siswa->id,
            'tanggal'  => $validated['tanggal'],
        ]);

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('absensi', 'public');
            $photoUrl = $path;
        }

        if ($tipe === 'pulang') {
            // ============================================
            // Clock Out (Absen Pulang)
            // ============================================
            if (!$absensi->exists) {
                $absensi->status = $validated['status'];
                $absensi->status_validasi = ($validated['status'] === 'hadir') ? 'disetujui' : 'pending';
            }

            $absensi->jam_pulang = $validated['jam'];
            if ($photoUrl) {
                $absensi->photo_pulang_url = $photoUrl;
            }

            $pesan = 'Absen pulang (Clock Out) berhasil dicatat.';
            $actionType = 'ABSENSI_PULANG';
        } else {
            // ============================================
            // Clock In (Absen Masuk) / Izin / Sakit
            // ============================================
            $absensi->status = $validated['status'];
            $absensi->jam_masuk = $validated['jam'];
            if ($photoUrl) {
                $absensi->photo_masuk_url = $photoUrl;
            }

            // Sakit/Izin butuh validasi guru, Hadir otomatis disetujui
            $absensi->status_validasi = ($validated['status'] === 'hadir') ? 'disetujui' : 'pending';

            $pesan = ($validated['status'] === 'hadir')
                ? 'Absen masuk (Clock In) berhasil dicatat.'
                : 'Pengajuan ' . ucfirst($validated['status']) . ' berhasil dikirim dan menunggu validasi guru.';
            $actionType = 'ABSENSI_MASUK';
        }

        $absensi->save();

        // Catat activity log
        ActivityLog::create([
            'level'       => 'info',
            'action_type' => $actionType,
            'actor_email' => auth()->user()->email ?? null,
            'actor_role'  => auth()->user()->role ?? 'siswa',
            'ip_address'  => request()->ip(),
            'metadata'    => [
                'description' => "Siswa {$siswa->profile->nama} melakukan {$actionType} tanggal {$validated['tanggal']} jam {$validated['jam']}",
                'status'      => $validated['status'],
                'tanggal'     => $validated['tanggal'],
                'jam'         => $validated['jam'],
            ],
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $pesan,
                'absensi' => $absensi,
            ]);
        }

        return redirect()->route('siswa.absensi.index')->with('success', $pesan);
    }
}