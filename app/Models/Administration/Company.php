<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = "administration.companies";

    protected $fillable=[
        "name",
        "logo",
        "phone",
        "email",
        "direction",
        "user_id"
    ];

    protected $appends = [
        "logo_src",
    ];

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    
    public function getLogoSrcAttribute()
    {
        return $this->logo ? config("app.url") . "/" . $this->logo : null;
    }
}
