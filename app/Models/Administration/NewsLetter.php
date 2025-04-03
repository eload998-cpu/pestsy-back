<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
{
    use HasFactory;
    
    protected $table="administration.newsletters";
    
    protected $fillable=[
        "name",
        "email"
    ];
}
