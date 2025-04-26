<?php
namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletedAccount extends Model
{
    use HasFactory;

    protected $table = "administration.deleted_accounts";


    protected $fillable =
        [
        "email",
    ];
}
