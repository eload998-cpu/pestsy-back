<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lamp extends Model
{
    use HasFactory;


    protected $table="lamps";

    protected $fillable=
    [
        "station_number",
        "lamp_not_working",
        "rubbery_iron_changed",
        "lamp_cleaning",
        "quantity_replaced",
        "fluorescent_change",
        "observation",
        "order_id"
    ];

}
