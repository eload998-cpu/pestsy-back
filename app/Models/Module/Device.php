<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = "modules.devices";

    protected $fillable = [
        "name",
        "company_id",
    ];

    //getters

    //setters

    public function setNameAttribute($value)
    {
        $this->attributes["name"] = stripAccents($value);
    }

}
