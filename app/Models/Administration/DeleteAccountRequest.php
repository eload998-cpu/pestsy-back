<?php
namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeleteAccountRequest extends Model
{
    use HasFactory;

    protected $table = "administration.delete_account_requests";

    protected $fillable =
        [
        "company_id",
        "reason",
    ];
}
