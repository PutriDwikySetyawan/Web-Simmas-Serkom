<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class JurnalHarian extends Model
{
    use HasUuids;

    protected $table = 'jurnal_harian';
    protected $fillable = [
        'siswa_id', 'tanggal', 'kegiatan', 'kendala',
        'solusi', 'photo_url', 'status_verifikasi', 'catatan_guru',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}