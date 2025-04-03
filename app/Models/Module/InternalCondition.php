<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalCondition extends Model
{
    use HasFactory;

    protected $table="internal_conditions";

    protected $fillable=
    [
        "walls",
        "floors",
        "cleaning",
        "windows",
        "storage",
        "space",
        "evidences",
        "roofs",
        "sealings",
        "closed_doors",
        "pests_facilities",
        "garbage_cans",
        "equipment",
        "ventilation",
        "ducts",
        "clean_walls",
        "order_id"
    ];

}
