<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\JurnalHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JurnalController extends Controller
{
    /**
     * Kegiatan Magang: Jurnal Kegiatan (/siswa/jurnal)
     */
    public function index(Request $request)
    {
        $siswa = auth()->user()->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan untuk akun ini.');
        }

        $query = JurnalHarian::where('siswa_id', $siswa->id);

        // Search berdasarkan kegiatan/kendala/solusi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                    ->orWhere('kendala', 'like', "%{$search}%")
                    ->orWhere('solusi', 'like', "%{$search}%");
            });
        }

        $jurnalList = $query->orderBy('tanggal', 'desc')->get();

        return view('siswa.jurnal', compact('siswa', 'jurnalList'));
    }

    /**
     * Modal Tulis Jurnal: simpan laporan aktivitas harian
     */
    public function store(Request $request)
    {
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

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $photoUrl = $request->file('photo')->store('jurnal', 'public');
        }

        $jurnal = JurnalHarian::create([
            'siswa_id'          => $siswa->id,
            'tanggal'           => $validated['tanggal'],
            'kegiatan'          => $validated['kegiatan'],
            'kendala'           => $validated['kendala'] ?? null,
            'solusi'            => $validated['solusi'] ?? null,
            'photo_url'         => $photoUrl,
            'status_verifikasi' => 'menunggu',
        ]);

        // Catat activity log
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
     * Modal Edit Jurnal: perbaiki laporan yang diminta revisi atau draft
     */
    public function update(Request $request, JurnalHarian $jurnal)
    {
        $siswa = auth()->user()->siswa;

        // Pastikan jurnal ini milik siswa yang login
        abort_if(!$siswa || $jurnal->siswa_id !== $siswa->id, 403);

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
            // Setelah direvisi, status kembali ke "menunggu" untuk dicek ulang guru
            'status_verifikasi' => 'menunggu',
        ];

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($jurnal->photo_url) {
                Storage::disk('public')->delete($jurnal->photo_url);
            }
            $updateData['photo_url'] = $request->file('photo')->store('jurnal', 'public');
        }

        $jurnal->update($updateData);

        // Catat activity log
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
     * Alert Hapus: hapus draft jurnal (bukan yang sudah disetujui)
     */
    public function destroy(JurnalHarian $jurnal)
    {
        $siswa = auth()->user()->siswa;
        abort_if(!$siswa || $jurnal->siswa_id !== $siswa->id, 403);

        // Jurnal yang sudah disetujui tidak boleh dihapus
        if ($jurnal->status_verifikasi === 'disetujui') {
            return response()->json([
                'message' => 'Laporan jurnal yang sudah disetujui guru tidak dapat dihapus.',
            ], 422);
        }

        if ($jurnal->photo_url) {
            Storage::disk('public')->delete($jurnal->photo_url);
        }

        $jurnalTanggal = $jurnal->tanggal;
        $jurnal->delete();

        // Catat activity log
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