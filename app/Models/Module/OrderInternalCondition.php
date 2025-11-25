<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderInternalCondition extends Model
{
    use HasFactory;

    protected $table = "modules.order_internal_conditions";

    protected $fillable =
        [
        "order_id",
        "value",
        "internal_condition_id",
    ];

    public function internalCondition()
    {
        return $this->belongsTo(InternalCondition::class);
    }

}
