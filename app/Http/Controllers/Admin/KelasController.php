<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// Import Model yang digunakan dalam manajemen kelas
use App\Models\ActivityLog;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * READ: Menampilkan daftar data kelas (/admin/kelas)
     * Dilengkapi fitur pencarian, filter tingkat/jurusan/status, statistik, dan pagination
     */
    public function index(Request $request)
    {
        // Query dasar kelas beserta relasi hitung jumlah siswa terdaftar
        $query = Kelas::withCount('siswa');

        // Filter pencarian berdasarkan kata kunci
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kelas', 'like', "%{$search}%")
                  ->orWhere('jurusan', 'like', "%{$search}%")
                  ->orWhere('wali_kelas', 'like', "%{$search}%")
                  ->orWhere('tahun_ajaran', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan tingkat (X, XI, XII)
        if ($request->filled('tingkat') && $request->tingkat !== 'semua') {
            $query->where('tingkat', $request->tingkat);
        }

        // Filter berdasarkan jurusan
        if ($request->filled('jurusan') && $request->jurusan !== 'semua') {
            $query->where('jurusan', $request->jurusan);
        }

        // Filter berdasarkan status aktif/non-aktif
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('is_active', $request->status === 'aktif' ? 1 : 0);
        }

        // Eksekusi pagination 10 data per halaman dengan menjaga query string filter
        $kelasList = $query->orderBy('tingkat', 'asc')
                           ->orderBy('nama_kelas', 'asc')
                           ->paginate(10)
                           ->withQueryString();

        // Data statistik ringkas untuk widget counter di bagian atas
        $stats = [
            'total_kelas'    => Kelas::count(),
            'kelas_aktif'    => Kelas::where('is_active', true)->count(),
            'total_jurusan'  => Jurusan::count() ?: Kelas::distinct('jurusan')->count('jurusan'),
            'total_siswa'    => Siswa::count(),
        ];

        // Mengambil daftar jurusan aktif dan daftar guru untuk pilihan dropdown form
        $jurusanList = Jurusan::where('is_active', true)->orderBy('kode_jurusan')->get();
        $guruList    = Guru::with('profile')->where('is_active', true)->get();

        // Mengirim data ke view admin/kelas.blade.php
        return view('admin.kelas', compact('kelasList', 'stats', 'jurusanList', 'guruList'));
    }

    /**
     * CREATE: Menyimpan data kelas baru ke database (POST /admin/kelas)
     */
    public function store(Request $request)
    {
        // Validasi input kelas baru
        $validated = $request->validate([
            'nama_kelas'   => ['required', 'string', 'max:50', 'unique:kelas,nama_kelas'],
            'tingkat'      => ['required', 'string', 'max:10'],
            'jurusan'      => ['required', 'string', 'max:100'],
            'wali_kelas'   => ['nullable', 'string', 'max:255'],
            'tahun_ajaran' => ['nullable', 'string', 'max:20'],
            'kapasitas'    => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        // Memberikan nilai kapasitas default 36 jika tidak diisi
        $validated['kapasitas'] = $validated['kapasitas'] ?? 36;
        $validated['is_active'] = true;

        // Insert data ke tabel kelas
        $kelas = Kelas::create($validated);

        // Catat ke log aktivitas sistem
        $this->logActivity('CREATE_KELAS', "Menambahkan kelas baru: {$validated['nama_kelas']}");

        // Respon JSON untuk AJAX/Modal
        return response()->json([
            'message' => 'Kelas berhasil ditambahkan.',
            'kelas'   => $kelas,
        ]);
    }

    /**
     * UPDATE: Memperbarui data kelas yang sudah ada (PUT/PATCH /admin/kelas/{kelas})
     */
    public function update(Request $request, Kelas $kelas)
    {
        // Validasi data perubahan (pengecualian unique untuk ID kelas yang sedang diedit)
        $validated = $request->validate([
            'nama_kelas'   => ['required', 'string', 'max:50', 'unique:kelas,nama_kelas,' . $kelas->id . ',id'],
            'tingkat'      => ['required', 'string', 'max:10'],
            'jurusan'      => ['required', 'string', 'max:100'],
            'wali_kelas'   => ['nullable', 'string', 'max:255'],
            'tahun_ajaran' => ['nullable', 'string', 'max:20'],
            'kapasitas'    => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $validated['kapasitas'] = $validated['kapasitas'] ?? 36;
        $oldNamaKelas = $kelas->nama_kelas;

        // Update data kelas
        $kelas->update($validated);

        // Jika nama kelas berubah, sinkronkan nama kelas pada data siswa yang bersangkutan
        if ($oldNamaKelas !== $validated['nama_kelas']) {
            Siswa::where('kelas', $oldNamaKelas)->update(['kelas' => $validated['nama_kelas']]);
        }

        // Catat ke log aktivitas sistem
        $this->logActivity('UPDATE_KELAS', "Memperbarui data kelas: {$validated['nama_kelas']}");

        // Respon JSON sukses
        return response()->json([
            'message' => 'Data kelas berhasil diperbarui.',
            'kelas'   => $kelas,
        ]);
    }

    /**
     * TOGGLE STATUS: Mengubah status Aktif / Nonaktif kelas (PATCH /admin/kelas/{kelas}/status)
     */
    public function updateStatus(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        // Simpan perubahan status aktif/non-aktif
        $kelas->update(['is_active' => $validated['is_active']]);
        $statusText = $validated['is_active'] ? 'diaktifkan' : 'dinonaktifkan';

        // Catat ke log aktivitas sistem
        $this->logActivity('UPDATE_STATUS_KELAS', "Mengubah status kelas {$kelas->nama_kelas} menjadi {$statusText}");

        return response()->json([
            'message' => "Kelas {$kelas->nama_kelas} berhasil {$statusText}.",
        ]);
    }

    /**
     * DELETE: Menghapus data kelas dari database (DELETE /admin/kelas/{kelas})
     * Dilengkapi proteksi integritas data (tidak bisa dihapus jika ada siswa)
     */
    public function destroy(Kelas $kelas)
    {
        // Cek apakah masih ada siswa yang terdaftar di kelas ini
        $hasSiswa = Siswa::where('kelas', $kelas->nama_kelas)->exists();

        if ($hasSiswa) {
            // Tolak penghapusan jika masih ada siswa terkait (HTTP 422)
            return response()->json([
                'message' => "Kelas \"{$kelas->nama_kelas}\" masih memiliki siswa terdaftar dan tidak dapat dihapus.",
            ], 422);
        }

        $nama = $kelas->nama_kelas;
        // Hapus record dari database
        $kelas->delete();

        // Catat ke log aktivitas sistem
        $this->logActivity('DELETE_KELAS', "Menghapus kelas: {$nama}");

        return response()->json([
            'message' => "Kelas \"{$nama}\" berhasil dihapus.",
        ]);
    }

    /**
     * HELPER: Fungsi internal pencatatan riwayat log aktivitas pengguna
     */
    private function logActivity(string $actionType, string $description = null): void
    {
        ActivityLog::create([
            'level'       => 'info',
            'action_type' => $actionType,
            'actor_email' => auth()->user()->email ?? null,
            'actor_role'  => auth()->user()->role ?? null,
            'target_type' => 'kelas',
            'description' => $description,
            'created_at'  => now(),
        ]);
    }
}
