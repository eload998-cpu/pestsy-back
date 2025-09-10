<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafetyControl extends Model
{
    use HasFactory;

    protected $table = "modules.safety_controls";

    protected $fillable = [
        "name",
    ];

}
