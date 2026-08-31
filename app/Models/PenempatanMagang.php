<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PenempatanMagang extends Model
{
    use HasUuids;

    protected $table = 'penempatan_magang';
    protected $fillable = [
        'siswa_id', 'tempat_magang_id', 'guru_id', 'posisi',
        'tanggal_mulai', 'tanggal_selesai', 'status_pengesahan', 'catatan_penolakan', 'nilai_akhir',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function tempatMagang()
    {
        return $this->belongsTo(TempatMagang::class, 'tempat_magang_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
