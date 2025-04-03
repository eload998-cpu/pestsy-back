<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = "devices";

    protected $fillable = [
        "name",
    ];

    //getters

    //setters

    public function setNameAttribute($value)
    {
        $this->attributes["name"] = stripAccents($value);
    }

}
