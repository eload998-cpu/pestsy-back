<?php
namespace App\Models\Module;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $table = "modules.workers";

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
        "company_id",
        "certification_title",
        "certification_date",
        "certifying_entity",
    ];

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function administrators()
    {

        return $this->belongsToMany(User::class, 'operators', 'worker_id');

    }

    public function users()
    {

        return $this->belongsToMany(User::class, 'operators', 'worker_id', 'user_id');

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

        if (! empty($this->attributes["date"])) {
            return Carbon::parse($this->attributes["date"])->format('d/m/Y');

        }

        return Carbon::parse(Carbon::now())->format('d/m/Y');
    }

    //getters

    public function getFullNameAttribute(): string
    {

        $first_name = (! empty($this->attributes["first_name"])) ? $this->attributes["first_name"] : '';
        $last_name  = (! empty($this->attributes["last_name"])) ? $this->attributes["last_name"] : '';

        $full_name = $first_name . " " . $last_name;

        return ucwords($full_name);
    }

}
