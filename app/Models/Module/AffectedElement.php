<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffectedElement extends Model
{
    use HasFactory;

    protected $table="affected_elements";

    protected $fillable=[
        "name",
    ];

}
