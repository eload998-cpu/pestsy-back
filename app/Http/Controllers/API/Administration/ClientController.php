<?php
namespace App\Http\Controllers\API\Administration;

use App\Events\AddRoleEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Client\CreateClientRequest;
use App\Http\Requests\Administration\Client\UpdateClientRequest;
use App\Mail\UserCreationMail;
use App\Models\Administration\UserSubscription;
use App\Models\Module\Client;
use App\Models\Module\ClientEmail;
use App\Models\Module\SystemUser;
use App\Models\Status;
use App\Models\StatusType;
use App\Models\User;
use Carbon\Carbon;
//MAILS
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClientController extends Controller
{

    private $client;
    private $paginate_size = 6;

    public function __construct(Client $client)
    {
        $this->client = $client;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients = $this->client;
        $user    = Auth::user();

        if ($request->search) {

            $search_value = $request->search;
            $clients      = $clients->where(function ($q) use ($search_value) {
                $q->whereRaw("LOWER(clients.first_name) ILIKE ?", ["%{$search_value}%"])
                    ->orWhereRaw("LOWER(clients.last_name) ILIKE ?", ["%{$search_value}%"])
                    ->orWhereRaw("LOWER(clients.cellphone) ILIKE ?", ["%{$search_value}%"])
                    ->orWhereRaw("LOWER(clients.email) ILIKE ?", ["%{$search_value}%"]);
            });
        }
        $clients = $clients->where('company_id', $user->company_id);

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'name':
                    $clients = $clients->orderByRaw("clients.first_name ||' '||  clients.last_name {$request->sort}");

                    break;

                case 'email':
                    $clients = $clients->orderBy('email', $request->sort);
                    break;

            }

        } else {
            $clients = $clients->orderBy("clients.created_at", "desc");

        }

        $clients = $clients
            ->with('administrators')
            ->paginate($this->paginate_size);

        $clients = parsePaginator($clients);

        return response()->json($clients);
    }

    public function addFile(Request $request)
    {
        return $request->all();
    }

    public function checkClientRole(Request $request)
    {
        $client = SystemUser::where('client_id', $request->id)->first();
        return ! empty($client) ? true : false;

    }

    public function createUser(Request $request)
    {

        $client = Client::find($request->id);

        if (str_contains($client->email, '@mail.com')) {
            $validator = \Validator::make([], []);
            $validator->errors()->add('usuario', 'Asigne un correo valido al cliente');
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        //VALIDATE ROLE
        $validation = $this->checkClientRole($request);

        if ($validation) {
            $validator = \Validator::make([], []);
            $validator->errors()->add('usuario', 'Este usuario ya posee un rol asignado');
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $password = Str::random(10);

        $status_type = StatusType::where('name', 'user')->first();
        $status      = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();

        $validate_user = User::where('email', $client->email)->first();

        if ($validate_user) {
            $validator = \Validator::make([], []);
            $validator->errors()->add('error', 'Ya existe un usuario con este correo, modifiquelo para crear el usuario');
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $user = User::create(
            [
                "email"               => $client->email,
                "first_name"          => $client->first_name,
                "last_name"           => ! empty($client->last_name) ? $client->last_name : "Fumigador",
                "cellphone"           => $client->cellphone,
                "city_id"             => Auth::user()->city_id,
                "password"            => $password,
                "company_id"          => Auth::user()->company_id,
                "status_id"           => $status->id,
                "order_tutorial_done" => true,
            ]);

        $user->password = $password;
        $user->save();

        //ATTACH TO ADMINISTRATOR USER
        $user->systemUsers()->detach();
        $user->systemUsers()->attach($client->id, ['company_id' => $user->company_id]);

        updateConnectionSchema("administration");

        AddRoleEvent::dispatch($user->id, 'system_user');
        if (! str_contains($user->email, '@mail.com')) {

            Mail::to($user->email)->send(new UserCreationMail($user, $password));
        }
        //OPTIONAL EMAILS

        $auth_user    = Auth::user();
        $subscription = UserSubscription::where('user_id', $auth_user->id)->orderBy('created_at', 'DESC')->get()->first();
        $now          = Carbon::now();
        $user->subscriptions()->attach([$subscription->plan_id => ['start_date' => $subscription->start_date, 'end_date' => $subscription->end_date, 'status_id' => $subscription->status_id, 'created_at' => $now]]);

        $id = $auth_user->id;
        updateConnectionSchema("modules");

        foreach ($client->emails as $e) {
            if (! str_contains($e->email, '@mail.com')) {

                Mail::to($e->email)->send(new UserCreationMail($user, $password));
            }
        }
        return response()->json(['success' => true, 'message' => 'Exito']);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateClientRequest $request)
    {

        DB::transaction(function () use ($request) {

            $data   = $request->except(['emails']);
            $client = Client::create($data);

            foreach ($request->emails as $e) {
                ClientEmail::create(
                    [
                        "client_id" => $client->id,
                        "email"     => $e["email"],
                    ]
                );
            }

        });

        return response()->json(['success' => true, 'message' => 'Exito']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $user   = Auth::user();
        $client = Client::where('id', $id)->where('company_id', $user->company->id)->first();

        if (empty($client)) {
            abort(401);

        }
        updateConnectionSchema("modules");

        $client->load('emails');
        $client->load('administrators');

        return response()->json(['success' => true, 'data' => $client]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->except('emails');
            unset($data["_method"]);

            unset($data["administrators"]);
            $client = Client::where('id', $id)->update($data);

            ClientEmail::where('client_id', $id)->delete();

            foreach ($request->emails as $e) {
                if ($e["email"] != "") {
                    ClientEmail::create(
                        [
                            "client_id" => $id,
                            "email"     => $e["email"],
                        ]
                    );
                }

            }

        });

        return response()->json(['success' => true, 'message' => 'Exito']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::find($id);

        $client->users()->delete();
        $client->delete();

        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
