<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Guru;
use App\Models\PenempatanMagang;
use App\Models\Profile;
use App\Models\Siswa;
use App\Models\TempatMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SiswaController extends Controller
{
    /**
     * Master Data: Data Siswa (/admin/siswa)
     */
    public function index(Request $request)
    {
        $query = Siswa::with(['profile', 'penempatan.tempatMagang', 'penempatan.guru.profile']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nis', 'like', "%{$search}%")
                ->orWhereHas('profile', fn ($q) => $q->where('nama', 'like', "%{$search}%"));
        }

        if ($request->filled('kelas') && $request->kelas !== 'semua') {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $siswaList = $query->paginate(10);

        // Data pendukung untuk dropdown Modal Plotting
        $guruList  = Guru::with('profile')->where('is_active', true)->get();
        $dudiList  = TempatMagang::where('status_verifikasi', 'terverifikasi')->get();

        return view('admin.siswa', compact('siswaList', 'guruList', 'dudiList'));
    }

    /**
     * Simpan siswa baru — akun login otomatis dibuat
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'  => ['required', 'string', 'max:255'],
            'nis'   => ['required', 'numeric', 'unique:siswa,nis'],
            'kelas' => ['required', 'string', 'max:50'],
        ]);

        $email       = "{$validated['nis']}@siswa.smk.sch.id";
        $rawPassword = 'Siswa' . Str::random(6);

        $profile = Profile::create([
            'nama'     => $validated['nama'],
            'email'    => $email,
            'password' => Hash::make($rawPassword),
            'role'     => 'siswa',
        ]);

        $siswa = Siswa::create([
            'user_id'   => $profile->id,
            'nis'       => $validated['nis'],
            'kelas'     => $validated['kelas'],
            'status'    => 'belum_magang',
            'is_active' => true,
        ]);

        $this->logActivity('CREATE_SISWA', "Menambahkan siswa baru: {$validated['nama']}");

        return response()->json([
            'message' => 'Siswa berhasil ditambahkan.',
            'siswa'   => $siswa->load('profile'),
            'kredensial' => [
                'email'    => $email,
                'password' => $rawPassword,
            ],
        ]);
    }

    /**
     * Update identitas dan kelas siswa
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nama'  => ['required', 'string', 'max:255'],
            'kelas' => ['required', 'string', 'max:50'],
        ]);

        $siswa->profile()->update(['nama' => $validated['nama']]);
        $siswa->update(['kelas' => $validated['kelas']]);

        $this->logActivity('UPDATE_SISWA', "Memperbarui data siswa: {$validated['nama']}");

        return response()->json(['message' => 'Data siswa berhasil diperbarui.']);
    }

    /**
     * Ubah status Aktif/Nonaktif akun siswa
     */
    public function updateStatus(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $siswa->update(['is_active' => $validated['is_active']]);

        $status = $validated['is_active'] ? 'Aktif' : 'Nonaktif';
        $this->logActivity('UPDATE_STATUS_SISWA', "Mengubah status siswa {$siswa->profile->nama} menjadi {$status}");

        return response()->json(['message' => "Status siswa berhasil diubah menjadi {$status}."]);
    }

    /**
     * Modal Plotting: menetapkan guru pembimbing + tempat magang untuk siswa
     */
    public function plotting(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'guru_id'          => ['required', 'exists:guru,id'],
            'tempat_magang_id' => ['required', 'exists:tempat_magang,id'],
            'tanggal_mulai'    => ['required', 'date'],
            'tanggal_selesai'  => ['required', 'date', 'after:tanggal_mulai'],
        ]);

        $tempatMagang = TempatMagang::findOrFail($validated['tempat_magang_id']);

        // Validasi kuota otomatis
        if ($tempatMagang->sisaKuota() <= 0) {
            return response()->json([
                'message' => 'Kuota tempat magang ini sudah penuh.',
            ], 422);
        }

        PenempatanMagang::create([
            'siswa_id'         => $siswa->id,
            'tempat_magang_id' => $validated['tempat_magang_id'],
            'guru_id'          => $validated['guru_id'],
            'tanggal_mulai'    => $validated['tanggal_mulai'],
            'tanggal_selesai'  => $validated['tanggal_selesai'],
            'status_pengesahan' => 'belum_disahkan',
        ]);

        $siswa->update(['status' => 'sedang_magang']);

        $this->logActivity('PLOTTING_SISWA', "Plotting siswa {$siswa->profile->nama} ke {$tempatMagang->nama_perusahaan}");

        return response()->json(['message' => 'Plotting berhasil disimpan.']);
    }

    /**
     * Hapus data siswa beserta cascade data terkait (absensi, jurnal, penempatan)
     */
    public function destroy(Siswa $siswa)
    {
        $nama = $siswa->profile->nama;
        $siswa->profile()->delete(); // cascade FK akan ikut hapus siswa, absensi, jurnal, dst
        $siswa->delete();

        $this->logActivity('DELETE_SISWA', "Menghapus siswa: {$nama}");

        return response()->json(['message' => 'Siswa berhasil dihapus.']);
    }

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