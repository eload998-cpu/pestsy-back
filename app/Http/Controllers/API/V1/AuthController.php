<?php
namespace App\Http\Controllers\API\V1;

use App\Classes\SchemaBuilder;
use App\Events\AddRoleEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\CreateUserRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Mail\RegisterMail;
use App\Models\Administration\City;
use App\Models\Administration\Company;
use App\Models\Administration\Country;
use App\Models\Administration\FacebookDeleteData;
use App\Models\Administration\Plan;
use App\Models\Administration\State;
use App\Models\Status;
use App\Models\StatusType;
use App\Models\User;
use App\Services\PaypalService;
//EVENTS
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//MAILS
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    private User $user;
    private Company $company;
    private SchemaBuilder $schema;
    private $paypalService;

    private $password;

    public function __construct(User $user, Company $company, SchemaBuilder $schema, PaypalService $paypalService)
    {
        $this->user          = $user;
        $this->company       = $company;
        $this->schema        = $schema;
        $this->password      = env('PRE_LOGIN_TOKEN');
        $this->paypalService = $paypalService;

    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback(Request $request)
    {

        if (isset($request->error_code)) {
            return redirect(config('app.front_app_base_url'));
        }

        $user = Socialite::driver('facebook')->user();
        // if the user exits, use that user and login
        $finduser = User::where('email', $user->email)->first();
        if ($finduser) {
            //if the user exists, login and show dashboard
            if (! empty($finduser->tokens)) {
                $finduser->tokens->each(function ($token) {$token->delete();});
                unset($finduser->tokens);
            }

            $token = $finduser->createToken('auth_token')->accessToken;

            //ADDING LAST ORDER
            updateConnectionSchema('administration');
            $finduser->load('roles.permissions');

            $user_role                = $finduser->roles()->first()->name;
            $finduser["subscription"] = $finduser->subscriptions()->latest('user_subscriptions.created_at')->first();

            $module_name = $finduser->module_name;
            updateConnectionSchema($module_name);

            switch ($user_role) {

                case 'operator':

                    $last_order                           = $finduser->lastOrder($module_name, $finduser->id);
                    $last_system_order_number             = $finduser->lastSystemOrderNumber($module_name);
                    $user                                 = $finduser->toArray();
                    $finduser["last_order"]               = $last_order;
                    $finduser["last_system_order_number"] = $last_system_order_number;
                    break;

                default:
                    if ($user_role == 'fumigator' || $user_role == 'administrator' || $user_role == 'super_administrator') {

                        $last_order                           = $finduser->lastOrder($module_name, $finduser->id);
                        $last_system_order_number             = $finduser->lastSystemOrderNumber($module_name);
                        $finduser                             = $finduser->toArray();
                        $finduser["last_order"]               = $last_order;
                        $finduser["last_system_order_number"] = $last_system_order_number;
                        $finduser["pending_transaction"]      = $finduser->pending_transaction;

                    }
                    break;

            }

            // Encrypting a string
            $stringToEncrypt = 'E4i0kgYtoK';
            $encryptedString = Crypt::encryptString($stringToEncrypt, $this->password);

            return redirect(config('app.front_app_base_url') . 'pre-login?email=' . $finduser["email"] . '&token=' . $encryptedString);

        } else {
            //user is not yet created, so create first
            $password       = Str::random(10);
            $user_full_name = explode(" ", $user->name);
            $data           = [
                "company_name"          => "Fumigadora " . $user->name,
                "first_name"            => $user_full_name[0],
                "last_name"             => isset($user_full_name[1]) ? $user_full_name[1] : 'Administrador',
                "email"                 => $user->email,
                "password"              => $password,
                "password_confirmation" => $password,
                "cellphone"             => null,
                "country_id"            => 44,
                "city_id"               => 18198,
                "state_id"              => 742,
                "oauth_facebook_id"     => $user->id,
                "oauth"                 => true,
            ];

            $request = new CreateUserRequest;
            $request->replace($data);

            return $this->register($request);

        }

    }

    public function preLogin(Request $request)
    {
        $status_type = StatusType::where('name', 'user')->first();
        $status      = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();

        $user = $this->user->where("email", "=", $request->email)->first();

        if (! empty($user)) {

            if ($user->status_id != $status->id) {
                return response()->json(['errors' => ['message' => 'Por favor renueve su suscripci贸n']], 422);

            }

            if (! empty($user->tokens)) {
                $user->tokens->each(function ($token) {$token->delete();});
                unset($user->tokens);
            }

            $token = $user->createToken('auth_token')->accessToken;

            //ADDING LAST ORDER
            updateConnectionSchema('administration');
            $user->load('roles.permissions');

            $user_role            = $user->roles()->first()->name;
            $user["subscription"] = $user->subscriptions()->latest('user_subscriptions.created_at')->first();

            $module_name = $user->module_name;
            updateConnectionSchema($module_name);
            switch ($user_role) {

                case 'operator':

                    $last_order                       = $user->lastOrder($module_name, $user->id);
                    $last_system_order_number         = $user->lastSystemOrderNumber($module_name);
                    $user                             = $user->toArray();
                    $user["last_order"]               = $last_order;
                    $user["last_system_order_number"] = $last_system_order_number;
                    break;

                default:
                    if ($user_role == 'fumigator' || $user_role == 'administrator' || $user_role == 'super_administrator') {

                        $last_order               = $user->lastOrder($module_name, $user->id);
                        $last_system_order_number = $user->lastSystemOrderNumber($module_name);
                        $country_name             = $user->city->state->country->name;
                        $pending_transaction      = $user->pending_transaction;

                        $user = $user->toArray();

                        $user["country_name"]             = $country_name;
                        $user["last_order"]               = $last_order;
                        $user["last_system_order_number"] = $last_system_order_number;
                        $user["pending_transaction"]      = $pending_transaction;

                    }
                    break;

            }

            return response()->json([
                'user'          => $user,
                "authorization" => [
                    'token' => $token,
                    'type'  => "Bearer",
                ],
            ]);
        } else {
            //user is not yet created, so create first
            $password       = Str::random(10);
            $user_full_name = $request->name;
            $ip_stack_api   = config('app.ip_stack');
            $user_ip        = request()->ip();
            $response       = Http::get("http://api.ipstack.com/{$user_ip}?access_key={$ip_stack_api}");
            $data           = $response->json();

            $country = Country::where('name', 'ilike', '%' . $data['country_name'] . '%')->first();
            $state   = State::where('country_id', $country->id)->first();
            $city    = City::where('state_id', $state->id)->first();
            $data    = [
                "company_name"          => "Fumigadora " . $request->name,
                "first_name"            => $request->givenName,
                "last_name"             => $request->familyName,
                "email"                 => $request->email,
                "password"              => $password,
                "password_confirmation" => $password,
                "cellphone"             => null,
                "country_id"            => ! empty($country) ? $country->id : 44,
                "city_id"               => ! empty($city) ? $city->id : 18198,
                "state_id"              => ! empty($state) ? $state->id : 742,
                "oauth_google_id"       => $request->id,
                "oauth"                 => true,
            ];

            $request = new CreateUserRequest;
            $request->replace($data);

            return $this->register($request);
        }

    }

    public function login(LoginRequest $request)
    {

        try {
            $status_type = StatusType::where('name', 'user')->first();
            $status      = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();

            $valid_credentials = Auth::guard('web')->attempt([
                "email"     => $request->email,
                "password"  => $request->password,
                "status_id" => $status->id,
            ]);

            $user = $this->user->firstWhere("email", "=", $request->email);

            if (! $request->oauth) {
                if (! $valid_credentials) {
                    if ($user) {
                        if ($user->status_id != $status->id) {
                            return response()->json(['errors' => ['message' => 'Por favor renueve su suscripcion']], 422);

                        }
                    }

                    return response()->json(['errors' => ['message' => 'Credenciales invalidas']], 422);
                }
            } else {
                $user = $this->user->where("email", "=", $request->email)->where('oauth_google_id', $request->password)->first();

                if (empty($user)) {
                    return response()->json(['errors' => ['message' => 'Credenciales invalidas']], 422);
                }
            }

            if (! empty($user->tokens)) {
                $user->tokens->each(function ($token) {$token->delete();});
                unset($user->tokens);
            }

            $client_id     = config('app.client_id');
            $client_secret = config('app.client_secret');

            $response = \Illuminate\Support\Facades\Http::asForm()->post(config('app.oauth_url') . '/oauth/token', [
                'grant_type'    => 'password',
                'client_id'     => $client_id,
                'client_secret' => $client_secret,
                'username'      => $request->email,
                'password'      => $request->password,
                'scope'         => '',
            ])->json();

            $token         = $response["access_token"];
            $refresh_token = $response["refresh_token"];

            //ADDING LAST ORDER
            updateConnectionSchema('administration');
            $user->load('roles.permissions');

            $user_role            = $user->roles()->first()->name;
            $user["subscription"] = $user->subscriptions()->latest('user_subscriptions.created_at')->first();

            $module_name = $user->module_name;
            updateConnectionSchema($module_name);

            switch ($user_role) {

                case 'operator':

                    $last_order                       = $user->lastOrder($module_name, $user->id);
                    $last_system_order_number         = $user->lastSystemOrderNumber($module_name);
                    $user                             = $user->toArray();
                    $user["last_order"]               = $last_order;
                    $user["last_system_order_number"] = $last_system_order_number;
                    break;

                default:
                    if ($user_role == 'fumigator' || $user_role == 'administrator' || $user_role == 'super_administrator') {

                        $last_order               = $user->lastOrder($module_name, $user->id);
                        $last_system_order_number = $user->lastSystemOrderNumber($module_name);
                        $country_name             = $user->city->state->country->name;
                        $pending_transaction      = $user->pending_transaction;

                        $user = $user->toArray();

                        $user["country_name"]             = $country_name;
                        $user["last_order"]               = $last_order;
                        $user["last_system_order_number"] = $last_system_order_number;
                        $user["pending_transaction"]      = $pending_transaction;

                    }
                    break;

            }

            return response()->json([
                'user'          => $user,
                "authorization" => [
                    'token'         => $token,
                    'refresh_token' => $refresh_token,
                    'type'          => "Bearer",
                ],
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            abort();
        }

    }

    public function refreshToken(Request $request)
    {

        $client_id     = config('app.client_id');
        $client_secret = config('app.client_secret');

        $response = \Illuminate\Support\Facades\Http::asForm()->post(config('app.oauth_url') . '/oauth/token', [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $request->token,
            'client_id'     => $client_id,
            'client_secret' => $client_secret,
            'scope'         => '',
        ])->json();
        $token         = $response["access_token"];
        $refresh_token = $response["refresh_token"];

        return response()->json([
            'token'         => $token,
            'refresh_token' => $refresh_token,
        ]);
    }

    public function logout()
    {
        $user = Auth::user()->token();
        $user->revoke();
        $user = User::find(Auth::user()->id);
        $user->tokens->each(function ($token) {$token->delete();});

        return response()->json([
            'success' => true,
            'message' => 'Sesi贸n cerrada con exito',
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {

        $user      = Auth::user();
        $user_role = $user->roles()->first()->name;

        $user = DB::transaction(function () use ($request) {

            $data = array_filter($request->all());

            $user    = $this->user->find(Auth::user()->id);
            $company = Company::find($user->company_id);

            if ($request->first_name) {
                $user->first_name = $request->first_name;
            }

            if ($request->last_name) {
                $user->last_name = $request->last_name;
            }

            if ($request->email) {
                $user->email = $request->email;
            }

            if ($request->cellphone) {
                $user->cellphone = $request->cellphone;
            }

            if ($request->profile_picture) {

                $company->logo = saveFileInStorageAndReturnPath($request->profile_picture, "administracion/usuarios/company");
                $company->save();
            }

            if ($request->password) {
                $user->password = $request->password;
            }

            $user->save();

            return $user;

        });

        if ($user_role == 'fumigator' || $user_role == 'administrator' || $user_role == 'super_administrator') {

            $company = DB::transaction(function () use ($request) {

                $data = array_filter($request->all());

                $company = $this->company->find(Auth::user()->company_id);

                if ($request->company_name) {
                    $company->name = $request->company_name;
                }

                if ($request->company_phone) {
                    $company->phone = $request->company_phone;
                }

                if ($request->company_email) {
                    $company->email = $request->company_email;
                }

                $company->direction = ($request->company_direction) ? $request->company_direction : null;

                if ($request->logo) {

                    $company->logo = saveFileInStorageAndReturnPath($request->logo, "administracion/usuarios/company");
                }

                $company->save();

                return $company;

            });

        }

        return response()->json(['success' => true, 'data' => $user, 'message' => 'Exito!']);

    }

    public function authUser(Request $request)
    {

        $user = Auth::User();
        updateConnectionSchema("administration");
        $user->load('roles.permissions');
        $user["subscription"] = $user->subscriptions()->latest('user_subscriptions.created_at')->first();
        $user["country_name"] = $user->city->state->country->name;
        $user_role            = $user->roles()->first()->name;
        $module_name          = $user->module_name;
        updateConnectionSchema($module_name);
        switch ($user_role) {

            case 'operator':

                $last_order                       = $user->lastOrder($module_name, $user->id);
                $last_system_order_number         = $user->lastSystemOrderNumber($module_name);
                $user                             = $user->toArray();
                $user["last_order"]               = $last_order;
                $user["last_system_order_number"] = $last_system_order_number;
                break;

            case 'system_user':

                updateConnectionSchema($module_name);
                break;

            default:
                if ($user_role == 'fumigator' || $user_role == 'administrator' || $user_role == 'super_administrator') {

                    $last_order               = $user->lastOrder($module_name, $user->id);
                    $last_system_order_number = $user->lastSystemOrderNumber($module_name);
                    $premiumPlan              = Plan::where('name', 'Premium')->first();
                    $wasPremium               = $user->subscriptions()->where("plan_id", $premiumPlan->id)->first();
                    $wasPremium               = ! empty($wasPremium) ? true : false;
                    $pending_transaction      = $user->pending_transaction;

                    $user = $user->toArray();

                    $user["last_order"]               = $last_order;
                    $user["wasPremium"]               = $wasPremium;
                    $user["last_system_order_number"] = $last_system_order_number;
                    $user["pending_transaction"]      = $pending_transaction;

                }
                break;

        }
        return response()->json($user);
    }

    public function register(CreateUserRequest $request)
    {

        $company = $this->company->create([
            "name"  => $request->company_name,
            "email" => $request->email,
            "phone" => $request->cellphone,
        ]);

        $status_type = StatusType::where('name', 'user')->first();
        $status      = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();
        $user        = $this->user->create([
            "first_name" => $request->first_name,
            "last_name"  => $request->last_name,
            "cellphone"  => $request->cellphone,
            "email"      => $request->email,
            "password"   => $request->password,
            "city_id"    => $request->city_id,
            "company_id" => $company->id,
            "status_id"  => $status->id,

        ]);

        $user->module_name = "module_" . $user->id;
        $user->save();

        $data = [
            "email"    => $user->email,
            "password" => $request->password,
        ];

        if ($request->oauth) {
            $user->oauth_google_id = $request->oauth_google_id;
            $user->save();

            $data["oauth"]    = true;
            $data["password"] = $request->oauth_google_id;

        }

        //CREATE SCHEMAS
        $this->schema->createSchemas($user->id);
        updateConnectionSchema('administration');
        AddRoleEvent::dispatch($user->id, 'fumigator');

        $plan = Plan::where('name', 'Gratis')->first();
        $now  = Carbon::now();

        $status_type = StatusType::where('name', 'plan')->first();
        $status      = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();

        $user->subscriptions()->attach([$plan->id => ['start_date' => $now, 'end_date' => Carbon::parse($now)->addDays(7), 'status_id' => $status->id, 'created_at' => $now]]);

        $company                 = Company::find($user->company->id);
        $company->order_quantity = $plan->order_quantity;
        $company->save();

        if (! str_contains($user->email, '@mail.com')) {

            Mail::to($user->email)->send(new RegisterMail($user));
        }
        $request = new LoginRequest;

        $request->replace($data);
        updateConnectionSchema('public');

        return ($request->oauth) ? $this->preLogin($request) : $this->login($request);

    }

    public function skipTutorial()
    {
        $user                = User::find(auth()->user()->id);
        $user->tutorial_done = true;
        $user->save();
        return response()->json(['success' => true, 'data' => $user, 'message' => 'Exito!']);

    }

    public function deleteData(Request $request)
    {

        $signed_request = $request->signed_request;
        $data           = parse_signed_request($signed_request);
        $user_id        = $data['user_id'];
        $url            = env('PROD_APP_URL');
        // Start data deletion
        $user = User::where('oauth_facebook_id', $data['user_id'])->first();

        #REMOVE SCHEMA
        $this->schema->destroySchema($user->module_name);

        $user->delete();
        $random = Str::random(8);
        FacebookDeleteData::create(["code" => $random, "uid" => $random]);

        $confirmation_code = $random;                                                  // unique code for the deletion request
        $status_url        = $url . '/facebook/deleted-data?id=' . $confirmation_code; // URL to track the deletion

        $data = [
            'url'               => $status_url,
            'confirmation_code' => $confirmation_code,
        ];
        echo json_encode($data);

    }

    public function checkDeletedData(Request $request)
    {
        $f = FacebookDeleteData::where(["code" => $request->id])->first();

        return ($f) ? $f : [];

    }

}
