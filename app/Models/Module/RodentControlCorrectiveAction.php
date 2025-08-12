<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RodentControlCorrectiveAction extends Model
{
    use HasFactory;

    protected $table = "modules.rodent_control_corrective_actions";

    protected $fillable = [
        "control_of_rodent_id",
        "corrective_action_id",
    ];

    public function correctiveAction()
    {
        return $this->belongsTo(CorrectiveAction::class);
    }
}
