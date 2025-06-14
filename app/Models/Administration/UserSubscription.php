<?php
namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $table    = "administration.user_subscriptions";
    protected $fillable = [
        "user_id",
        "plan_id",
        "start_date",
        "end_date",
        "status_id",
    ];

}
