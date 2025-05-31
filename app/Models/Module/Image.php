<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Image extends Model
{
    use HasFactory;

    protected $table="modules.images";

    protected $fillable=
    [
        "file_name",
        "order_id"
    ];

    protected $appends = [
        "full_url"
    ];


    //getters

    public function getFullUrlAttribute():string
    {
        return config('app.url').$this->attributes["file_name"];
    }
    /*
    protected function fileName(): Attribute
    {
        return Attribute::make(
            get: function($value){
                return config('app.url').$value;
            }
        );
    }*/
}
