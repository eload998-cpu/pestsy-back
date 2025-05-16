<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppliedTreatment extends Model
{
    use HasFactory;

    protected $table="applied_treatments";

    protected $fillable=[
        "name",
    ];

}
