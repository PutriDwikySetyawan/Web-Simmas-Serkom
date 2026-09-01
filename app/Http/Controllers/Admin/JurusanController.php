<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// Import Model yang digunakan dalam manajemen jurusan
use App\Models\ActivityLog;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    /**
     * READ: Menampilkan daftar data jurusan / program keahlian (/admin/jurusan)
     * Dilengkapi pencarian, filter status, relasi hitung kelas & guru, serta pagination
     */
    public function index(Request $request)
    {
        // Query jurusan beserta jumlah relasi kelas dan guru
        $query = Jurusan::withCount(['kelas', 'guru']);

        // Filter pencarian berdasarkan kata kunci
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_jurusan', 'like', "%{$search}%")
                  ->orWhere('nama_jurusan', 'like', "%{$search}%")
                  ->orWhere('kepala_jurusan', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter status aktif atau non-aktif
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('is_active', $request->status === 'aktif' ? 1 : 0);
        }

        // Pagination 10 baris per halaman
        $jurusanList = $query->orderBy('kode_jurusan', 'asc')
                             ->paginate(10)
                             ->withQueryString();

        // Data statistik widget ringkasan
        $stats = [
            'total_jurusan' => Jurusan::count(),
            'jurusan_aktif' => Jurusan::where('is_active', true)->count(),
            'total_kelas'   => Kelas::count(),
            'total_guru'    => Guru::where('is_active', true)->count(),
        ];

        // Mengambil daftar guru aktif untuk pilihan Kepala Program Keahlian
        $guruList = Guru::with('profile')->where('is_active', true)->get();

        // Mengirimkan variabel ke view admin/jurusan.blade.php
        return view('admin.jurusan', compact('jurusanList', 'stats', 'guruList'));
    }

    /**
     * CREATE: Menyimpan data jurusan baru ke database (POST /admin/jurusan)
     */
    public function store(Request $request)
    {
        // Validasi input data jurusan baru
        $validated = $request->validate([
            'kode_jurusan'   => ['required', 'string', 'max:30', 'unique:jurusan,kode_jurusan'],
            'nama_jurusan'   => ['required', 'string', 'max:150'],
            'kepala_jurusan' => ['nullable', 'string', 'max:255'],
            'deskripsi'      => ['nullable', 'string', 'max:1000'],
        ]);

        // Mengubah kode jurusan menjadi huruf kapital (UPPERCASE)
        $validated['kode_jurusan'] = strtoupper(trim($validated['kode_jurusan']));
        $validated['is_active'] = true;

        // Simpan ke database tabel jurusan
        $jurusan = Jurusan::create($validated);

        // Catat ke log aktivitas sistem
        $this->logActivity('CREATE_JURUSAN', "Menambahkan jurusan baru: {$validated['kode_jurusan']} - {$validated['nama_jurusan']}");

        // Respon JSON untuk AJAX
        return response()->json([
            'message' => 'Jurusan berhasil ditambahkan.',
            'jurusan' => $jurusan,
        ]);
    }

    /**
     * UPDATE: Memperbarui data jurusan (PUT/PATCH /admin/jurusan/{jurusan})
     */
    public function update(Request $request, Jurusan $jurusan)
    {
        // Validasi data perubahan (pengecualian unique untuk ID jurusan yang sedang diedit)
        $validated = $request->validate([
            'kode_jurusan'   => ['required', 'string', 'max:30', 'unique:jurusan,kode_jurusan,' . $jurusan->id . ',id'],
            'nama_jurusan'   => ['required', 'string', 'max:150'],
            'kepala_jurusan' => ['nullable', 'string', 'max:255'],
            'deskripsi'      => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['kode_jurusan'] = strtoupper(trim($validated['kode_jurusan']));
        $oldKode = $jurusan->kode_jurusan;

        // Simpan perubahan data jurusan
        $jurusan->update($validated);

        // Jika kode jurusan diubah, sinkronkan data di tabel kelas & guru terkait
        if ($oldKode !== $validated['kode_jurusan']) {
            Kelas::where('jurusan', $oldKode)->update(['jurusan' => $validated['kode_jurusan']]);
            Guru::where('jurusan', $oldKode)->update(['jurusan' => $validated['kode_jurusan']]);
        }

        // Catat ke log aktivitas sistem
        $this->logActivity('UPDATE_JURUSAN', "Memperbarui jurusan: {$validated['kode_jurusan']}");

        // Respon JSON sukses
        return response()->json([
            'message' => 'Data jurusan berhasil diperbarui.',
            'jurusan' => $jurusan,
        ]);
    }

    /**
     * TOGGLE STATUS: Mengubah status Aktif / Nonaktif jurusan (PATCH /admin/jurusan/{jurusan}/status)
     */
    public function updateStatus(Request $request, Jurusan $jurusan)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        // Update status aktif
        $jurusan->update(['is_active' => $validated['is_active']]);
        $statusText = $validated['is_active'] ? 'diaktifkan' : 'dinonaktifkan';

        // Catat log aktivitas
        $this->logActivity('UPDATE_STATUS_JURUSAN', "Mengubah status jurusan {$jurusan->kode_jurusan} menjadi {$statusText}");

        return response()->json([
            'message' => "Jurusan {$jurusan->kode_jurusan} berhasil {$statusText}.",
        ]);
    }

    /**
     * DELETE: Menghapus data jurusan (DELETE /admin/jurusan/{jurusan})
     * Dilengkapi proteksi integritas data (tidak bisa dihapus jika ada kelas atau guru terkait)
     */
    public function destroy(Jurusan $jurusan)
    {
        // Cek keterkaitan dengan tabel kelas dan guru
        $hasKelas = Kelas::where('jurusan', $jurusan->kode_jurusan)->exists();
        $hasGuru  = Guru::where('jurusan', $jurusan->kode_jurusan)->exists();

        if ($hasKelas || $hasGuru) {
            $alasan = $hasKelas ? 'kelas' : 'guru';
            // Tolak jika ada data terkait (HTTP 422)
            return response()->json([
                'message' => "Jurusan \"{$jurusan->kode_jurusan}\" masih memiliki data {$alasan} terkait dan tidak dapat dihapus.",
            ], 422);
        }

        $kode = $jurusan->kode_jurusan;
        // Hapus data dari tabel
        $jurusan->delete();

        // Catat log aktivitas
        $this->logActivity('DELETE_JURUSAN', "Menghapus jurusan: {$kode}");

        return response()->json([
            'message' => "Jurusan \"{$kode}\" berhasil dihapus.",
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
            'target_type' => 'jurusan',
            'description' => $description,
            'created_at'  => now(),
        ]);
    }
}
