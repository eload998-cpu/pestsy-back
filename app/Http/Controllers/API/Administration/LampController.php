<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Order\Lamp\CreateLampRequest;
use App\Http\Requests\Administration\Order\Lamp\UpdateLampRequest;
use App\Models\Module\CorrectiveAction;
use App\Models\Module\Lamp;
use App\Models\Module\LampCorrectiveAction;
use App\Models\Module\Location;
use App\Models\Module\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LampController extends Controller
{
    private $lamp;
    private $paginate_size = 6;

    public function __construct(Lamp $lamp)
    {
        $this->lamp = $lamp;

    }

    //
    public function index(Request $request)
    {
        $lamps = $this->lamp
            ->select('lamps.*')
            ->leftJoin('orders', 'lamps.order_id', 'orders.id')
            ->where('orders.id', $request->order_id);

        if ($request->search) {
            $search_value = $request->search;
            $lamps        = $lamps->whereRaw("LOWER(lamps.station_number) || LOWER(lamps.rubbery_iron_changed) || LOWER(lamps.lamp_cleaning) || LOWER(lamps.fluorescent_change) ILIKE '%{$search_value}%'");

        }

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'station_number':
                    $lamps = $lamps->orderBy("lamps.station_number", $request->sort);
                    break;

                case 'fluorescent_change':
                    $lamps = $lamps->orderBy("lamps.fluorescent_change", $request->sort);
                    break;
            }

        } else {
            $lamps = $lamps->orderBy("lamps.created_at", "desc");

        }

        $lamps = $lamps->paginate($this->paginate_size);
        $lamps = parsePaginator($lamps);

        return response()->json($lamps);
    }

    public function store(CreateLampRequest $request)
    {
        $worker_id   = null;
        $location_id = null;

        $data = $request->all();

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

        unset($data["_method"]);
        unset($data["corrective_actions"]);

        $data["worker_id"]   = $worker_id;
        $data["location_id"] = $location_id;

        $lamp = Lamp::create($data);

        foreach ($request->corrective_actions as $key => $value) {
            if (is_string($value)) {
                $correctiveActionId = $this->addCorrectiveAction($value);
            } else {
                $correctiveActionId = $value;
            }

            LampCorrectiveAction::create([
                "lamp_id"              => $lamp->id,
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

    public function update(UpdateLampRequest $request, $id)
    {
        $worker_id   = null;
        $location_id = null;

        $data = $request->all();

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

        unset($data["_method"]);
        unset($data["company_id"]);
        unset($data["corrective_actions"]);

        $data["worker_id"]   = $worker_id;
        $data["location_id"] = $location_id;

        $lamp = Lamp::where('id', $id)->update($data);

        LampCorrectiveAction::where('lamp_id', $id)->delete();
        foreach ($request->corrective_actions as $key => $value) {
            if (is_string($value)) {
                $correctiveActionId = $this->addCorrectiveAction($value);
            } else {
                $correctiveActionId = $value;
            }

            LampCorrectiveAction::create([
                "lamp_id"              => $id,
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
        $model = Lamp::find($id);
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
        $lamp = Lamp::destroy($id);
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
