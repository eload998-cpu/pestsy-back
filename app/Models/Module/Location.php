<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table="locations";

    protected $fillable=[
        "name",
    ];

    //getters

     //setters

     public function setNameAttribute($value)
     {
         $this->attributes["name"] = stripAccents($value);
     }


}
