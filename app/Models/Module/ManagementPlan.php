<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementPlan extends Model
{
    use HasFactory;

    protected $table="management_plans";

    protected $fillable=
    [
        "name",
        "file_url",
        "client_id"
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
