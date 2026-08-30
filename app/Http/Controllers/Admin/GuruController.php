<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Guru;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuruController extends Controller
{
    /**
     * Master Data: Data Guru (/admin/guru)
     */
    public function index(Request $request)
    {
        $query = Guru::with('profile')->withCount('penempatan');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nip', 'like', "%{$search}%")
                ->orWhereHas('profile', fn ($q) => $q->where('nama', 'like', "%{$search}%"));
        }

        if ($request->filled('jurusan') && $request->jurusan !== 'semua') {
            $query->where('jurusan', $request->jurusan);
        }

        $guruList = $query->paginate(10);

        return view('admin.guru', compact('guruList'));
    }

    /**
     * Simpan guru baru — akun login otomatis dibuat dengan password acak
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'    => ['required', 'string', 'max:255'],
            'nip'     => ['required', 'digits:18', 'unique:guru,nip'],
            'jurusan' => ['required', 'string', 'max:255'],
        ]);

        // Email dibuat otomatis dari nama, password acak 8 karakter
        $emailSlug   = Str::slug($validated['nama'], '.');
        $email       = "{$emailSlug}@smk.sch.id";
        $rawPassword = 'Guru' . Str::random(6);

        $profile = Profile::create([
            'nama'     => $validated['nama'],
            'email'    => $email,
            'password' => Hash::make($rawPassword),
            'role'     => 'guru',
        ]);

        $guru = Guru::create([
            'user_id'   => $profile->id,
            'nip'       => $validated['nip'],
            'jurusan'   => $validated['jurusan'],
            'is_active' => true,
        ]);

        $this->logActivity('CREATE_GURU', "Menambahkan guru baru: {$validated['nama']}");

        // Kredensial dikirim balik untuk ditampilkan di Modal "Akun Guru Berhasil Dibuat"
        return response()->json([
            'message'  => 'Guru berhasil ditambahkan.',
            'guru'     => $guru->load('profile'),
            'kredensial' => [
                'email'    => $email,
                'password' => $rawPassword,
            ],
        ]);
    }

    /**
     * Update data guru (NIP bersifat read-only, tidak divalidasi ulang)
     */
    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nama'    => ['required', 'string', 'max:255'],
            'jurusan' => ['required', 'string', 'max:255'],
        ]);

        $guru->profile()->update(['nama' => $validated['nama']]);
        $guru->update(['jurusan' => $validated['jurusan']]);

        $this->logActivity('UPDATE_GURU', "Memperbarui data guru: {$validated['nama']}");

        return response()->json(['message' => 'Data guru berhasil diperbarui.']);
    }

    /**
     * Ubah status Aktif/Nonaktif guru
     */
    public function updateStatus(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $guru->update(['is_active' => $validated['is_active']]);

        $status = $validated['is_active'] ? 'Aktif' : 'Nonaktif';
        $this->logActivity('UPDATE_STATUS_GURU', "Mengubah status guru {$guru->profile->nama} menjadi {$status}");

        return response()->json(['message' => "Status guru berhasil diubah menjadi {$status}."]);
    }

    /**
     * Hapus data guru — ditolak kalau masih membimbing siswa aktif
     */
    public function destroy(Guru $guru)
    {
        $sedangMembimbing = $guru->penempatan()
            ->where('status_pengesahan', '!=', 'lulus_magang')
            ->exists();

        if ($sedangMembimbing) {
            return response()->json([
                'message' => 'Guru ini masih membimbing siswa aktif dan tidak dapat dihapus.',
            ], 422);
        }

        $nama = $guru->profile->nama;
        $guru->profile()->delete(); // cascade akan ikut hapus data guru
        $guru->delete();

        $this->logActivity('DELETE_GURU', "Menghapus guru: {$nama}");

        return response()->json(['message' => 'Guru berhasil dihapus.']);
    }

    /**
     * Helper pencatatan log aktivitas (dipakai berulang di semua controller Admin)
     */
    private function logActivity(string $actionType, string $description = null): void
    {
        ActivityLog::create([
            'level'       => 'info',
            'action_type' => $actionType,
            'actor_email' => auth()->user()->email ?? null,
            'actor_role'  => auth()->user()->role ?? null,
            'ip_address'  => request()->ip(),
            'metadata'    => $description ? ['description' => $description] : null,
        ]);
    }
}