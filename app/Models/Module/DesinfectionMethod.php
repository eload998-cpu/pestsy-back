<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesinfectionMethod extends Model
{
    use HasFactory;

    protected $table = "modules.desinfection_methods";

    protected $fillable = [
        "name",
        "company_id",
    ];

}
