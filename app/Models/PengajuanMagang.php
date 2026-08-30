<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PengajuanMagang extends Model
{
    use HasUuids;

    protected $table = 'pengajuan_magang';
    protected $fillable = [
        'siswa_id', 'tempat_magang_id', 'posisi',
        'tanggal_mulai', 'tanggal_selesai', 'status', 'catatan_penolakan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function tempatMagang()
    {
        return $this->belongsTo(TempatMagang::class, 'tempat_magang_id');
    }
}