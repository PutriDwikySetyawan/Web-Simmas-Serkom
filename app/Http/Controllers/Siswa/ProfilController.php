<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    /**
     * Tampilan Profil Siswa (/siswa/profil)
     */
    public function index()
    {
        $profile = auth()->user();
        $siswa = $profile->siswa;

        if (!$siswa) {
            abort(403, 'Data profil siswa tidak ditemukan.');
        }

        // Load relasi penempatan, tempat magang, guru pembimbing
        $siswa->load([
            'penempatan.tempatMagang',
            'penempatan.guru.profile',
            'pengajuan.tempatMagang',
        ]);

        $totalHadir = $siswa->absensi()->where('status', 'hadir')->count();
        $totalJurnal = $siswa->jurnalHarian()->count();
        $pengajuanAktif = $siswa->pengajuan()->latest()->first();

        return view('siswa.profil', compact(
            'profile',
            'siswa',
            'totalHadir',
            'totalJurnal',
            'pengajuanAktif'
        ));
    }

    /**
     * Update Informasi Profil & Password
     */
    public function update(Request $request)
    {
        $profile = auth()->user();
        $siswa = $profile->siswa;

        abort_if(!$siswa, 403, 'Akses tidak diizinkan.');

        $rules = [
            'nama' => ['required', 'string', 'max:255'],
        ];

        // Jika mengubah password
        if ($request->filled('password_baru') || $request->filled('password_lama') || $request->filled('password_baru_confirmation')) {
            $rules['password_lama'] = ['required', 'current_password'];
            $rules['password_baru'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $validated = $request->validate($rules, [
            'nama.required'                  => 'Nama lengkap wajib diisi.',
            'password_lama.required'         => 'Kata sandi saat ini wajib diisi untuk mengubah kata sandi.',
            'password_lama.current_password' => 'Kata sandi saat ini yang Anda masukkan salah.',
            'password_baru.required'         => 'Kata sandi baru wajib diisi.',
            'password_baru.min'              => 'Kata sandi baru minimal 8 karakter.',
            'password_baru.confirmed'        => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $profile->nama = $validated['nama'];

        if ($request->filled('password_baru')) {
            $profile->password = $validated['password_baru'];
        }

        $profile->save();

        ActivityLog::create([
            'level'       => 'info',
            'action_type' => 'UPDATE_PROFIL',
            'actor_email' => $profile->email,
            'actor_role'  => $profile->role ?? 'siswa',
            'ip_address'  => request()->ip(),
            'metadata'    => [
                'description' => "Siswa {$profile->nama} memperbarui pengaturan profil / kata sandi",
            ],
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengaturan profil berhasil disimpan.',
            ]);
        }

        return back()->with('success', 'Pengaturan profil berhasil disimpan.');
    }
}

