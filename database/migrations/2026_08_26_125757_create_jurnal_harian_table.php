<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurnal_harian', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('siswa_id');
            $table->date('tanggal');
            $table->text('kegiatan');
            $table->text('kendala')->nullable();
            $table->text('solusi')->nullable();
            $table->string('photo_url')->nullable();
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'revisi'])->default('menunggu');
            $table->text('catatan_guru')->nullable();
            $table->timestamps();

            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurnal_harian');
    }
};