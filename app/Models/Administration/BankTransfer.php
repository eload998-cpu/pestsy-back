<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransfer extends Model
{
    use HasFactory;

    protected $table="administration.bank_transfers";
    
    protected $fillable=[
        "reference","user_id","status_id","transaction_id"
    ];
}
