<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aplication extends Model
{
    use HasFactory;

    protected $table = "modules.aplications";

    protected $fillable = [
        "name",
        "company_id",
    ];

    //setters

    public function setNameAttribute($value)
    {
        $this->attributes["name"] = stripAccents($value);
    }
}
