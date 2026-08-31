<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. DDL: Buat tabel pengajuan_magang
        if (! Schema::hasTable('pengajuan_magang')) {
            Schema::create('pengajuan_magang', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('siswa_id');
                $table->uuid('tempat_magang_id');
                $table->string('posisi')->nullable();
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai');
                $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
                $table->text('catatan_penolakan')->nullable();
                $table->timestamps();

                $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
                $table->foreign('tempat_magang_id')->references('id')->on('tempat_magang')->onDelete('cascade');
            });
        }

        // 2. DDL: Tambahkan kolom pengajuan_id di penempatan_magang
        if (! Schema::hasColumn('penempatan_magang', 'pengajuan_id')) {
            Schema::table('penempatan_magang', function (Blueprint $table) {
                $table->uuid('pengajuan_id')->nullable()->after('id');
                $table->foreign('pengajuan_id')->references('id')->on('pengajuan_magang')->onDelete('set null');
            });
        }

        // 3. DML: Pindahkan data pengajuan awal siswa dalam DB transaction
        DB::transaction(function () {
            $submissions = DB::table('penempatan_magang')
                ->whereNull('guru_id')
                ->whereIn('status_pengesahan', ['menunggu', 'belum_disahkan', 'ditolak'])
                ->get();

            foreach ($submissions as $sub) {
                $status = match ($sub->status_pengesahan) {
                    'ditolak' => 'ditolak',
                    'disahkan', 'lulus_magang' => 'disetujui',
                    default => 'menunggu',
                };

                DB::table('pengajuan_magang')->insertOrIgnore([
                    'id' => $sub->id,
                    'siswa_id' => $sub->siswa_id,
                    'tempat_magang_id' => $sub->tempat_magang_id,
                    'posisi' => $sub->posisi,
                    'tanggal_mulai' => $sub->tanggal_mulai,
                    'tanggal_selesai' => $sub->tanggal_selesai,
                    'status' => $status,
                    'catatan_penolakan' => $sub->catatan_penolakan,
                    'created_at' => $sub->created_at,
                    'updated_at' => $sub->updated_at,
                ]);
            }

            DB::table('penempatan_magang')
                ->whereNull('guru_id')
                ->whereIn('status_pengesahan', ['menunggu', 'belum_disahkan', 'ditolak'])
                ->delete();
        });
    }

    public function down(): void
    {
        DB::transaction(function () {
            if (Schema::hasTable('pengajuan_magang')) {
                $pengajuans = DB::table('pengajuan_magang')->get();
                foreach ($pengajuans as $p) {
                    $statusPengesahan = match ($p->status) {
                        'disetujui' => 'disahkan',
                        'ditolak' => 'ditolak',
                        default => 'menunggu',
                    };

                    DB::table('penempatan_magang')->insertOrIgnore([
                        'id' => $p->id,
                        'siswa_id' => $p->siswa_id,
                        'tempat_magang_id' => $p->tempat_magang_id,
                        'guru_id' => null,
                        'posisi' => $p->posisi,
                        'tanggal_mulai' => $p->tanggal_mulai,
                        'tanggal_selesai' => $p->tanggal_selesai,
                        'status_pengesahan' => $statusPengesahan,
                        'catatan_penolakan' => $p->catatan_penolakan,
                        'nilai_akhir' => null,
                        'created_at' => $p->created_at,
                        'updated_at' => $p->updated_at,
                    ]);
                }
            }
        });

        if (Schema::hasColumn('penempatan_magang', 'pengajuan_id')) {
            Schema::table('penempatan_magang', function (Blueprint $table) {
                $table->dropForeign(['pengajuan_id']);
                $table->dropColumn('pengajuan_id');
            });
        }

        Schema::dropIfExists('pengajuan_magang');
    }
};
