<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fumigation extends Model
{
    use HasFactory;

    protected $table = "modules.fumigations";

    protected $fillable =
        [
        "aplication_id",
        "location_id",
        "product_id",
        "dose",
        "order_id",
        "application_time",
        "worker_id",
        "within_critical_limits",
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function aplication()
    {
        return $this->belongsTo(Aplication::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function correctiveActions()
    {
        return $this->hasMany(FumigationCorrectiveAction::class, 'fumigation_id', 'id');
    }

    public function safetyControls()
    {
        return $this->hasMany(FumigationSafetyControl::class, 'fumigation_id', 'id');
    }
}
