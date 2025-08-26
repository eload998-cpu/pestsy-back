<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trap extends Model
{
    use HasFactory;

    protected $table = "modules.traps";

    protected $fillable =
        [
        "station_number",
        "device_id",
        "product_id",
        "dose",
        "pheromones",
        "application_time",
        "worker_id",
        "order_id",
        "condition",
        "location_id",
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function correctiveActions()
    {
        return $this->hasMany(TrapCorrectiveAction::class, 'trap_id', 'id');
    }

}
