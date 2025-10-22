<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemUser extends Model
{
    use HasFactory;

    protected $table    = "modules.system_users";
    protected $fillable = ["client_id", "user_id", "company_id"];
}
