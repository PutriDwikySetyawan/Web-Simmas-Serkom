<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Guru;
use App\Models\PenempatanMagang;
use App\Models\PengajuanMagang;
use App\Models\Siswa;
use App\Models\TempatMagang;
use Illuminate\Http\Request;

class PenempatanController extends Controller
{
    /**
     * Manajemen: Penempatan Magang (/admin/penempatan)
     */
    public function index(Request $request)
{
    $query = PenempatanMagang::with(['siswa.profile', 'tempatMagang', 'guru.profile']);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('siswa', fn ($q) => $q->where('nis', 'like', "%{$search}%")
            ->orWhereHas('profile', fn ($q2) => $q2->where('nama', 'like', "%{$search}%")));
    }

    if ($request->filled('kelas') && $request->kelas !== 'semua') {
        $query->whereHas('siswa', fn ($q) => $q->where('kelas', $request->kelas));
    }

    if ($request->filled('dudi') && $request->dudi !== 'semua') {
        $query->where('tempat_magang_id', $request->dudi);
    }

    if ($request->filled('status') && $request->status !== 'semua') {
        $query->where('status_pengesahan', $request->status);
    }

    $penempatanList = $query->latest()->paginate(10);

    // Data pendukung untuk dropdown Form Tambah Penempatan
    $siswaBelumMagang = Siswa::where('status', 'belum_magang')->with('profile')->get();
    $dudiList         = TempatMagang::where('status_verifikasi', 'terverifikasi')->get();
    $guruList         = Guru::with('profile')->where('is_active', true)->get();

    // ===== TAMBAHAN BARU: statistik ringkas untuk 4 kartu di atas tabel =====
    $totalPenempatan   = PenempatanMagang::count();
    $sedangBerlangsung = PenempatanMagang::where('status_pengesahan', 'disahkan')->count();
    $lulusMagang       = PenempatanMagang::where('status_pengesahan', 'lulus_magang')->count();
    $dudiTerlibat      = PenempatanMagang::distinct('tempat_magang_id')->count('tempat_magang_id');

    // ===== TAMBAHAN BARU: daftar kelas unik untuk dropdown filter Kelas =====
    $daftarKelas = Siswa::distinct()->pluck('kelas')->filter()->values();

    // ===== PENGAJUAN MAGANG dari Siswa =====
    $pengajuanQuery = PengajuanMagang::with(['siswa.profile', 'tempatMagang']);

    if ($request->filled('cari_pengajuan')) {
        $cari = $request->cari_pengajuan;
        $pengajuanQuery->whereHas('siswa', function ($q) use ($cari) {
            $q->where('nis', 'like', "%{$cari}%")
              ->orWhereHas('profile', fn ($q2) => $q2->where('nama', 'like', "%{$cari}%"));
        })->orWhereHas('tempatMagang', fn ($q) => $q->where('nama_perusahaan', 'like', "%{$cari}%"));
    }

    if ($request->filled('status_pengajuan') && $request->status_pengajuan !== 'semua') {
        $pengajuanQuery->where('status', $request->status_pengajuan);
    }

    $pengajuanList      = $pengajuanQuery->latest()->paginate(10, ['*'], 'halaman_pengajuan')->withQueryString();
    $totalPengajuan     = PengajuanMagang::count();
    $pengajuanMenunggu  = PengajuanMagang::where('status', 'menunggu')->count();
    $pengajuanDisetujui = PengajuanMagang::where('status', 'disetujui')->count();
    $pengajuanDitolak   = PengajuanMagang::where('status', 'ditolak')->count();

