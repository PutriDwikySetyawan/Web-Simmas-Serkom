<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasUuids;

    protected $table = 'siswa';
    protected $fillable = ['user_id', 'nis', 'kelas', 'status', 'is_active'];

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'user_id');
    }

    public function penempatan()
    {
        return $this->hasOne(PenempatanMagang::class, 'siswa_id')->latestOfMany();
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'siswa_id');
    }

    public function jurnalHarian()
    {
        return $this->hasMany(JurnalHarian::class, 'siswa_id');
    }
}
