<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectiveAction extends Model
{
    use HasFactory;

    protected $table = "modules.corrective_actions";

    protected $fillable = [
        "name",
        "company_id",
    ];
}
