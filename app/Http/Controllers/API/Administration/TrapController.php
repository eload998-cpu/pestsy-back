<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Order\Trap\CreateTrapRequest;
use App\Http\Requests\Administration\Order\Trap\UpdateTrapRequest;
use App\Models\Module\AplicationPlace;
use App\Models\Module\CorrectiveAction;
use App\Models\Module\Device;
use App\Models\Module\Location;
use App\Models\Module\Product;
use App\Models\Module\Trap;
use App\Models\Module\TrapCorrectiveAction;
use App\Models\Module\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TrapController extends Controller
{
    private $trap;
    private $paginate_size = 6;

    public function __construct(Trap $trap)
    {
        $this->trap = $trap;

    }

    //
    public function index(Request $request)
    {
        $traps = $this->trap
            ->leftJoin('orders', 'traps.order_id', 'orders.id')
            ->leftJoin('devices', 'traps.device_id', 'devices.id')
            ->select('traps.id', 'traps.station_number', 'traps.dose', 'traps.pheromones', 'devices.name as device_name')
            ->where('orders.id', $request->order_id);

        if ($request->search) {
            $search_value = $request->search;
            $traps        = $traps->whereRaw("LOWER(traps.station_number) || LOWER(traps.dose) || LOWER(traps.pheromones) || LOWER(devices.name) ILIKE '%{$search_value}%'");

        }

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'station_number':
                    $traps = $traps->orderBy("traps.station_number", $request->sort);
                    break;

                case 'dose':
                    $traps = $traps->orderBy("traps.dose", $request->sort);
                    break;

                case 'device_name':
                    $traps = $traps->orderBy("traps.device_name", $request->sort);
                    break;
            }

        } else {
            $traps = $traps->orderBy("traps.created_at", "desc");

        }

        $traps = $traps->paginate($this->paginate_size);
        $traps = parsePaginator($traps);

        return response()->json($traps);
    }

    public function store(CreateTrapRequest $request)
    {

        $product_id  = null;
        $device_id   = null;
        $worker_id   = null;
        $location_id = null;

        if (is_string($request->product_id)) {
            $product_id = $this->addProduct($request->product_id);
        } else {
            $product_id = $request->product_id;
        }

        if (is_string($request->device_id)) {
            $device_id = $this->addDevice($request->device_id);
        } else {
            $device_id = $request->device_id;
        }

        if (is_string($request->worker_id)) {
            $worker_id = $this->addWorker($request->worker_id);
        } else {
            $worker_id = $request->worker_id;
        }

        if (is_string($request->location_id)) {
            $location_id = $this->addLocation($request->location_id);
        } else {
            $location_id = $request->location_id;
        }

        $data                = $request->all();
        $data["device_id"]   = $device_id;
        $data["product_id"]  = $product_id;
        $data["worker_id"]   = $worker_id;
        $data["location_id"] = $location_id;
        unset($data["corrective_actions"]);
        unset($data["_method"]);
        $trap = Trap::create($data);

        foreach ($request->corrective_actions as $key => $value) {
            if (is_string($value)) {
                $correctiveActionId = $this->addCorrectiveAction($value);
            } else {
                $correctiveActionId = $value;
            }

            TrapCorrectiveAction::create([
                "trap_id"              => $trap->id,
                "corrective_action_id" => $correctiveActionId,
            ]);
        }

        return response()->json(
            ["success" => true,
                "data"     => [],
                "message"  => "Exito!",
            ]
        );

    }

    private function addApplicationPlace($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = AplicationPlace::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }

    private function addCorrectiveAction($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = CorrectiveAction::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }

    private function addWorker($worker_name)
    {

        $user = Auth::user();

        $worker_name   = explode("-", $worker_name);
        $worker_name   = explode(" ", $worker_name[1]);
        $email_name    = str_replace(" ", "_", $worker_name[0]);
        return $worker = Worker::create(
            [
                "first_name" => $worker_name[0],
                "email"      => $email_name . Str::random(8) . "@mail.com",
                "date"       => Carbon::now(),
                "company_id" => $user->company_id,

            ]
        )->id;

    }

    public function update(UpdateTrapRequest $request, $id)
    {

        $product_id  = null;
        $device_id   = null;
        $location_id = null;
        $worker_id   = null;

        if (is_string($request->product_id)) {
            $product_id = $this->addProduct($request->product_id);
        } else {
            $product_id = $request->product_id;
        }

        if (is_string($request->device_id)) {
            $device_id = $this->addDevice($request->device_id);
        } else {
            $device_id = $request->device_id;
        }

        if (is_string($request->worker_id)) {
            $worker_id = $this->addWorker($request->worker_id);
        } else {
            $worker_id = $request->worker_id;
        }

        if (is_string($request->location_id)) {
            $location_id = $this->addLocation($request->location_id);
        } else {
            $location_id = $request->location_id;
        }

        $data                = $request->all();
        $data["device_id"]   = $device_id;
        $data["product_id"]  = $product_id;
        $data["worker_id"]   = $worker_id;
        $data["location_id"] = $location_id;

        unset($data["_method"]);
        unset($data["company_id"]);
        unset($data["corrective_actions"]);

        $trap = Trap::where('id', $id)->update($data);

        TrapCorrectiveAction::where('trap_id', $id)->delete();
        foreach ($request->corrective_actions as $key => $value) {
            if (is_string($value)) {
                $correctiveActionId = $this->addCorrectiveAction($value);
            } else {
                $correctiveActionId = $value;
            }

            TrapCorrectiveAction::create([
                "trap_id"              => $id,
                "corrective_action_id" => $correctiveActionId,
            ]);
        }

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
        $model = Trap::find($id);
        $model->load('correctiveActions');
        return response()->json(['success' => true, 'data' => $model]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trap = Trap::destroy($id);
        return response()->json(['success' => true, 'message' => 'Exito']);

    }

    private function addProduct($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = Product::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }

    private function addLocation($id)
    {
        $name        = explode("-", $id);
        $name        = $name[1];
        $user        = Auth::user();
        return $data = Location::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,
            ]
        )->id;

    }

    private function addDevice($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = Device::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }
}
