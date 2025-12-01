<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalCondition extends Model
{
    use HasFactory;

    protected $table = "modules.internal_conditions";

    protected $fillable =
        [
        "name",
        "company_id",
        "is_general",
    ];

}
