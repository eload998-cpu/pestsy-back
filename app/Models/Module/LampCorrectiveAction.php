<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LampCorrectiveAction extends Model
{
    use HasFactory;

    protected $table = "modules.lamp_corrective_actions";

    protected $fillable = [
        "lamp_id",
        "corrective_action_id",
    ];

    public function correctiveAction()
    {
        return $this->belongsTo(CorrectiveAction::class);
    }
}
