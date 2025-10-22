<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PestBitacore extends Model
{
    use HasFactory;

    
    protected $table="modules.pest_bitacores";

    protected $fillable=
    [
        "pest_id",
        "control_of_rodent_id",
        "quantity"
    ];

    public function pest()
    {
        return $this->belongsTo(Pest::class);
    }

}
