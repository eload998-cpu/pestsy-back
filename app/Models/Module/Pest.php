<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pest extends Model
{
    use HasFactory;

    protected $table="modules.pests";

    protected $fillable=[
        "common_name",
        "scientific_name",
        "is_xylophagus",
        "company_id"
    ];

}
