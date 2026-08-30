<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\TempatMagang;
use App\Models\PenempatanMagang;
use Illuminate\Http\Request;

class DudiController extends Controller
{
   /**
 * Master Data: Data DUDI (/admin/dudi)
 */
public function index(Request $request)
{
    // ------------------------------------------------------------
    // QUERY TABEL UTAMA (tidak diubah dari punya kamu)
    // ------------------------------------------------------------
    $query = TempatMagang::withCount(['penempatan as siswa_aktif_count' => function ($q) {
        $q->where('status_pengesahan', '!=', 'lulus_magang');
    }]);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where('nama_perusahaan', 'like', "%{$search}%")
            ->orWhere('alamat', 'like', "%{$search}%");
    }

    if ($request->filled('status') && $request->status !== 'semua') {
        $query->where('status_verifikasi', $request->status);
    }

    $dudiList = $query->paginate(10)->withQueryString();

    // ------------------------------------------------------------
    // STAT CARD (data realtime dari DB, bukan dummy)
    // ------------------------------------------------------------
    $stats = [
        'total_mitra'       => TempatMagang::count(),
        'terverifikasi'     => TempatMagang::where('status_verifikasi', 'terverifikasi')->count(),
        'menunggu_validasi' => TempatMagang::where('status_verifikasi', 'belum_diverifikasi')->count(),
        'siswa_ditempatkan' => PenempatanMagang::where('status_pengesahan', '!=', 'lulus_magang')->count(),
    ];

    return view('admin.dudi', compact('dudiList', 'stats'));
}
    /**
     * Simpan mitra DUDI baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan'    => ['required', 'string', 'max:255'],
            'bidang_usaha'       => ['required', 'string', 'max:255'],
            'nama_pic'           => ['required', 'string', 'max:255'],
            'kontak_pic'         => ['required', 'string', 'max:20'],
            'kuota'              => ['required', 'integer', 'min:1'],
            'status_verifikasi'  => ['required', 'in:terverifikasi,belum_diverifikasi'],
            'alamat'             => ['required', 'string'],
        ]);

        $dudi = TempatMagang::create($validated);

        $this->logActivity('CREATE_DUDI', "Menambahkan mitra DUDI: {$validated['nama_perusahaan']}");

        return response()->json([
            'message' => 'Mitra DUDI berhasil ditambahkan.',
            'dudi'    => $dudi,
        ]);
    }

    /**
     * Update profil dan kontak mitra industri
     */
    public function update(Request $request, TempatMagang $dudi)
    {
        $validated = $request->validate([
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'bidang_usaha'    => ['required', 'string', 'max:255'],
            'nama_pic'        => ['required', 'string', 'max:255'],
            'kontak_pic'      => ['required', 'string', 'max:20'],
            'kuota'           => ['required', 'integer', 'min:1'],
            'alamat'          => ['required', 'string'],
        ]);

        $dudi->update($validated);

        $this->logActivity('UPDATE_DUDI', "Memperbarui data mitra: {$validated['nama_perusahaan']}");

        return response()->json(['message' => 'Data mitra DUDI berhasil diperbarui.']);
    }

    /**
     * Ubah status verifikasi (Terverifikasi / Belum Diverifikasi)
     */
    public function updateVerifikasi(Request $request, TempatMagang $dudi)
    {
        $validated = $request->validate([
            'status_verifikasi' => ['required', 'in:terverifikasi,belum_diverifikasi'],
        ]);

        $dudi->update($validated);

        $this->logActivity('UPDATE_VERIFIKASI_DUDI', "Mengubah status verifikasi {$dudi->nama_perusahaan} menjadi {$validated['status_verifikasi']}");

        return response()->json(['message' => 'Status verifikasi berhasil diperbarui.']);
    }

    /**
     * Hapus mitra DUDI — ditolak kalau masih ada siswa aktif magang di sana
     */
    public function destroy(TempatMagang $dudi)
    {
        $masihAdaSiswaAktif = $dudi->penempatan()
            ->where('status_pengesahan', '!=', 'lulus_magang')
            ->exists();

        if ($masihAdaSiswaAktif) {
            return response()->json([
                'message' => 'Mitra ini masih memiliki siswa aktif magang dan tidak dapat dihapus.',
            ], 422);
        }

        $nama = $dudi->nama_perusahaan;
        $dudi->delete();

        $this->logActivity('DELETE_DUDI', "Menghapus mitra DUDI: {$nama}");

        return response()->json(['message' => 'Mitra DUDI berhasil dihapus.']);
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