<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
// Import Model untuk jurnal harian, log aktivitas, dan upload storage foto
use App\Models\ActivityLog;
use App\Models\JurnalHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JurnalController extends Controller
{
    /**
     * READ: Menampilkan Halaman Riwayat Jurnal Kegiatan Siswa (/siswa/jurnal)
     */
    public function index(Request $request)
    {
        // Mengambil data siswa dari user login aktif
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan untuk akun ini.');
        }

        // Query jurnal milik siswa tersebut
        $query = JurnalHarian::where('siswa_id', $siswa->id);

        // Filter pencarian berdasarkan isi kegiatan, kendala, atau solusi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                    ->orWhere('kendala', 'like', "%{$search}%")
                    ->orWhere('solusi', 'like', "%{$search}%");
            });
        }

        // Mengurutkan jurnal dari tanggal terbaru
        $jurnalList = $query->orderBy('tanggal', 'desc')->get();

        // Mengirimkan variabel ke view siswa/jurnal.blade.php
        return view('siswa.jurnal', compact('siswa', 'jurnalList'));
    }

    /**
     * CREATE: Menyimpan laporan aktivitas jurnal baru (POST /siswa/jurnal)
     */
    public function store(Request $request)
    {
        // Validasi input form jurnal harian
        $validated = $request->validate([
            'tanggal'  => ['required', 'date'],
            'kegiatan' => ['required', 'string', 'min:15'],
            'kendala'  => ['nullable', 'string'],
            'solusi'   => ['nullable', 'string'],
            'photo'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ], [
            'tanggal.required'  => 'Tanggal pelaksanaan wajib diisi.',
            'kegiatan.required' => 'Rincian kegiatan wajib diisi.',
            'kegiatan.min'      => 'Rincian kegiatan minimal harus berisi 15 karakter.',
            'photo.image'       => 'File lampiran harus berupa gambar.',
            'photo.max'         => 'Ukuran foto maksimal 4MB.',
        ]);

        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Profil siswa tidak ditemukan.'], 403);
            }
            return back()->with('error', 'Profil siswa tidak ditemukan.');
        }

        // Upload lampiran foto dokumentasi magang ke storage publik
        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $photoUrl = $request->file('photo')->store('jurnal', 'public');
        }

        // Simpan data jurnal ke database
        $jurnal = JurnalHarian::create([
            'siswa_id'          => $siswa->id,
            'tanggal'           => $validated['tanggal'],
            'kegiatan'          => $validated['kegiatan'],
            'kendala'           => $validated['kendala'] ?? null,
            'solusi'            => $validated['solusi'] ?? null,
            'photo_url'         => $photoUrl,
            'status_verifikasi' => 'menunggu', // Status awal: menunggu verifikasi guru
        ]);

        // Mencatat log aktivitas pembuatan jurnal
        ActivityLog::create([
            'level'       => 'info',
            'action_type' => 'TULIS_JURNAL',
            'actor_email' => auth()->user()->email ?? null,
            'actor_role'  => auth()->user()->role ?? 'siswa',
            'ip_address'  => request()->ip(),
            'metadata'    => [
                'description' => "Siswa {$siswa->profile->nama} menulis jurnal kegiatan tanggal {$validated['tanggal']}",
                'jurnal_id'   => $jurnal->id,
                'tanggal'     => $validated['tanggal'],
            ],
        ]);

        // Respon JSON untuk AJAX
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jurnal kegiatan berhasil dikirim.',
                'jurnal'  => $jurnal,
            ]);
        }

        return redirect()->route('siswa.jurnal.index')->with('success', 'Jurnal kegiatan berhasil dikirim.');
    }

    /**
     * UPDATE: Memperbarui data laporan jurnal kegiatan (PUT /siswa/jurnal/{jurnal})
     */
    public function update(Request $request, JurnalHarian $jurnal)
    {
        $siswa = auth()->user()->siswa;

        // Memastikan jurnal hanya dapat diubah oleh pemiliknya sendiri (otorisasi)
        abort_if(!$siswa || $jurnal->siswa_id !== $siswa->id, 403);

        // Validasi input perubahan data
        $validated = $request->validate([
            'tanggal'  => ['required', 'date'],
            'kegiatan' => ['required', 'string', 'min:15'],
            'kendala'  => ['nullable', 'string'],
            'solusi'   => ['nullable', 'string'],
            'photo'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ], [
            'tanggal.required'  => 'Tanggal pelaksanaan wajib diisi.',
            'kegiatan.required' => 'Rincian kegiatan wajib diisi.',
            'kegiatan.min'      => 'Rincian kegiatan minimal harus berisi 15 karakter.',
            'photo.image'       => 'File lampiran harus berupa gambar.',
            'photo.max'         => 'Ukuran foto maksimal 4MB.',
        ]);

        $updateData = [
            'tanggal'           => $validated['tanggal'],
            'kegiatan'          => $validated['kegiatan'],
            'kendala'           => $validated['kendala'] ?? null,
            'solusi'            => $validated['solusi'] ?? null,
            // Setelah direvisi, status otomatis kembali ke "menunggu" untuk dicek ulang oleh guru
            'status_verifikasi' => 'menunggu',
        ];

        // Jika user mengunggah foto baru, hapus foto lama dari storage
        if ($request->hasFile('photo')) {
            if ($jurnal->photo_url) {
                Storage::disk('public')->delete($jurnal->photo_url);
            }
            $updateData['photo_url'] = $request->file('photo')->store('jurnal', 'public');
        }

        // Update record ke database
        $jurnal->update($updateData);

        // Catat log aktivitas update jurnal
        ActivityLog::create([
            'level'       => 'info',
            'action_type' => 'UPDATE_JURNAL',
            'actor_email' => auth()->user()->email ?? null,
            'actor_role'  => auth()->user()->role ?? 'siswa',
            'ip_address'  => request()->ip(),
            'metadata'    => [
                'description' => "Siswa {$siswa->profile->nama} memperbarui jurnal ID {$jurnal->id}",
                'jurnal_id'   => $jurnal->id,
            ],
        ]);

        // Respon JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jurnal kegiatan berhasil diperbarui.',
                'jurnal'  => $jurnal,
            ]);
        }

        return redirect()->route('siswa.jurnal.index')->with('success', 'Jurnal kegiatan berhasil diperbarui.');
    }

    /**
     * DELETE: Menghapus laporan jurnal harian (DELETE /siswa/jurnal/{jurnal})
     * Dilengkapi proteksi: Jurnal yang sudah disetujui guru tidak boleh dihapus
     */
    public function destroy(JurnalHarian $jurnal)
    {
        $siswa = auth()->user()->siswa;
        abort_if(!$siswa || $jurnal->siswa_id !== $siswa->id, 403);

        // Validasi: Jurnal yang telah diverifikasi & disetujui guru tidak diizinkan dihapus
        if ($jurnal->status_verifikasi === 'disetujui') {
            return response()->json([
                'message' => 'Laporan jurnal yang sudah disetujui guru tidak dapat dihapus.',
            ], 422);
        }

        // Hapus file foto dari storage jika ada
        if ($jurnal->photo_url) {
            Storage::disk('public')->delete($jurnal->photo_url);
        }

        $jurnalTanggal = $jurnal->tanggal;
        // Hapus data dari tabel
        $jurnal->delete();

        // Mencatat log aktivitas penghapusan
        ActivityLog::create([
            'level'       => 'info',
            'action_type' => 'DELETE_JURNAL',
            'actor_email' => auth()->user()->email ?? null,
            'actor_role'  => auth()->user()->role ?? 'siswa',
            'ip_address'  => request()->ip(),
            'metadata'    => [
                'description' => "Siswa {$siswa->profile->nama} menghapus jurnal tanggal {$jurnalTanggal}",
            ],
        ]);

        return response()->json(['message' => 'Jurnal kegiatan berhasil dihapus.']);
    }
}