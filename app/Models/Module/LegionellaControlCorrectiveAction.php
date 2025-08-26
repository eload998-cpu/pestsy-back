<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegionellaControlCorrectiveAction extends Model
{
    use HasFactory;

    protected $table = "modules.legionella_control_corrective_actions";

    protected $fillable = [
        "legionella_control_id",
        "corrective_action_id",
    ];

    public function correctiveAction()
    {
        return $this->belongsTo(CorrectiveAction::class);
    }
}
