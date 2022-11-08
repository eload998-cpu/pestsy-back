<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table="clients";

    protected $appends = [
        "full_name"
    ];

    protected $fillable=[
        "first_name",
        "last_name",
        "email",
        "identification_number",
        "cellphone",
        "direction",
        "code"
    ];

    //getters

    public function getFullNameAttribute():string
    {
        $full_name = $this->attributes["first_name"] . " " . $this->attributes["last_name"];

        return ucwords($full_name);
    }

}
