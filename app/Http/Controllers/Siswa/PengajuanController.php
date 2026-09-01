<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
// Import Model untuk pengajuan, penempatan, dan log aktivitas
use App\Models\ActivityLog;
use App\Models\PengajuanMagang;
use App\Models\PenempatanMagang;
use App\Models\TempatMagang;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    /**
     * READ: Menampilkan Halaman Pengajuan Magang Siswa (/siswa/pengajuan)
     */
    public function index()
    {
        // Mengambil data relasi siswa dari akun user yang sedang login
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan untuk akun ini.');
        }

        // Mengambil riwayat pengajuan magang terakhir dari siswa
        $pengajuanTerakhir = PengajuanMagang::where('siswa_id', $siswa->id)
            ->with(['tempatMagang'])
            ->latest()
            ->first();

        // Mengambil data penempatan resmi jika sudah ditempatkan oleh pihak admin
        $penempatan = $siswa->penempatan()->with(['tempatMagang', 'guru.profile'])->first();

        // Mengambil daftar perusahaan DUDI yang sudah terverifikasi
        $dudiList = TempatMagang::where('status_verifikasi', 'terverifikasi')
            ->orderBy('nama_perusahaan', 'asc')
            ->get();

        // Memeriksa apakah siswa sudah berstatus aktif magang atau lulus
        $sudahMagang = $siswa->status === 'sedang_magang' || $siswa->status === 'lulus' || ($penempatan && $penempatan->status_pengesahan === 'disahkan');

        // Mengirimkan variabel ke view siswa/pengajuan.blade.php
        return view('siswa.pengajuan', compact('siswa', 'pengajuanTerakhir', 'penempatan', 'dudiList', 'sudahMagang'));
    }

    /**
     * CREATE: Memproses pengiriman formulir pengajuan tempat magang mandiri (POST /siswa/pengajuan)
     */
    public function store(Request $request)
    {
        // Validasi input data pengajuan dari form modal
        $validated = $request->validate([
            'tempat_magang_id' => ['required', 'exists:tempat_magang,id'],
            'posisi'           => ['required', 'string', 'max:255'],
            'tanggal_mulai'    => ['required', 'date'],
            'tanggal_selesai'  => ['required', 'date', 'after:tanggal_mulai'],
        ], [
            // Kustomisasi pesan error dalam Bahasa Indonesia
            'tempat_magang_id.required' => 'Silakan pilih perusahaan mitra DUDI.',
            'posisi.required'          => 'Posisi / divisi yang diminati wajib diisi.',
            'tanggal_mulai.required'   => 'Tanggal mulai magang wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai magang wajib diisi.',
            'tanggal_selesai.after'    => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Profil siswa tidak ditemukan.'], 403);
            }
            return back()->with('error', 'Profil siswa tidak ditemukan.');
        }

        $tempatMagang = TempatMagang::findOrFail($validated['tempat_magang_id']);

        // Validasi sisa kuota penerimaan pada tempat magang yang dipilih
        if ($tempatMagang->sisa_kuota <= 0) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Kuota tempat magang ini sudah penuh.',
                    'errors'  => ['tempat_magang_id' => ['Kuota tempat magang ini sudah penuh.']],
                ], 422);
            }
            return back()->withErrors(['tempat_magang_id' => 'Kuota tempat magang ini sudah penuh.'])->withInput();
        }

        // Menyimpan data pengajuan baru ke tabel pengajuan_magang
        $pengajuan = PengajuanMagang::create([
            'siswa_id'         => $siswa->id,
            'tempat_magang_id' => $validated['tempat_magang_id'],
            'posisi'           => $validated['posisi'],
            'tanggal_mulai'    => $validated['tanggal_mulai'],
            'tanggal_selesai'  => $validated['tanggal_selesai'],
            'status'           => 'menunggu', // Status awal: menunggu verifikasi admin/sekolah
        ]);

        // Memperbarui status siswa menjadi "pengajuan" jika sebelumnya "belum_magang"
        if ($siswa->status === 'belum_magang') {
            $siswa->update(['status' => 'pengajuan']);
        }

        // Mencatat log aktivitas pengajuan ke tabel activity_logs
        ActivityLog::create([
            'level'       => 'info',
            'action_type' => 'PENGAJUAN_MAGANG',
            'actor_email' => auth()->user()->email ?? null,
            'actor_role'  => auth()->user()->role ?? 'siswa',
            'ip_address'  => request()->ip(),
            'metadata'    => [
                'description'   => "Siswa {$siswa->profile->nama} mengajukan magang di {$tempatMagang->nama_perusahaan} posisi {$validated['posisi']}",
                'pengajuan_id'  => $pengajuan->id,
                'tempat_magang' => $tempatMagang->nama_perusahaan,
                'posisi'        => $validated['posisi'],
            ],
        ]);

        // Mengembalikan respon JSON jika request dikirim via Fetch / AJAX
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Pengajuan magang berhasil dikirim dan sedang ditinjau sekolah.',
                'pengajuan' => $pengajuan->load('tempatMagang'),
                'penempatan' => $pengajuan->load('tempatMagang'),
            ]);
        }

        return redirect()->route('siswa.pengajuan.index')->with('success', 'Pengajuan magang berhasil dikirim.');
    }
}
