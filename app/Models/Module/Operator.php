<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $table    = "modules.operators";
    protected $fillable = ["worker_id", "user_id", "company_id"];

}
