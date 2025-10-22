<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use HasFactory;

    protected $table="modules.observations";

    protected $fillable=
    [
        "observation",
        "order_id"
    ];
}
