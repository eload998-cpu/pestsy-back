<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Order\CreateOrderRequest;
use App\Mail\OrderMail;
use App\Models\Administration\Company;
use App\Models\Module\Client;
use App\Models\Module\ExternalCondition;
use App\Models\Module\InfestationGrade;
use App\Models\Module\InternalCondition;
use App\Models\Module\Order;
use App\Models\Module\Worker;
use App\Models\Status;
use App\Models\StatusType;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{

    private $order;
    private $paginate_size = 6;
    private $last_order;

    public function __construct(Order $order)
    {
        $this->order = $order;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status_type  = StatusType::where('name', 'order')->first();
        $status       = Status::where('status_type_id', $status_type->id)->where('name', $request->condition)->first();
        $user         = Auth::user();
        $role_of_user = "";
        $client_id    = null;
        $module_name  = $user->module_name;
        $user_role    = $user->roles()->first()->name;
        switch ($user_role) {

            case 'system_user':
                updateConnectionSchema($module_name);

                $client_id = $user->systemUsers()->first()->pivot->client_id;

                break;

        }
        updateConnectionSchema($module_name);

        $orders = $this->order
            ->where('status_id', $status->id)
            ->leftJoin('clients', 'orders.client_id', 'clients.id')
            ->select('orders.*', DB::raw("COALESCE(clients.first_name || ' ' || clients.last_name, clients.first_name) AS client_name"))
            ->with('status');

        if ($request->search) {
            $search_value = $request->search;
            $orders       = $orders->whereRaw("LOWER(clients.first_name) || LOWER(clients.last_name) || LOWER(orders.order_number)  || LOWER(orders.date) ILIKE '%{$search_value}%'");

        }

        if (! empty($client_id)) {
            $orders = $orders->where('orders.client_id', $client_id);
        }

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'order_number':
                    $orders = $orders->orderBy("order_number", $request->sort);
                    break;

                case 'client_name':

                    $sortOrder = ($request->sort == "ASC") ? "DESC" : "ASC";
                    $orders    = $orders->orderByRaw("clients.first_name || clients.last_name {$sortOrder}");
                    break;

                case 'direction':
                    $orders = $orders->orderBy("direction", $request->sort);
                    break;

                case 'service_type':

                    $sortOrder = ($request->sort == "ASC") ? "DESC" : "ASC";

                    $orders = $orders->orderBy("service_type", $sortOrder);
                    break;

                case 'date':
                    $orders = $orders->orderBy("date", $request->sort);
                    break;
            }

        } else {

            $orders = $orders->orderBy("orders.created_at", "desc");

        }

        $orders = $orders->paginate($this->paginate_size);

        $orders = parsePaginator($orders);

        return response()->json($orders);
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

    public function dailyOrders(Request $request)
    {

        $status_type  = StatusType::where('name', 'order')->first();
        $status       = Status::where('status_type_id', $status_type->id)->where('name', $request->condition)->first();
        $user         = Auth::user();
        $role_of_user = "";
        $client_id    = null;
        $module_name  = $user->module_name;
        $user_role    = $user->roles()->first()->name;
        switch ($user_role) {

            case 'system_user':
                updateConnectionSchema($module_name);

                $client_id = $user->systemUsers()->first()->pivot->client_id;

                break;

        }
        updateConnectionSchema($module_name);

        $orders = Order::where('status_id', $status->id)
            ->leftJoin('clients', 'orders.client_id', 'clients.id')
            ->select('orders.*', DB::raw("COALESCE(clients.first_name || ' ' || clients.last_name, clients.first_name) AS client_name"))
            ->whereBetween(DB::raw('DATE(orders.date)'), [$request->date_1, $request->date_2])
            ->with('status');

        if ($request->search) {
            $search_value = $request->search;
            $orders       = $orders->whereRaw("LOWER(clients.first_name) || LOWER(clients.last_name) || LOWER(orders.order_number) || LOWER(orders.service_type) || LOWER(orders.date) || LOWER(orders.direction)  ILIKE '%{$search_value}%'");

        }

        if (! empty($client_id)) {
            $orders = $orders->where('orders.client_id', $client_id);
        }

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'order_number':
                    $orders = $orders->orderBy("order_number", $request->sort);
                    break;

                case 'client_name':
                    $orders = $orders->orderByRaw("clients.first_name || clients.last_name {$request->sort}");
                    break;

                case 'direction':
                    $orders = $orders->orderBy("direction", $request->sort);
                    break;

                case 'service_type':
                    $orders = $orders->orderBy("service_type", $request->sort);
                    break;

                case 'date':
                    $orders = $orders->orderBy("date", $request->sort);
                    break;
            }

        } else {

            $orders = $orders->orderBy("orders.created_at", "desc");

        }

        $orders = $orders->paginate($this->paginate_size);

        $orders = parsePaginator($orders);

        return response()->json($orders);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    private function validateOrderNumber($order_number)
    {
        $user = Auth::user();
        $q    = Order::where("order_number", $order_number);

        if (! empty($q->first())) {
            $o = $q->where("user_id", $user->id)->first();

            if (empty($o)) {
                return "NOT VALID";
            }
            return "VALID";

        }

        return "EMPTY";

    }

    private function getOrderNumber($order_number, $response)
    {
        switch ($response) {

            case 'EMPTY':
                return $order_number;
                break;

            case 'VALID':
                return $order_number;
                break;

            case 'NOT VALID':

                $order_number = DB::table('orders')->select('order_number')->orderByRaw('cast(order_number as int) DESC')->first();
                $order_number = intval($order_number->order_number);
                $order_number = str_pad($order_number + 1, 3, '0', STR_PAD_LEFT);
                return $order_number;
                break;

        }
    }

    public function validateSubscription()
    {
        $user        = Auth::user();
        $module_name = $user->module_name;
        $user_role   = $user->roles()->first()->name;

        switch ($user_role) {

            case 'operator':

                updateConnectionSchema($module_name);
                $user_id = $user->operators()->first()->id;

                $subscription_user = User::find($user_id);

                return $subscription_user->company->order_quantity;
                break;

            default:
                if ($user_role == 'fumigator' || $user_role == 'administrator' || $user_role == 'super_administrator') {
                    updateConnectionSchema($module_name);
                    return $user->company->order_quantity;

                }
                break;

        }

    }

    public function store(CreateOrderRequest $request)
    {

        if ($this->validateSubscription() <= 0) {
            $validator = \Validator::make([], []);
            $validator->errors()->add('Error', 'Ha alcanzado el limite de ordenes, renueve su plan');
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $order = DB::transaction(function () use ($request) {

            $status_type  = StatusType::where('name', 'order')->first();
            $status       = Status::where('status_type_id', $status_type->id)->where('name', $request->status)->first();
            $order_number = $request->order_number;

            $user        = Auth::user();
            $module_name = $user->module_name;
            updateConnectionSchema($module_name);

            if ($request->action == "CREATE") {
                $validation   = $this->validateOrderNumber($order_number);
                $order_number = $this->getOrderNumber($order_number, $validation);
            }

            $arrive_time = str_replace("Z", "", $request->arrive_time);
            $start_time  = str_replace("Z", "", $request->start_time);
            $end_time    = str_replace("Z", "", $request->end_time);
            $client_id   = null;
            $worker_id   = null;

            if (is_string($request->client_id)) {
                $client_id = $this->addClient($request->client_id);
            } else {
                $client_id = $request->client_id;
            }

            if (is_string($request->worker_id)) {
                $worker_id = $this->addWorker($request->worker_id);
            } else {
                $worker_id = $request->worker_id;
            }

            $order = Order::updateOrCreate(
                [
                    "order_number" => $order_number,
                ],
                [
                    "client_id"    => $client_id,
                    "worker_id"    => $worker_id,
                    "date"         => $request->date,
                    "direction"    => $request->direction,
                    "service_type" => $request->service_type,
                    "arrive_time"  => $arrive_time,
                    "start_time"   => $start_time,
                    "end_time"     => $end_time,
                    "coordinator"  => $request->coordinator,
                    "origin"       => $request->origin,
                    "status_id"    => $status->id,
                    "user_id"      => Auth::user()->id,
                ]);

            if ($request->service_type == "General") {
                $external_condition = ExternalCondition::updateOrCreate(
                    [
                        "order_id" => $order->id,
                    ],
                    [
                        "obsolete_machinery" => $request->external_conditions["obsolete_machinery"],
                        "sewer_system"       => $request->external_conditions["sewer_system"],
                        "debris"             => $request->external_conditions["debris"],
                        "containers"         => $request->external_conditions["containers"],
                        "spotlights"         => $request->external_conditions["spotlights"],
                        "green_areas"        => $request->external_conditions["green_areas"],
                        "waste"              => $request->external_conditions["waste"],
                        "nesting"            => $request->external_conditions["nesting"],
                    ]);

                $internal_condition = InternalCondition::updateOrCreate(
                    [
                        "order_id" => $order->id,
                    ],
                    [
                        "walls"            => $request->internal_conditions["walls"],
                        "floors"           => $request->internal_conditions["floors"],
                        "cleaning"         => $request->internal_conditions["cleaning"],
                        "windows"          => $request->internal_conditions["windows"],
                        "storage"          => $request->internal_conditions["storage"],
                        "space"            => $request->internal_conditions["space"],
                        "evidences"        => $request->internal_conditions["evidences"],
                        "roofs"            => $request->internal_conditions["roofs"],
                        "sealings"         => $request->internal_conditions["sealings"],
                        "closed_doors"     => $request->internal_conditions["closed_doors"],
                        "pests_facilities" => $request->internal_conditions["pests_facilities"],
                        "garbage_cans"     => $request->internal_conditions["garbage_cans"],
                        "equipment"        => $request->internal_conditions["equipment"],
                        "ventilation"      => $request->internal_conditions["ventilation"],
                        "clean_walls"      => $request->internal_conditions["clean_walls"],
                        "ducts"            => $request->internal_conditions["ducts"],
                    ]);

                $infestation_grade = InfestationGrade::updateOrCreate(
                    [
                        "order_id" => $order->id,
                    ],
                    [
                        "german_cockroaches"   => $request->pests["german_cockroaches"],
                        "flies"                => $request->pests["flies"],
                        "bees"                 => $request->pests["bees"],
                        "fleas"                => $request->pests["fleas"],
                        "moths"                => $request->pests["moths"],
                        "weevils"              => $request->pests["weevils"],
                        "american_cockroaches" => $request->pests["american_cockroaches"],
                        "ants"                 => $request->pests["ants"],
                        "termites"             => $request->pests["termites"],
                        "spiders"              => $request->pests["spiders"],
                        "rodents"              => $request->pests["rodents"],
                        "fire_ants"            => $request->pests["fire_ants"],
                        "stilt_walkers"        => $request->pests["stilt_walkers"],
                        "others"               => $request->pests["others"],
                    ]);
            }

            return $order;
        });

        return response()
            ->json([
                "success" => true,
                "data"    => [
                    "order" => $order,
                ],
                "message" => "Orden almacenada con exito",
            ]);
    }

    private function addClient($client_id)
    {
        $client_name = explode("-", $client_id);
        $client_name = explode(" ", $client_name[1]);
        $email_name  = str_replace(" ", "_", $client_name[0]);

        return $client = Client::create(
            [
                "first_name" => $client_name[0],
                "email"      => $email_name . "@mail.com",
                "date"       => Carbon::now(),
            ]
        )->id;

    }

    private function addWorker($worker_name)
    {
        $worker_name   = explode("-", $worker_name);
        $worker_name   = explode(" ", $worker_name[1]);
        $email_name    = str_replace(" ", "_", $worker_name[0]);
        return $worker = Worker::create(
            [
                "first_name" => $worker_name[0],
                "email"      => $email_name . "@mail.com",
                "date"       => Carbon::now(),
            ]
        )->id;

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user    = Auth::user();
        $clients = Client::all();
        $workers = Worker::all();
        $order   = $this->order->find($id);
        $order->load('externalCondition');
        $order->load('internalCondition');
        $order->load('infestationGrade');
        $last_order_number = $order->order_number;

        return response()->json(compact('clients', 'workers', 'order', 'last_order_number'), 200);

    }

    /**
     * Check if users has previous data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkOrder(Request $request)
    {
        $user    = Auth::user();
        $clients = Client::all();
        $workers = Worker::all();

        $status_type = StatusType::where('name', 'order')->first();
        $status      = Status::where('status_type_id', $status_type->id)->where('name', 'in process')->first();

        $order = $this->order->where(["status_id" => $status->id, "user_id" => $user->id])->latest()->first();

        if (! empty($order)) {
            $order->load('externalCondition');
            $order->load('internalCondition');
            $order->load('infestationGrade');
        }

        $last_order        = $this->order->latest()->first();
        $last_order_number = (! empty($last_order)) ? str_pad($last_order->order_number + 1, 3, '0', STR_PAD_LEFT) : str_pad(1, 3, '0', STR_PAD_LEFT);

        return response()->json(compact('clients', 'workers', 'order', 'last_order_number'), 200);
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
    public function update(UpdateProductRequest $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::destroy($id);
        return response()->json(['success' => true, 'message' => 'Exito']);

    }

    public function resend($id)
    {
        expiredAccountMessage();

        $user = Auth::user();

        $order        = Order::find($id);
        $company_name = $user->company->name;

        if (empty($user->last_email_sent)) {
            $user->last_email_sent = Carbon::now();
            $user->save();
            if (! str_contains($order->client->email, '@mail.com')) {
                Mail::to($order->client->email)->send(new OrderMail($order, $company_name));
            }
            //OPTIONAL EMAILS

            $user_role   = $user->roles()->first()->name;
            $module_name = $user->module_name;
            updateConnectionSchema($module_name);

        } else {
            $lastSent = $user->last_email_sent;

            if ($lastSent && Carbon::now()->diffInMinutes($lastSent) < 10) {

                $validator = \Validator::make([], []);
                $validator->errors()->add('Error', 'Espere 10 minutos antes de reenviar el correo');
                throw new \Illuminate\Validation\ValidationException($validator);
            }
            $user->last_email_sent = Carbon::now();
            $user->save();

            foreach ($order->client->emails as $e) {

                if (! str_contains($e->email, '@mail.com')) {

                    Mail::to($e->email)->send(new OrderMail($order, $company_name));
                }
            }
        }

        return response()
            ->json([
                "success" => true,
                "data"    => [],
                "message" => "Orden Reenviada con exito",
            ]);
    }

    public function finish(Request $request)
    {

        if ($this->validateSubscription() <= 0) {
            $validator = \Validator::make([], []);
            $validator->errors()->add('Error', 'Renueve su plan para finalizar la orden');
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $order        = Order::find($request->order_id);
        $user         = Auth::user();
        $company_name = $user->company->name;

        DB::transaction(function () use ($request, $order) {
            $status_type      = StatusType::where('name', 'order')->first();
            $status           = Status::where('status_type_id', $status_type->id)->where('name', 'completed')->first();
            $order->status_id = $status->id;
            $order->save();

        });

        if (! str_contains($order->client->email, '@mail.com') && ! str_contains($order->client->email, '@pestsy.com')) {
            Mail::to($order->client->email)->send(new OrderMail($order, $company_name));

        }

        //OPTIONAL EMAILS

        $user_role   = $user->roles()->first()->name;
        $module_name = $user->module_name;
        updateConnectionSchema($module_name);
        $id = $user->id;

        switch ($user_role) {

            case 'operator':

                $user_id = $user->operators()->first()->id;

                $subscription_user = User::find($user_id);

                $company                 = Company::find($subscription_user->company->id);
                $company->order_quantity = $company->order_quantity - 1;

                $company->save();

                updateConnectionSchema($module_name);
                break;

            case 'system_user':

                updateConnectionSchema($module_name);
                break;

            default:

                if ($user_role == 'fumigator' || $user_role == 'administrator' || $user_role == 'super_administrator') {
                    updateConnectionSchema("administration");

                    $company                 = Company::find($user->company->id);
                    $company->order_quantity = $company->order_quantity - 1;
                    $company->save();

                    updateConnectionSchema($module_name);
                }
                break;

        }

        foreach ($order->client->emails as $e) {

            if (! str_contains($e->email, '@mail.com') && ! str_contains($e->email, '@pestsy.com')) {
                Mail::to($e->email)->send(new OrderMail($order, $company_name));
            }
        }

        return response()
            ->json([
                "success" => true,
                "data"    => [],
                "message" => "Orden finalizada con exito",
            ]);

    }

    public function pending(Request $request)
    {

        DB::transaction(function () use ($request) {
            $order            = Order::find($request->order_id);
            $status_type      = StatusType::where('name', 'order')->first();
            $status           = Status::where('status_type_id', $status_type->id)->where('name', 'pending')->first();
            $order->status_id = $status->id;
            $order->save();

        });
        return response()
            ->json([
                "success" => true,
                "data"    => [],
                "message" => "Orden finalizada con exito",
            ]);

    }
}
