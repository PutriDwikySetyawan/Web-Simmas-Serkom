<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TempatMagang extends Model
{
    // ============================================================
    // TRAIT & KONFIGURASI DASAR
    // ============================================================

    use HasUuids; // primary key berupa UUID, konsisten dengan Guru & Siswa

    /**
     * Nama tabel eksplisit karena penamaan singular (bukan default "tempat_magangs")
     */
    protected $table = 'tempat_magang';

    /**
     * Kolom yang boleh diisi lewat mass assignment.
     * Sesuai field form Tambah & Edit Mitra DUDI di soal.
     */
    protected $fillable = [
        'nama_perusahaan',
        'bidang_usaha',
        'nama_pic',
        'kontak_pic',
        'alamat',
        'kuota',
        'status_verifikasi',
    ];

    // ============================================================
    // RELASI
    // ============================================================

    /**
     * Satu DUDI bisa punya banyak penempatan siswa magang.
     * Dipakai controller untuk withCount('penempatan as siswa_aktif_count')
     * dan untuk cek relasi sebelum hapus data.
     */
    public function penempatan()
    {
        return $this->hasMany(PenempatanMagang::class, 'tempat_magang_id');
    }

    // ============================================================
    // ACCESSOR
    // ============================================================

    /**
     * Sisa kuota = kuota total - jumlah siswa yang masih aktif magang di sini.
     * Berguna nanti di form Plotting Siswa & form Tambah Penempatan
     * ("PT. Universal Big Data (Sisa Kuota: 3)").
     *
     * Catatan: accessor ini hanya akurat kalau siswa_aktif_count sudah
     * di-load via withCount(). Kalau belum, fallback hitung langsung.
     */
    public function getSisaKuotaAttribute()
    {
        $terpakai = $this->siswa_aktif_count
            ?? $this->penempatan()->where('status_pengesahan', '!=', 'lulus_magang')->count();

        return max(0, $this->kuota - $terpakai);
    }

    public function sisaKuota(): int
    {
        return (int) $this->sisa_kuota;
    }
}