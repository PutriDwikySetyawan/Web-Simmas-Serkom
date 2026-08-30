<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    /**
     * Sistem: Pengaturan Sistem 3 Tab (/admin/settings)
     */
    public function edit()
    {
        $config = config('simmas');
        if (!is_array($config)) {
            $config = [];
        }

        // Cast ke object agar di Blade bisa diakses $settings->field
        $settings = (object) array_merge([
            'nama_aplikasi'       => 'SIMMAS',
            'kepanjangan'         => 'Sistem Informasi Manajemen Magang Siswa',
            'deskripsi_aplikasi'  => 'Platform manajemen magang siswa SMK yang menghubungkan sekolah, guru pembimbing, dan dunia usaha industri.',
            'hero_judul'          => 'Kelola Magang Siswa Lebih Mudah & Terstruktur',
            'hero_deskripsi'      => 'Platform terpadu untuk monitoring jurnal harian, absensi selfie berbasis lokasi, penempatan DUDI, dan validasi pembimbing secara realtime.',
            'nama_sekolah'        => 'SMK Negeri 1 Bangil',
            'website_sekolah'     => 'https://smkn1-bangil.sch.id',
            'nama_kepala_sekolah' => 'Drs. H. Ahmad Fauzi, M.Pd.',
            'nip_kepala_sekolah'  => '196805121994031005',
            'alamat_sekolah'      => 'Jl. Tongkol No.3, Kec. Bangil, Kab. Pasuruan, Jawa Timur 67153',
            'no_telepon_sekolah'  => '(0343) 744144',
        ], $config);

        return view('admin.settings', compact('settings'));
    }

    /**
     * Simpan perubahan ke config/simmas.php
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // Tab 1: Identitas Aplikasi
            'nama_aplikasi'       => ['required', 'string', 'max:100'],
            'kepanjangan'         => ['required', 'string', 'max:255'],
            'deskripsi_aplikasi'  => ['required', 'string'],

            // Tab 2: Halaman Depan
            'hero_judul'          => ['required', 'string', 'max:255'],
            'hero_deskripsi'      => ['required', 'string'],

            // Tab 3: Data Sekolah
            'nama_sekolah'        => ['required', 'string', 'max:255'],
            'website_sekolah'     => ['nullable', 'url'],
            'nama_kepala_sekolah' => ['required', 'string', 'max:255'],
            'nip_kepala_sekolah'  => ['required', 'string', 'max:50'],
            'alamat_sekolah'      => ['required', 'string'],
            'no_telepon_sekolah'  => ['required', 'string', 'max:30'],
        ], [
            'nama_aplikasi.required'       => 'Nama aplikasi wajib diisi.',
            'kepanjangan.required'         => 'Kepanjangan aplikasi wajib diisi.',
            'deskripsi_aplikasi.required'  => 'Deskripsi aplikasi wajib diisi.',
            'hero_judul.required'          => 'Judul utama hero wajib diisi.',
            'hero_deskripsi.required'      => 'Deskripsi hero wajib diisi.',
            'nama_sekolah.required'        => 'Nama sekolah wajib diisi.',
            'website_sekolah.url'          => 'Format URL website sekolah tidak valid (harus diawali https:// atau http://).',
            'nama_kepala_sekolah.required' => 'Nama kepala sekolah wajib diisi.',
            'nip_kepala_sekolah.required'  => 'NIP kepala sekolah wajib diisi.',
            'alamat_sekolah.required'      => 'Alamat sekolah wajib diisi.',
            'no_telepon_sekolah.required'  => 'Nomor telepon sekolah wajib diisi.',
        ]);

        $this->writeConfigFile($validated);

        // Bersihkan cache config
        try {
            Artisan::call('config:clear');
        } catch (\Throwable $e) {
            // Ignore if artisan fails in certain contexts
        }

        $this->logActivity('UPDATE_SETTINGS', 'Memperbarui pengaturan sistem SIMMAS');

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan sistem berhasil disimpan.',
        ]);
    }

    /**
     * Tulis ulang file config/simmas.php dengan data baru
     */
    private function writeConfigFile(array $data): void
    {
        $path = config_path('simmas.php');
        $export = var_export($data, true);
        $content = "<?php\n\nreturn {$export};\n";

        File::put($path, $content);
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