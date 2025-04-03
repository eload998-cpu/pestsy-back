<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AplicationPlace extends Model
{
    use HasFactory;
    protected $table="aplication_places";

    protected $fillable = [
        "name"
    ];


     //setters

     public function setNameAttribute($value)
     {
         $this->attributes["name"] = stripAccents($value);
     }
}
