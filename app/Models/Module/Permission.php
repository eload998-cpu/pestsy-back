<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table="permissions";

    protected $fillable=
    [
        "name",
        "file_url"
    ];

    
    protected function fileUrl(): Attribute
    {
        return Attribute::make(
            get: function($value){
                return config('app.url').$value;
            }
        );
    }

}
