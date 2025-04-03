<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fumigation extends Model
{
    use HasFactory;

    protected $table="fumigations";

    protected $fillable=
    [
        "aplication_id",
        "aplication_place_id",
        "product_id",
        "dose",
        "order_id"
    ];


    public function aplication()
    {
        return $this->belongsTo(Aplication::class);
    }

    public function aplicationPlace()
    {
        return $this->belongsTo(AplicationPlace::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
