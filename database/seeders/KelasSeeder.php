<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            [
                'nama_kelas'   => 'XII RPL A',
                'tingkat'      => 'XII',
                'jurusan'      => 'PPLG',
                'wali_kelas'   => 'Guru SIMMAS',
                'tahun_ajaran' => '2025/2026',
                'kapasitas'    => 36,
                'is_active'    => true,
            ],
            [
                'nama_kelas'   => 'XI RPL 2',
                'tingkat'      => 'XI',
                'jurusan'      => 'PPLG',
                'wali_kelas'   => 'Budi Santoso, S.Kom',
                'tahun_ajaran' => '2025/2026',
                'kapasitas'    => 36,
                'is_active'    => true,
            ],
            [
                'nama_kelas'   => 'XI RPL 3',
                'tingkat'      => 'XI',
                'jurusan'      => 'PPLG',
                'wali_kelas'   => 'Siti Nurhaliza, M.Pd',
                'tahun_ajaran' => '2025/2026',
                'kapasitas'    => 36,
                'is_active'    => true,
            ],
            [
                'nama_kelas'   => 'XII TKJ 1',
                'tingkat'      => 'XII',
                'jurusan'      => 'TJKT',
                'wali_kelas'   => 'Ahmad Fauzi, S.T',
                'tahun_ajaran' => '2025/2026',
                'kapasitas'    => 36,
                'is_active'    => true,
            ],
            [
                'nama_kelas'   => 'X DKV 1',
                'tingkat'      => 'X',
                'jurusan'      => 'DKV',
                'wali_kelas'   => 'Rina Marlina, S.Sn',
                'tahun_ajaran' => '2025/2026',
                'kapasitas'    => 36,
                'is_active'    => true,
            ],
        ];

        foreach ($classes as $data) {
            Kelas::firstOrCreate(['nama_kelas' => $data['nama_kelas']], $data);
        }
    }
}
