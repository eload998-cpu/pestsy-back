<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $table = "public.statuses";

    protected $fillable=[
        "name",
        "status_id"
    ];

}
