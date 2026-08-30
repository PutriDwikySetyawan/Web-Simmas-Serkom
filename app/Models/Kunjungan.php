<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasUuids;

    protected $table = 'kunjungan';
    protected $casts = ['tanggal' => 'date'];
    protected $fillable = ['guru_id', 'tempat_magang_id', 'tanggal', 'catatan', 'photo_url'];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function tempatMagang()
    {
        return $this->belongsTo(TempatMagang::class, 'tempat_magang_id');
    }
}