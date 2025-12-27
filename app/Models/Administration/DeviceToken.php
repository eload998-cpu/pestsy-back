<?php
namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{

    protected $table = "administration.device_tokens";

    protected $fillable = [
        'user_id', 'token', 'platform', 'device_id', 'last_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];
}
