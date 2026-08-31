<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PenempatanMagang;
use App\Models\TempatMagang;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    /**
     * Utama: Pengajuan Magang (/siswa/pengajuan)
     */
    public function index()
    {
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan untuk akun ini.');
        }

        // Penempatan terakhir juga menjadi sumber status pengajuan siswa.
        $penempatan = $siswa->penempatan()->with(['tempatMagang', 'guru.profile'])->first();

        // ============================================
        // Dropdown DUDI mitra dengan info sisa kuota (untuk Modal Form Pengajuan)
        // ============================================
        $dudiList = TempatMagang::where('status_verifikasi', 'terverifikasi')
            ->orderBy('nama_perusahaan', 'asc')
            ->get();

        $pengajuanTerakhir = $penempatan;

        // Cek apakah siswa sudah punya penempatan resmi aktif / magang
        $sudahMagang = $siswa->status === 'sedang_magang' || $siswa->status === 'lulus' || ($penempatan && $penempatan->status_pengesahan === 'disahkan');

        return view('siswa.pengajuan', compact('siswa', 'pengajuanTerakhir', 'penempatan', 'dudiList', 'sudahMagang'));
    }

    /**
     * Modal Pengajuan: kirim pengajuan magang mandiri
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tempat_magang_id' => ['required', 'exists:tempat_magang,id'],
            'posisi'           => ['required', 'string', 'max:255'],
            'tanggal_mulai'    => ['required', 'date'],
            'tanggal_selesai'  => ['required', 'date', 'after:tanggal_mulai'],
        ], [
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

        // Validasi kuota otomatis
        if ($tempatMagang->sisa_kuota <= 0) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Kuota tempat magang ini sudah penuh.',
                    'errors'  => ['tempat_magang_id' => ['Kuota tempat magang ini sudah penuh.']],
                ], 422);
            }
            return back()->withErrors(['tempat_magang_id' => 'Kuota tempat magang ini sudah penuh.'])->withInput();
        }

        $penempatan = PenempatanMagang::create([
            'siswa_id'         => $siswa->id,
            'tempat_magang_id' => $validated['tempat_magang_id'],
            'posisi'           => $validated['posisi'],
            'tanggal_mulai'    => $validated['tanggal_mulai'],
            'tanggal_selesai'  => $validated['tanggal_selesai'],
            'status_pengesahan' => 'menunggu',
        ]);

        // Update status siswa jadi "pengajuan" jika status masih belum_magang
        if ($siswa->status === 'belum_magang') {
            $siswa->update(['status' => 'pengajuan']);
        }

        // Catat activity log
        ActivityLog::create([
            'level'       => 'info',
            'action_type' => 'PENGAJUAN_MAGANG',
            'actor_email' => auth()->user()->email ?? null,
            'actor_role'  => auth()->user()->role ?? 'siswa',
            'ip_address'  => request()->ip(),
            'metadata'    => [
                'description'   => "Siswa {$siswa->profile->nama} mengajukan magang di {$tempatMagang->nama_perusahaan} posisi {$validated['posisi']}",
                'penempatan_id' => $penempatan->id,
                'tempat_magang' => $tempatMagang->nama_perusahaan,
                'posisi'        => $validated['posisi'],
            ],
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Pengajuan magang berhasil dikirim dan sedang ditinjau sekolah.',
                'penempatan' => $penempatan->load('tempatMagang'),
            ]);
        }

        return redirect()->route('siswa.pengajuan.index')->with('success', 'Pengajuan magang berhasil dikirim.');
    }
}
