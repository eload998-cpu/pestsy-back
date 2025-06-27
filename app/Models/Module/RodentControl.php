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
    ];

    public function pestBitacores()
    {
        return $this->hasMany(PestBitacore::class, 'control_of_rodent_id', 'id');
    }

    public function orderCorrectiveActions()
    {
        return $this->hasMany(OrderCorrectiveAction::class, 'control_of_rodent_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

}
