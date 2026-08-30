<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * ============================================================
     * DATABASE SEEDER SIMMAS
     * ============================================================
     *
     * Seeder ini membuat 3 akun demo:
     *
     * 1. Admin
     *    Email    : admin@simmas.sch.id
     *    Password : password123
     *
     * 2. Guru
     *    Email    : guru@simmas.sch.id
     *    Password : password123
     *
     * 3. Siswa
     *    Email    : siswa@simmas.sch.id
     *    Password : password123
     *
     * Data akun disimpan di tabel profiles.
     * Guru memiliki data tambahan di tabel guru.
     * Siswa memiliki data tambahan di tabel siswa.
     */

    public function run(): void
    {
        // ========================================================
        // 1. MEMBUAT UUID UNTUK SETIAP AKUN
        // ========================================================
        //
        // ID dibuat manual karena struktur database menggunakan
        // UUID / CHAR(36).
        //

        $adminId = (string) Str::uuid();
        $guruId  = (string) Str::uuid();
        $siswaId = (string) Str::uuid();

        // ========================================================
        // 2. AKUN ADMIN
        // ========================================================
        //
        // Admin hanya membutuhkan data pada tabel profiles.
        // Admin tidak membutuhkan data pada tabel guru atau siswa.
        //

        DB::table('profiles')->insert([
            'id'         => $adminId,
            'nama'       => 'Admin SIMMAS',
            'email'      => 'admin@simmas.sch.id',

            // Password login akun demo:
            // password123
            'password'   => Hash::make('password123'),

            'role'       => 'admin',

            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ========================================================
        // 3. AKUN GURU - TABEL PROFILES
        // ========================================================
        //
        // Data login guru disimpan di profiles.
        //

        DB::table('profiles')->insert([
            'id'         => $guruId,
            'nama'       => 'Guru SIMMAS',
            'email'      => 'guru@simmas.sch.id',

            // Password login akun demo:
            // password123
            'password'   => Hash::make('password123'),

            'role'       => 'guru',

            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ========================================================
        // 4. DATA GURU
        // ========================================================
        //
        // Guru memiliki data tambahan pada tabel guru.
        //
        // PENTING:
        // Database kamu saat ini memiliki kolom "jurusan"
        // yang wajib diisi. Karena itu jurusan harus dimasukkan
        // ke dalam seeder.
        //

        DB::table('guru')->insert([
            'id'         => (string) Str::uuid(),

            // Relasi ke profiles
            'user_id'    => $guruId,

            // Nomor Induk Pegawai
            'nip'        => '199001012020121001',

            // Jurusan guru
            'jurusan'    => 'PPLG',

            // Status guru aktif
            'is_active'  => true,

            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ========================================================
        // 5. AKUN SISWA - TABEL PROFILES
        // ========================================================
        //
        // Data login siswa disimpan di profiles.
        //

        DB::table('profiles')->insert([
            'id'         => $siswaId,
            'nama'       => 'Siswa SIMMAS',
            'email'      => 'siswa@simmas.sch.id',

            // Password login akun demo:
            // password123
            'password'   => Hash::make('password123'),

            'role'       => 'siswa',

            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ========================================================
        // 6. DATA SISWA
        // ========================================================
        //
        // Siswa memiliki data tambahan pada tabel siswa.
        //

        DB::table('siswa')->insert([
            'id'         => (string) Str::uuid(),

            // Relasi ke profiles
            'user_id'    => $siswaId,

            // Nomor Induk Siswa
            'nis'        => '2223456789',

            // Kelas siswa
            'kelas'      => 'XII PPLG 1',

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}