<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RodentControl extends Model
{
    use HasFactory;

    protected $table = "modules.control_of_rodents";

    protected $fillable =
        [
        "device_id",
        "product_id",
        "order_id",
        "device_number",
        "location_id",
        "bait_status",
        "dose",
        "activity",
        "observation",
        "application_time",
        "infestation_level",
        "aplication_id",
        "worker_id",
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
    public function pestBitacores()
    {
        return $this->hasMany(PestBitacore::class, 'control_of_rodent_id', 'id');
    }

    public function CorrectiveActions()
    {
        return $this->hasMany(RodentControlCorrectiveAction::class, 'control_of_rodent_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function application()
    {
        return $this->belongsTo(Aplication::class, 'aplication_id', 'id');
    }

}
