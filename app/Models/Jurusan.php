<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasUuids;

    protected $table = 'jurusan';

    protected $fillable = [
        'kode_jurusan',
        'nama_jurusan',
        'kepala_jurusan',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke rombel/kelas dengan jurusan ini
     */
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'jurusan', 'kode_jurusan');
    }

    /**
     * Relasi ke guru yang mengampu/berada di jurusan ini
     */
    public function guru()
    {
        return $this->hasMany(Guru::class, 'jurusan', 'kode_jurusan');
    }
}
