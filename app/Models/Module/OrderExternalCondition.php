<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderExternalCondition extends Model
{
    use HasFactory;

    protected $table = "modules.order_external_conditions";

    protected $fillable =
        [
        "order_id",
        "value",
        "external_condition_id",
    ];

    public function externalCondition()
    {
        return $this->belongsTo(ExternalCondition::class);
    }

}
