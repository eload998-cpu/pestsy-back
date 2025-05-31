<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegionellaControl extends Model
{
    use HasFactory;

    protected $table = "modules.control_of_legionella";

    protected $fillable =
        [
        "order_id",
        "location_id",
        "desinfection_method_id",
        "inspection_result",
        "last_treatment_date",
        "next_treatment_date",
        "code",
        "sample_required",
        "water_temperature",
        "residual_chlorine_level",
        "observation",

    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function desinfectionMethod()
    {
        return $this->belongsTo(DesinfectionMethod::class);
    }



}
