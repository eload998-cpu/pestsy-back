<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table ="administration.plans";
    protected $fillable = ["name","period_type","period","order_quantity","status_id","paypal_id"];
}
