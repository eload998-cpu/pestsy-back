<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrapCapture extends Model
{
    use HasFactory;

    protected $table = "modules.trap_captures";

    protected $fillable =
        [
        "pest_id",
        "trap_id",
        "quantity",
    ];

    public function pest()
    {
        return $this->belongsTo(Pest::class);
    }
}
