<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Order\LegionellaControl\CreateLegionellaControlRequest;
use App\Http\Requests\Administration\Order\LegionellaControl\UpdateLegionellaControlRequest;
use App\Models\Module\LegionellaControl;
use App\Models\Module\LegionellaControlCorrectiveAction;
use App\Services\ApplicationService;
use App\Services\CorrectiveActionService;
use App\Services\LocationService;
use App\Services\ProductService;
use App\Services\WorkerService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class LegionellaControlController extends Controller
{
    private $legionella_control;
    private $paginate_size = 6;

    public function __construct(LegionellaControl $legionella_control)
    {
        $this->legionella_control = $legionella_control;

    }

    //
    public function index(Request $request)
    {

        $legionella_controls = $this->legionella_control
            ->leftJoin('orders', 'control_of_legionella.order_id', 'orders.id')
            ->leftJoin('locations', 'control_of_legionella.location_id', 'locations.id')
            ->leftJoin('aplications', 'control_of_legionella.aplication_id', 'aplications.id')
            ->select('control_of_legionella.*', 'locations.name as location_name', 'aplications.name as desinfection_method_name')
            ->where('orders.id', $request->order_id);

        if ($request->search) {
            $search_value        = $request->search;
            $legionella_controls = $legionella_controls->whereRaw("LOWER(control_of_legionella.last_treatment_date) || LOWER(control_of_legionella.next_treatment_date) || LOWER(locations.name) || LOWER(aplications.name) ILIKE '%{$search_value}%'");

        }

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'desinfection_method_name':
                    $sortOrder           = ($request->sort == "ASC") ? "DESC" : "ASC";
                    $legionella_controls = $legionella_controls->orderBy("aplications.name", $sortOrder);
                    break;

                case 'location_name':
                    $sortOrder = ($request->sort == "ASC") ? "DESC" : "ASC";

                    $legionella_controls = $legionella_controls->orderBy("locations.name", $sortOrder);
                    break;

                case 'last_treatment_date':
                    $sortOrder = ($request->sort == "ASC") ? "DESC" : "ASC";

                    $legionella_controls = $legionella_controls->orderBy("last_treatment_date", $sortOrder);
                    break;

                case 'next_treatment_date':
                    $sortOrder = ($request->sort == "ASC") ? "DESC" : "ASC";

                    $legionella_controls = $legionella_controls->orderBy("next_treatment_date", $sortOrder);
                    break;
            }

        } else {
            $legionella_controls = $legionella_controls->orderBy("control_of_legionella.created_at", "DESC");

        }

        $legionella_controls = $legionella_controls->paginate($this->paginate_size);
        $legionella_controls = parsePaginator($legionella_controls);

        return response()->json($legionella_controls);
    }

    public function store(CreateLegionellaControlRequest $request)
    {

        try {

            \Log::info("acaa");
            $location_id    = null;
            $application_id = null;
            $worker_id      = null;
            $product_id     = null;

            if (is_string($request->location_id)) {
                $location_id = LocationService::add($request->location_id);
            } else {
                $location_id = $request->location_id;
            }

            if (is_string($request->application_id)) {
                $application_id = ApplicationService::add($request->location_id);
            } else {
                $application_id = $request->application_id;
            }

            if (is_string($request->worker_id)) {
                $worker_id = WorkerService::add($request->worker_id);
            } else {
                $worker_id = $request->worker_id;
            }

            if ($request->code == "") {
                $code = $this->generarCodigoLegionela($request->order_id);
            } else {
                $code = $request->code;
            }

            if (is_string($request->product_id)) {
                $product_id = ProductService::add($request->product_id);
            } else {
                $product_id = $request->product_id;
            }

            $legionella_control = LegionellaControl::create(
                [
                    "location_id"             => $location_id,
                    "aplication_id"           => $application_id,
                    "inspection_result"       => $request->inspection_result,
                    "last_treatment_date"     => $request->last_treatment_date,
                    "next_treatment_date"     => $request->next_treatment_date,
                    "code"                    => $code,
                    "sample_required"         => $request->sample_required,
                    "water_temperature"       => $request->water_temperature,
                    "residual_chlorine_level" => $request->residual_chlorine_level,
                    "observation"             => $request->observation,
                    "order_id"                => $request->order_id,
                    "within_critical_limits"  => $request->within_critical_limits,
                    "worker_id"               => $worker_id,
                    "product_id"              => $product_id,

                ]
            );

            foreach ($request->correctiveActions as $key => $value) {
                if (is_string($value)) {
                    $correctiveActionId = CorrectiveActionService::add($value);
                } else {
                    $correctiveActionId = $value;
                }

                LegionellaControlCorrectiveAction::create([
                    "legionella_control_id" => $legionella_control->id,
                    "corrective_action_id"  => $correctiveActionId,
                ]);
            }

            return response()->json(
                ["success" => true,
                    "data"     => [],
                    "message"  => "Exito!",
                ]
            );
        } catch (\Exception $err) {
            \Log::info($err);
            throw $err;
        }

    }

    public function update(UpdateLegionellaControlRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $location_id    = null;
            $application_id = null;
            $worker_id      = null;
            $product_id     = null;

            if (is_string($request->location_id)) {
                $location_id = LocationService::add($request->location_id);
            } else {
                $location_id = $request->location_id;
            }

            if (is_string($request->product_id)) {
                $product_id = ProductService::add($request->product_id);
            } else {
                $product_id = $request->product_id;
            }

            if (is_string($request->application_id)) {
                $application_id = ApplicationService::add($request->location_id);
            } else {
                $application_id = $request->application_id;
            }

            if (is_string($request->worker_id)) {
                $worker_id = WorkerService::add($request->worker_id);
            } else {
                $worker_id = $request->worker_id;
            }

            if ($request->code == "") {
                $code = $this->generarCodigoLegionela($request->order_id);
            } else {
                $code = $request->code;
            }

            LegionellaControlCorrectiveAction::where('legionella_control_id', $id)->delete();
            foreach ($request->correctiveActions as $key => $value) {
                if (is_string($value)) {
                    $correctiveActionId = CorrectiveActionService::add($value);
                } else {
                    $correctiveActionId = $value;
                }

                LegionellaControlCorrectiveAction::create([
                    "legionella_control_id" => $id,
                    "corrective_action_id"  => $correctiveActionId,
                ]);
            }

            $legionella_control = LegionellaControl::where('id', $id)->update(
                [
                    "location_id"             => $location_id,
                    "aplication_id"           => $application_id,
                    "inspection_result"       => $request->inspection_result,
                    "last_treatment_date"     => $request->last_treatment_date,
                    "next_treatment_date"     => $request->next_treatment_date,
                    "code"                    => $code,
                    "sample_required"         => $request->sample_required,
                    "water_temperature"       => $request->water_temperature,
                    "residual_chlorine_level" => $request->residual_chlorine_level,
                    "observation"             => $request->observation,
                    "order_id"                => $request->order_id,
                    "within_critical_limits"  => $request->within_critical_limits,
                    "worker_id"               => $worker_id,
                    "product_id"              => $product_id,
                ]
            );

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
        $model = LegionellaControl::find($id);
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
        $legionella_control = LegionellaControl::destroy($id);
        LegionellaControlCorrectiveAction::where("legionella_control_id", $id)->delete();
        return response()->json(['success' => true, 'message' => 'Exito']);

    }

    public function generarCodigoLegionela($order_id)
    {
        $date   = Carbon::now()->format('Ymd');
        $prefix = 'LEG-' . $date;

        $count = LegionellaControl::where('code', 'like', $prefix . '%')
            ->where('order_id', $order_id)
            ->count();
        $number = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . '-' . $number;
    }

}
