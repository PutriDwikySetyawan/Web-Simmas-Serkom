<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasUuids;

    protected $table = 'guru';
    protected $fillable = ['user_id', 'nip', 'jurusan', 'is_active'];

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'user_id');
    }

    public function penempatan()
    {
        return $this->hasMany(PenempatanMagang::class, 'guru_id');
    }

    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class, 'guru_id');
    }
}