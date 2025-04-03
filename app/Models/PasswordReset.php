<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = "public.password_resets";
    const id = null;
    const UPDATED_AT = null;
	protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable=[
        "email",
        "token"
    ];

}
