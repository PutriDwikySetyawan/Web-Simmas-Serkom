<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
// Import Model untuk absensi, log aktivitas, dan upload storage
use App\Models\Absensi;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    /**
     * READ: Menampilkan Halaman Riwayat & Presensi Harian Siswa (/siswa/absensi)
     */
    public function index(Request $request)
    {
        // Mengambil data relasi profil siswa yang sedang login
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan untuk akun ini.');
        }

        // ============================================
        // Status Kehadiran Hari Ini (Clock In / Clock Out)
        // ============================================
        $absensiHariIni = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', today())
            ->first();

        // ============================================
        // Filter Berdasarkan Bulan (Default: Bulan & Tahun Berjalan)
        // ============================================
        $bulanDipilih = $request->input('bulan', now()->format('Y-m'));
        $tahun = (int) substr($bulanDipilih, 0, 4);
        $bulan = (int) substr($bulanDipilih, 5, 2);

        // ============================================
        // Mengambil Riwayat Absensi Sesuai Bulan yang Dipilih
        // ============================================
        $riwayatAbsensi = Absensi::where('siswa_id', $siswa->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'desc')
            ->get();

        // ============================================
        // Rekap Statistik Kehadiran Bulanan
        // ============================================
        $totalHadir = $riwayatAbsensi->where('status', 'hadir')->count();
        $totalSakit = $riwayatAbsensi->where('status', 'sakit')->count();
        $totalIzin  = $riwayatAbsensi->where('status', 'izin')->count();
        $totalAlfa  = $riwayatAbsensi->where('status', 'alfa')->count();

        // Mengirimkan variabel ke view siswa/absensi.blade.php
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
     * CREATE / UPDATE: Menyimpan presensi harian (Clock In / Clock Out / Izin / Sakit)
     * Dilengkapi fitur upload foto bukti presensi ke storage publik
     */
    public function store(Request $request)
    {
        // Validasi input presensi
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

        // Mencari atau membuat record absensi baru untuk tanggal ini
        $absensi = Absensi::firstOrNew([
            'siswa_id' => $siswa->id,
            'tanggal'  => $validated['tanggal'],
        ]);

        // Upload foto selfie/bukti kehadiran jika diunggah
        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('absensi', 'public');
            $photoUrl = $path;
        }

        if ($tipe === 'pulang') {
            // ============================================
            // PROSES CLOCK OUT (ABSEN PULANG)
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
            // PROSES CLOCK IN (ABSEN MASUK) / IZIN / SAKIT
            // ============================================
            $absensi->status = $validated['status'];
            $absensi->jam_masuk = $validated['jam'];
            if ($photoUrl) {
                $absensi->photo_masuk_url = $photoUrl;
            }

            // Status 'hadir' otomatis disetujui, status 'sakit'/'izin' perlu validasi guru pembimbing
            $absensi->status_validasi = ($validated['status'] === 'hadir') ? 'disetujui' : 'pending';

            $pesan = ($validated['status'] === 'hadir')
                ? 'Absen masuk (Clock In) berhasil dicatat.'
                : 'Pengajuan ' . ucfirst($validated['status']) . ' berhasil dikirim dan menunggu validasi guru.';
            $actionType = 'ABSENSI_MASUK';
        }

        // Simpan data absensi ke database
        $absensi->save();

        // Mencatat log aktivitas absensi ke tabel activity_logs
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

        // Mengembalikan respon JSON untuk Fetch/AJAX
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