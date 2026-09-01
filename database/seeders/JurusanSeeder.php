<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusanList = [
            [
                'kode_jurusan'   => 'PPLG',
                'nama_jurusan'   => 'Pengembangan Perangkat Lunak dan Gim',
                'kepala_jurusan' => 'Guru SIMMAS',
                'deskripsi'      => 'Program keahlian yang mempelajari pengembangan software, mobile application, web development, dan game dev.',
                'is_active'      => true,
            ],
            [
                'kode_jurusan'   => 'TJKT',
                'nama_jurusan'   => 'Teknik Jaringan Komputer dan Telekomunikasi',
                'kepala_jurusan' => 'Ahmad Fauzi, S.T',
                'deskripsi'      => 'Program keahlian yang fokus pada instalasi jaringan komputer, server, cloud computing, dan infrastruktur telekomunikasi.',
                'is_active'      => true,
            ],
            [
                'kode_jurusan'   => 'DKV',
                'nama_jurusan'   => 'Desain Komunikasi Visual',
                'kepala_jurusan' => 'Rina Marlina, S.Sn',
                'deskripsi'      => 'Program keahlian yang berfokus pada desain grafis, ilustrasi digital, UI/UX, animasi, dan multimedia.',
                'is_active'      => true,
            ],
            [
                'kode_jurusan'   => 'AKL',
                'nama_jurusan'   => 'Akuntansi dan Keuangan Lembaga',
                'kepala_jurusan' => 'Hj. Endang Suryani, M.Ak',
                'deskripsi'      => 'Program keahlian tata kelola keuangan, akuntansi bisnis perbankan, dan perpajakan.',
                'is_active'      => true,
            ],
            [
                'kode_jurusan'   => 'MPLB',
                'nama_jurusan'   => 'Manajemen Perkantoran dan Layanan Bisnis',
                'kepala_jurusan' => 'Dra. Sri Wahyuni',
                'deskripsi'      => 'Program keahlian tata kelola administrasi perkantoran, kearsipan, dan layanan komunikasi bisnis.',
                'is_active'      => true,
            ],
        ];

        foreach ($jurusanList as $item) {
            Jurusan::firstOrCreate(['kode_jurusan' => $item['kode_jurusan']], $item);
        }
    }
}
