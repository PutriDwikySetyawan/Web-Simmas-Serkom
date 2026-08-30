<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasUuids;

    protected $table = 'activity_logs';
    public $timestamps = false;
    protected $fillable = ['level', 'action_type', 'actor_email', 'actor_role', 'ip_address', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];
}