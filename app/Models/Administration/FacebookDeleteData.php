<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookDeleteData extends Model
{
    use HasFactory;

    protected $table = "administration.facebook_delete_data";
    protected $fillable = ["code", "uid"];
}
