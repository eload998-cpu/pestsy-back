<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LampCapture extends Model
{
    use HasFactory;
    protected $table = "modules.lamp_captures";

    protected $fillable =
        [
        "pest_id",
        "lamp_id",
        "quantity",
    ];

    public function pest()
    {
        return $this->belongsTo(Pest::class);
    }
}
