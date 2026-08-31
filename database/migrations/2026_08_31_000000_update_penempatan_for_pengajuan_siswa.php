<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penempatan_magang', function (Blueprint $table) {
            $table->uuid('guru_id')->nullable()->change();
            $table->enum('status_pengesahan', ['menunggu', 'belum_disahkan', 'disahkan', 'ditolak', 'lulus_magang'])
                ->default('belum_disahkan')
                ->change();
        });

        Schema::table('penempatan_magang', function (Blueprint $table) {
            if (! Schema::hasColumn('penempatan_magang', 'posisi')) {
                $table->string('posisi')->nullable()->after('guru_id');
            }

            if (! Schema::hasColumn('penempatan_magang', 'catatan_penolakan')) {
                $table->text('catatan_penolakan')->nullable()->after('status_pengesahan');
            }
        });

        // Pindahkan data lama sebelum tabel pengajuan terpisah dihapus.
        if (Schema::hasTable('pengajuan_magang')) {
            DB::table('pengajuan_magang')->orderBy('created_at')->each(function ($pengajuan) {
                DB::table('penempatan_magang')->insertOrIgnore([
                    'id' => $pengajuan->id,
                    'siswa_id' => $pengajuan->siswa_id,
                    'tempat_magang_id' => $pengajuan->tempat_magang_id,
                    'guru_id' => null,
                    'posisi' => $pengajuan->posisi,
                    'tanggal_mulai' => $pengajuan->tanggal_mulai,
                    'tanggal_selesai' => $pengajuan->tanggal_selesai,
                    'status_pengesahan' => match ($pengajuan->status) {
                        'disetujui' => 'belum_disahkan',
                        default => $pengajuan->status,
                    },
                    'catatan_penolakan' => $pengajuan->catatan_penolakan,
                    'nilai_akhir' => null,
                    'created_at' => $pengajuan->created_at,
                    'updated_at' => $pengajuan->updated_at,
                ]);
            });

            Schema::dropIfExists('pengajuan_magang');
        }
    }

    public function down(): void
    {
        // Migrasi ini menyatukan dua tabel; rollback tidak aman dilakukan otomatis.
    }
};
