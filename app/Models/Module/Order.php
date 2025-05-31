<?php
namespace App\Models\Module;

use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table   = "modules.orders";
    protected $appends = [
        "parsed_arrive_time",
        "parsed_start_time",
        "parsed_end_time",
        "parsed_date",
    ];

    protected $fillable = [
        "order_number",
        "client_id",
        "worker_id",
        "date",
        "direction",
        "service_type",
        "arrive_time",
        "start_time",
        "end_time",
        "coordinator",
        "origin",
        "status_id",
        "company_id",
        "user_id",
    ];
    /*
    protected function date(): Attribute
    {
        return Attribute::make(
            get:function($value)
            {
                if(!$this->isDate($value))
                {
                    return Carbon::parse($value)->format('d/m/Y');
                }

                return $value;
            }
        );
    }*/

    public function getParsedDateAttribute(): string
    {

        return Carbon::parse($this->attributes["date"])->format('d/m/Y');
    }

    public function isDate($string)
    {
        $matches = [];
        $pattern = '/^([0-9]{1,2})\\/([0-9]{1,2})\\/([0-9]{4})$/';
        if (! preg_match($pattern, $string, $matches)) {
            return false;
        }

        if (! checkdate($matches[2], $matches[1], $matches[3])) {
            return false;
        }

        return true;
    }

    //GETTERS

    public function getParsedArriveTimeAttribute(): string
    {
        $timestamp = strtotime($this->attributes["arrive_time"]);
        if (! empty($timestamp)) {
            $time = date('g:i a', $timestamp);
            return $time;
        }

        return "";
    }

    public function getParsedStartTimeAttribute(): string
    {
        $timestamp = strtotime($this->attributes["start_time"]);
        if (! empty($timestamp)) {
            $time = date('g:i a', $timestamp);
            return $time;
        }

        return "";
    }

    public function getParsedEndTimeAttribute(): string
    {
        $timestamp = strtotime($this->attributes["end_time"]);
        if (! empty($timestamp)) {
            $time = date('g:i a', $timestamp);
            return $time;
        }

        return "";
    }

    #RELATIONSHIPS

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function externalCondition()
    {
        return $this->hasOne(ExternalCondition::class, 'order_id', 'id');
    }

    public function internalCondition()
    {
        return $this->hasOne(InternalCondition::class, 'order_id', 'id');
    }

    public function infestationGrade()
    {
        return $this->hasOne(InfestationGrade::class, 'order_id', 'id');
    }

    public function rodentControls()
    {
        return $this->hasMany(RodentControl::class, 'order_id', 'id');
    }

    public function xylophageControl()
    {
        return $this->hasMany(XylophageControl::class, 'order_id', 'id');
    }

    public function legionellaControl()
    {
        return $this->hasMany(LegionellaControl::class, 'order_id', 'id');
    }

    public function fumigations()
    {
        return $this->hasMany(Fumigation::class, 'order_id', 'id');
    }

    public function lamps()
    {
        return $this->hasMany(Lamp::class, 'order_id', 'id');
    }

    public function traps()
    {
        return $this->hasMany(Trap::class, 'order_id', 'id');
    }

    public function observations()
    {
        return $this->hasMany(Observation::class, 'order_id', 'id');
    }

    public function signatures()
    {
        return $this->hasMany(Signature::class, 'order_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'order_id', 'id');
    }
}
