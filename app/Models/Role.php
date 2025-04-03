<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Role extends Model
{
    use HasFactory;

    protected $fillable=
    [
        "name",
        "display_name"
    ];

    //RELATIONSHIPS

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');

    }

    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => trans("roles.{$value}"),
        );
    }

}
