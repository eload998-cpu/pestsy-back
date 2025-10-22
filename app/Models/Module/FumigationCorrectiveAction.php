<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FumigationCorrectiveAction extends Model
{
    use HasFactory;

    protected $table = "modules.fumigation_corrective_actions";

    protected $fillable = [
        "fumigation_id",
        "corrective_action_id",
    ];

    public function correctiveAction()
    {
        return $this->belongsTo(CorrectiveAction::class);
    }
}
