<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrapCorrectiveAction extends Model
{
    use HasFactory;

    protected $table = "modules.trap_corrective_actions";

    protected $fillable = [
        "trap_id",
        "corrective_action_id",
    ];

    public function correctiveAction()
    {
        return $this->belongsTo(CorrectiveAction::class);
    }
}
