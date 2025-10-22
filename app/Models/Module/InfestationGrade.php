<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfestationGrade extends Model
{
    use HasFactory;

    protected $table="modules.infestation_grades";

    protected $fillable=
    [
        "german_cockroaches",
        "flies",
        "bees",
        "termites",
        "fleas",
        "moths",
        "weevils",
        "american_cockroaches",
        "ants",
        "termite",
        "spiders",
        "rodents",
        "fire_ants",
        "stilt_walkers",
        "others",
        "order_id"
    ];

}
