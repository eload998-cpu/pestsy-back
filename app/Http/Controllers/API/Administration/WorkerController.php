<?php

namespace App\Http\Controllers\API\Administration;

use App\Events\AddRoleEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Worker\CreateWorkerRequest;
use App\Http\Requests\Administration\Worker\UpdateWorkerRequest;
use App\Mail\UserCreationMail;
use App\Models\Administration\UserSubscription;
use App\Models\Module\Operator;
use App\Models\Module\Worker;
use App\Models\Status;
use App\Models\StatusType;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class WorkerController extends Controller
{

    private $worker;
    private $paginate_size = 6;

    public function __construct(Worker $worker)
    {
        $this->worker = $worker;

    }

    public function checkWorkerRole(Request $request)
    {
        $worker = Operator::where('worker_id', $request->id)->first();
        return (!empty($worker)) ? true : false;

    }

    public function createUser(Request $request)
    {
        $worker = Worker::find($request->id);

        if (str_contains($worker->email, '@mail.com')) {
            $validator = \Validator::make([], []);
            $validator->errors()->add('usuario', 'Asigne un correo valido al técnico');
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        //VALIDATE ROLE
        $validation = $this->checkWorkerRole($request);

        if ($validation) {
            $validator = \Validator::make([], []);
            $validator->errors()->add('usuario', 'Este usuario ya posee un rol asignado');
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $status_type = StatusType::where('name', 'user')->first();
        $status = Status::where('status_type_id', $status_type->id)->where('name', 'active')->first();

        $password = Str::random(10);

        $validate_user = User::where('email', $worker->email)->first();

        if ($validate_user) {
            $validator = \Validator::make([], []);
            $validator->errors()->add('error', 'Ya existe un usuario con este correo, modifiquelo para crear el usuario');
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $user = User::create(
            [
                "first_name" => $worker->first_name,
                "last_name" => !empty($worker->last_name) ? $worker->last_name : "Operador",
                "email" => $worker->email,
                "cellphone" => $worker->cellphone,
                "city_id" => Auth::user()->city_id,
                "password" => $password,
                "company_id" => Auth::user()->company_id,
                "status_id" => $status->id,
                "module_name" => Auth::user()->module_name,
            ]);

        $user->password = $password;
        $user->save();

        //ATTACH TO ADMINISTRATOR USER

        $user->operators()->detach();
        $user->operators()->attach(Auth::user()->id, ['worker_id' => $worker->id]);

        updateConnectionSchema("administration");

        $auth_user = Auth::user();
        $subscription = UserSubscription::where('user_id', $auth_user->id)->orderBy('created_at', 'DESC')->get()->first();

        $now = Carbon::now();
        $user->subscriptions()->attach([$subscription->plan_id => ['start_date' => $subscription->start_date, 'end_date' => $subscription->end_date, 'status_id' => $subscription->status_id, 'created_at' => $now]]);

        AddRoleEvent::dispatch($user->id, 'operator');

        if (!str_contains($user->email, '@mail.com')) {

            Mail::to($user->email)->send(new UserCreationMail($user, $password));
        }
        return response()->json(['success' => true, 'message' => 'Exito']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $workers = $this->worker;

        if ($request->search) {
            $search_value = $request->search;
            $workers = $workers->whereRaw("LOWER(workers.first_name) || LOWER(workers.last_name) || LOWER(workers.cellphone) || LOWER(workers.email) ILIKE '%{$search_value}%'");

        }

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'name':

                    $workers = $workers->orderByRaw("workers.first_name || workers.last_name {$request->sort}");

                    break;

                case 'email':
                    $workers = $workers->orderBy('email', $request->sort);
                    break;

            }

        } else {
            $workers = $workers->orderBy("workers.created_at", "desc");

        }

        $workers = $workers
            ->with('administrators')
            ->paginate($this->paginate_size);
        $workers = parsePaginator($workers);

        return response()->json($workers);
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
    public function store(CreateWorkerRequest $request)
    {
        DB::transaction(function () use ($request) {

            $worker = Worker::create($request->all());

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
        $worker = Worker::find($id);

        $worker->load('administrators');

        return response()->json(['success' => true, 'data' => $worker]);

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
    public function update(UpdateWorkerRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->all();
            unset($data["_method"]);
            unset($data["administrators"]);

            $worker = Worker::where('id', $id)->update($data);

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
        $worker = Worker::find($id);

        $worker->users()->delete();
        $worker->delete();
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
