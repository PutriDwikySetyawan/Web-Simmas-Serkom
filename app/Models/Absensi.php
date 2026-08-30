<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasUuids;

    protected $table = 'absensi';
    protected $fillable = [
        'siswa_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'status',
        'photo_masuk_url', 'photo_pulang_url', 'status_validasi', 'catatan_guru',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}