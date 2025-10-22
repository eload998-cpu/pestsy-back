<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table="administration.states";
    
    protected $fillable=[
        "name",
        "country_id"
    ];


    public function country()
    {

        return $this->belongsTo(Country::class, 'country_id', 'id');

    }
}
