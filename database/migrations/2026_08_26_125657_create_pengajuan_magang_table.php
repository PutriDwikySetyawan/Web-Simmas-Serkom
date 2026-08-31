<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pengajuan siswa kini disimpan langsung di tabel penempatan_magang.
    }

    public function down(): void
    {
        // Tidak ada tabel terpisah yang perlu dihapus.
    }
};
