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
        "inspection_result",
        "last_treatment_date",
        "next_treatment_date",
        "code",
        "sample_required",
        "water_temperature",
        "residual_chlorine_level",
        "observation",
        "aplication_id",
        "worker_id",
        "within_critical_limits",

    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function application()
    {
        return $this->belongsTo(Aplication::class);
    }

    public function correctiveActions()
    {
        return $this->hasMany(LegionellaControlCorrectiveAction::class, 'legionella_control_id', 'id');
    }
}
