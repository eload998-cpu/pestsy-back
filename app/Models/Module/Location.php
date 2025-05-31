<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = "modules.locations";

    protected $fillable = [
        "name",
        "is_legionella",
        "company_id",
    ];

    //getters

    //setters

    public function setNameAttribute($value)
    {
        $this->attributes["name"] = stripAccents($value);
    }

}
