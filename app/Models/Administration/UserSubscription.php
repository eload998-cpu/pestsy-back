<?php
namespace App\Models\Administration;

use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserSubscription extends Model
{
    use HasFactory;

    protected $table = "administration.user_subscriptions";

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
