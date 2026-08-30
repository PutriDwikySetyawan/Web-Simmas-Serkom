<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penempatan_magang', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('siswa_id');
            $table->uuid('tempat_magang_id');
            $table->uuid('guru_id');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status_pengesahan', ['belum_disahkan', 'disahkan', 'lulus_magang'])
                ->default('belum_disahkan');
            $table->unsignedTinyInteger('nilai_akhir')->nullable();
            $table->timestamps();

            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
            $table->foreign('tempat_magang_id')->references('id')->on('tempat_magang')->onDelete('cascade');
            $table->foreign('guru_id')->references('id')->on('guru')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penempatan_magang');
    }
};