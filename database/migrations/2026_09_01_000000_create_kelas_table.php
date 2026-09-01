<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_kelas', 50)->unique();
            $table->string('tingkat', 10);
            $table->string('jurusan', 100);
            $table->string('wali_kelas', 255)->nullable();
            $table->string('tahun_ajaran', 20)->nullable();
            $table->unsignedInteger('kapasitas')->default(36);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
