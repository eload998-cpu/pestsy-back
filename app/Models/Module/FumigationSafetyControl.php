<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FumigationSafetyControl extends Model
{
    use HasFactory;

    protected $table = "modules.fumigation_safety_controls";

    protected $fillable = [
        "fumigation_id",
        "safety_control_id",
    ];

    public function safetyControl()
    {
        return $this->belongsTo(SafetyControl::class);
    }
}
