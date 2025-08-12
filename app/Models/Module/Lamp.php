<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lamp extends Model
{
    use HasFactory;

    protected $table = "modules.lamps";

    protected $fillable =
        [
        "station_number",
        "lamp_not_working",
        "rubbery_iron_changed",
        "lamp_cleaning",
        "quantity_replaced",
        "fluorescent_change",
        "observation",
        "order_id",
        "application_time",
        "worker_id",
        "location_id",

    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function correctiveActions()
    {
        return $this->hasMany(LampCorrectiveAction::class, 'lamp_id', 'id');
    }

      public function location()
    {
        return $this->belongsTo(Location::class);
    }

}
