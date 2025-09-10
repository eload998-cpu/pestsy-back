<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = "modules.products";

    protected $fillable = [
        "name",
        "registration_code",
        "batch",
        "expiration_date",
        "company_id",
        "active_ingredient",
        "concentration",
    ];

    //setters

    public function setNameAttribute($value)
    {
        $this->attributes["name"] = stripAccents($value);
    }

}
