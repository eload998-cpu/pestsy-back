<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XylophagusControlCorrectiveAction extends Model
{
    use HasFactory;

    protected $table = "modules.xylophagus_control_corrective_actions";

    protected $fillable = [
        "xylophagus_control_id",
        "corrective_action_id",
    ];

    public function correctiveAction()
    {
        return $this->belongsTo(CorrectiveAction::class);
    }
}
