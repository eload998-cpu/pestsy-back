<?php
namespace App\Models\Administration;

use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table    = "administration.plans";
    protected $fillable = ["name", "period_type", "period", "order_quantity", "status_id", "paypal_id"];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
