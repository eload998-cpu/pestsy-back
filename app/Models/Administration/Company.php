<?php
namespace App\Models\Administration;

use App\Models\Module\Order;
use App\Models\Status;
use App\Models\StatusType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = "administration.companies";

    protected $fillable = [
        "name",
        "logo",
        "phone",
        "email",
        "direction",
        "user_id",
    ];

    protected $appends = [
        "logo_src",
    ];

    public function deleteAccountRequest()
    {
        return $this->hasOne(DeleteAccountRequest::class, 'company_id', 'id');
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function getLogoSrcAttribute()
    {
        return $this->logo ? config("app.url") . "/" . $this->logo : null;
    }

    public function lastOrder($user_id)
    {

        updateConnectionSchema("modules");

        $status_type = StatusType::where('name', 'order')->first();
        $status      = Status::where('status_type_id', $status_type->id)->where('name', 'in process')->first();
        $order       = Order::where('status_id', $status->id)
            ->where('company_id', $this->id)
            ->where('user_id', $user_id)
            ->latest()
            ->first();
        $arr = [];

        if (! empty($order)) {

            $last_order          = intval($order->order_number);
            $arr["id"]           = $order->id;
            $arr["last_order"]   = $last_order;
            $arr["service_type"] = $order->service_type;

            return $arr;
        }

        return "";

    }

    public function lastSystemOrderNumber()
    {

        updateConnectionSchema("modules");

        if (! empty(Order::where('company_id', $this->id)->latest()->first())) {
            $last_order = intval(Order::where('company_id', $this->id)->latest()->first()->order_number);
            return str_pad($last_order + 1, 3, '0', STR_PAD_LEFT);
        }

        return "";

    }

}