    return view('admin.penempatan', compact(
        'penempatanList', 'siswaBelumMagang', 'dudiList', 'guruList',
        'totalPenempatan', 'sedangBerlangsung', 'lulusMagang', 'dudiTerlibat', 'daftarKelas',
        'pengajuanList', 'totalPengajuan', 'pengajuanMenunggu', 'pengajuanDisetujui', 'pengajuanDitolak'
    ));
}
    /**
     * Simpan penempatan resmi baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id'         => ['required', 'exists:siswa,id'],
            'tempat_magang_id' => ['required', 'exists:tempat_magang,id'],
            'guru_id'          => ['required', 'exists:guru,id'],
            'tanggal_mulai'    => ['required', 'date'],
            'tanggal_selesai'  => ['required', 'date', 'after:tanggal_mulai'],
        ]);

            $tempatMagang = TempatMagang::findOrFail($validated['tempat_magang_id']);

        // Validasi kuota otomatis — tolak kalau kuota DUDI sudah penuh
        // (sisa_kuota diakses sebagai properti, sesuai getSisaKuotaAttribute() di Model)
        if ($tempatMagang->sisa_kuota <= 0) {
            return response()->json([
                'message' => 'Kuota tempat magang ini sudah penuh.',
            ], 422);
        }

        $penempatan = PenempatanMagang::create([
            ...$validated,
            'status_pengesahan' => 'belum_disahkan',
        ]);

        // Update status siswa jadi "sedang_magang"
        Siswa::where('id', $validated['siswa_id'])->update(['status' => 'sedang_magang']);

        $this->logActivity('CREATE_PENEMPATAN', "Menempatkan siswa ID {$validated['siswa_id']} ke {$tempatMagang->nama_perusahaan}");

        return response()->json([
            'message'    => 'Penempatan berhasil disimpan.',
            'penempatan' => $penempatan->load(['siswa.profile', 'tempatMagang', 'guru.profile']),
        ]);
    }

    /**
     * Edit alokasi tempat magang, pembimbing, atau jadwal
     */
    public function update(Request $request, PenempatanMagang $penempatan)
    {
        $validated = $request->validate([
            'tempat_magang_id' => ['required', 'exists:tempat_magang,id'],
            'guru_id'          => ['required', 'exists:guru,id'],
            'tanggal_mulai'    => ['required', 'date'],
            'tanggal_selesai'  => ['required', 'date', 'after:tanggal_mulai'],
        ]);

        $penempatan->update($validated);

        $this->logActivity('UPDATE_PENEMPATAN', "Memperbarui penempatan ID {$penempatan->id}");

        return response()->json(['message' => 'Penempatan berhasil diperbarui.']);
    }

    /**
     * Sahkan status penempatan (Disahkan, Lulus Magang, Belum Disahkan)
     */
    public function sahkan(Request $request, PenempatanMagang $penempatan)
    {
        $validated = $request->validate([
            'status_pengesahan' => ['required', 'in:belum_disahkan,disahkan,lulus_magang'],
        ]);

        $penempatan->update($validated);

        // Kalau lulus magang, update juga status siswa
        if ($validated['status_pengesahan'] === 'lulus_magang') {
            $penempatan->siswa()->update(['status' => 'lulus']);
        }

        $this->logActivity('SAHKAN_PENEMPATAN', "Mengubah status pengesahan penempatan ID {$penempatan->id} menjadi {$validated['status_pengesahan']}");

        return response()->json(['message' => 'Status penempatan berhasil diperbarui.']);
    }

    /**
     * Batalkan penempatan — kembalikan status siswa jadi 'Belum Magang'
     */
    public function batalkan(PenempatanMagang $penempatan)
    {
        $penempatan->siswa()->update(['status' => 'belum_magang']);

        $siswaNama = $penempatan->siswa->profile->nama ?? '-';
        $penempatan->delete();

        $this->logActivity('BATALKAN_PENEMPATAN', "Membatalkan penempatan siswa: {$siswaNama}");

        return response()->json(['message' => 'Penempatan berhasil dibatalkan.']);
    }

    /**
     * Setujui pengajuan magang dari siswa
     */
    public function setujuiPengajuan(PengajuanMagang $pengajuan)
    {
        if ($pengajuan->status !== 'menunggu') {
            return response()->json(['message' => 'Pengajuan sudah diproses sebelumnya.'], 422);
        }

        $pengajuan->update(['status' => 'disetujui', 'catatan_penolakan' => null]);
        $pengajuan->siswa()->update(['status' => 'pengajuan']);

        $this->logActivity(
            'SETUJUI_PENGAJUAN',
            "Admin menyetujui pengajuan dari " . ($pengajuan->siswa->profile->nama ?? '-') .
            " ke " . ($pengajuan->tempatMagang->nama_perusahaan ?? '-')
        );

        return response()->json(['message' => 'Pengajuan berhasil disetujui.']);
    }

    /**
     * Tolak pengajuan magang dari siswa
     */
    public function tolakPengajuan(Request $request, PengajuanMagang $pengajuan)
    {
        $request->validate([
            'catatan_penolakan' => ['required', 'string', 'max:500'],
        ], [
            'catatan_penolakan.required' => 'Catatan alasan penolakan wajib diisi.',
        ]);

        if ($pengajuan->status !== 'menunggu') {
            return response()->json(['message' => 'Pengajuan sudah diproses sebelumnya.'], 422);
        }

        $pengajuan->update([
            'status'            => 'ditolak',
            'catatan_penolakan' => $request->catatan_penolakan,
        ]);
        $pengajuan->siswa()->update(['status' => 'belum_magang']);

        $this->logActivity(
            'TOLAK_PENGAJUAN',
            "Admin menolak pengajuan dari " . ($pengajuan->siswa->profile->nama ?? '-') .
            ". Alasan: {$request->catatan_penolakan}"
        );

        return response()->json(['message' => 'Pengajuan berhasil ditolak.']);
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