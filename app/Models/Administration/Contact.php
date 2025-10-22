<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = "administration.contacts";

    protected $fillable=[
        "user_id",
        "data",
        "status_id",
    ];

}
