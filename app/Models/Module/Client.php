<?php

namespace App\Models\Module;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = "clients";

    protected $appends = [
        "full_name",
        "parsed_date",
    ];

    protected $fillable = [
        "first_name",
        "last_name",
        "date",
        "email",
        "identification_type",
        "identification_number",
        "cellphone",
        "direction",
        "code",
    ];


    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    
    public function administrators()
    {

        return $this->belongsToMany(User::class, 'system_users', 'client_id', 'administrator_id')->withPivot('client_id');

    }

    public function users()
    {

        return $this->belongsToMany(User::class, 'system_users', 'client_id', 'user_id');

    }

    /*
    protected function date(): Attribute
    {
    return Attribute::make(
    get: fn ($value) => Carbon::parse($value)->format('d/m/Y'),
    );
    }*/

    public function getParsedDateAttribute(): string
    {
        if (!empty($this->attributes["date"])) {
            return Carbon::parse($this->attributes["date"])->format('d/m/Y');

        }

        return Carbon::parse(Carbon::now())->format('d/m/Y');
    }

    public function user()
    {
        updateConnectionSchema("administration");

        return $this->hasOne(User::class);
    }

    //getters

    public function getFullNameAttribute(): string
    {

        $first_name = (!empty($this->attributes["first_name"])) ? $this->attributes["first_name"] : '';
        $last_name = (!empty($this->attributes["last_name"])) ? $this->attributes["last_name"] : '';

        $full_name = $first_name . " " . $last_name;

        return ucwords($full_name);
    }

    //RELATIONSHIPS

    public function emails()
    {
        return $this->hasMany(ClientEmail::class, 'client_id', 'id');
    }

    //setters

    public function setFirstNameAttribute($value)
    {
        $this->attributes["first_name"] = stripAccents($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes["last_name"] = stripAccents($value);
    }
}
