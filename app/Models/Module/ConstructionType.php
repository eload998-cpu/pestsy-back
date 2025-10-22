<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructionType extends Model
{
    use HasFactory;

    protected $table = "modules.construction_types";

    protected $fillable = [
        "name",
        "company_id",
    ];

}
