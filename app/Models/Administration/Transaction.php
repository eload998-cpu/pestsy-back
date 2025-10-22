<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = "administration.transactions";

    protected $fillable = [
        "user_id",
        "plan_id",
        "status_id",
        "bill_code",
        "type",
        "approved_plan_status_id",
        "data"
    ];

    public function bankTransfers()
    {
        return $this->hasMany(BankTransfer::class, 'transaction_id', 'id');

    }

}
