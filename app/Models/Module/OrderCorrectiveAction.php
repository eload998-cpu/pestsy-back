<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCorrectiveAction extends Model
{
    use HasFactory;

    protected $table = "modules.order_corrective_actions";

    protected $fillable = [
        "control_of_rodent_id",
        "corrective_action_id",
    ];

    public function correctiveAction()
    {
        return $this->belongsTo(CorrectiveAction::class);
    }
}
