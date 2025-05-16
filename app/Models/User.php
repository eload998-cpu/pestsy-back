<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Administration\City;
use App\Models\Administration\Company;
use App\Models\Administration\Plan;
use App\Models\Administration\Transaction;
use App\Models\Module\Order;
use App\Models\Status;
use App\Models\StatusType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "administration.users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'cellphone',
        'profile_picture',
        'password',
        'city_id',
        'company_id',
        'status_id',
        'oauth_google_id',
        'oauth_facebook_id',
        'module_name',
        'last_email_sent',
        'paypal_subscription_id',
        'active_subscription',
        'verify_paypal_subscription'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = [
        "profile_photo_src",
        "profile_photo_logo_src",
        "full_name",
        "company_logo",
    ];

    //RELATIONSHIP

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function city()
    {
        updateConnectionSchema("administration");

        return $this->belongsTo(City::class, 'city_id', 'id');

    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, "company_id", "id");
    }

    public function roles()
    {
        updateConnectionSchema("administration");

        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id');

    }

    public function subscriptions()
    {
        updateConnectionSchema("administration");

        return $this->belongsToMany(Plan::class, 'user_subscriptions', 'user_id', 'plan_id')->withPivot('id', 'start_date', 'end_date', 'status_id');

    }

    public function systemUsers()
    {
        return $this->belongsToMany(User::class, 'system_users', 'user_id', 'administrator_id')->withPivot('client_id');

    }

    public function operators()
    {

        return $this->belongsToMany(User::class, 'operators', 'user_id', 'administrator_id');

    }

    public function lastOrder($module_name, $user_id)
    {

        updateConnectionSchema($module_name);

        $status_type = StatusType::where('name', 'order')->first();
        $status = Status::where('status_type_id', $status_type->id)->where('name', 'in process')->first();
        $order = Order::where('status_id', $status->id)->where('user_id', $user_id)->latest()->first();
        $arr = [];

        if (!empty($order)) {

            $last_order = intval($order->order_number);
            $arr["id"] = $order->id;
            $arr["last_order"] = $last_order;
            $arr["service_type"] = $order->service_type;

            return $arr;
        }

        return "";

    }

    public function lastSystemOrderNumber($module_name)
    {

        updateConnectionSchema($module_name);

        if (!empty(Order::latest()->first())) {
            $last_order = intval(Order::latest()->first()->order_number);
            return str_pad($last_order + 1, 3, '0', STR_PAD_LEFT);
        }

        return "";

    }

    //APPENDS

    //GETTERS

    public function getPendingTransactionAttribute(): bool
    {   

        try{

            $status_type = StatusType::where('name', 'transaction')->first();
            $status = Status::where('status_type_id', $status_type->id)->where('name', 'pending')->first();
    
            $transaction = $this->transactions()
                ->where('status_id', $status->id)
                ->orderBy('created_at', 'DESC')->get()->first();
    
            return (!$this->attributes["active_subscription"] && !empty($transaction)) ? true : false;
        }catch(\Exception $e)
        {
            \Log::error($e);
        }

       
    }

    public function getFullNameAttribute(): string
    {

        $full_name = $this->attributes["first_name"] . " " . $this->attributes["last_name"];

        return ucwords($full_name);
    }

    public function getCompanyLogoAttribute(): string
    {
        $company = $this->company;
        $logo = $company->logo;

        return ($logo) ? $logo : "";
    }



    //SETTERS

    public function setPasswordAttribute($value)
    {
        $this->attributes["password"] = bcrypt($value);
    }

    public function setSchemaConnection(string $module)
    {
        updateConnectionSchema($module);
    }

    public function getProfilePhotoSrcAttribute()
    {
        return $this->profile_picture ? config("app.url") . "/" . $this->profile_picture : null;
    }

    public function getProfilePhotoLogoSrcAttribute()
    {
        return $this->company->logo ? config("app.url") . "/" . $this->company->logo : null;
    }
    //HELPERS

    public function hasOneOfTheseRoles(...$roles)
    {

        if ($this->hasRoles()) {
            $required_roles = is_array($roles[0]) ?
            collect($roles)->collapse() :
            collect(func_get_args());

            $rolesOfThisUser = self::getRolesOfThisUser();

            foreach ($required_roles as $required_role) {
                if ($rolesOfThisUser->contains($required_role)) {
                    return true;
                }

            }

            return false;
        }

        return false;
    }

    public function hasRoles()
    {
        return $this->roles->isNotEmpty();
    }

    private function getRolesOfThisUser()
    {
        $role_names = collect();

        $this->roles->each(function ($role) use ($role_names) {
            $role_names->push($role->name);
        });

        return $role_names;
    }

}
