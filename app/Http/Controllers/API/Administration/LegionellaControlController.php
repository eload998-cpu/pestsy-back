<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Order\LegionellaControl\CreateLegionellaControlRequest;
use App\Http\Requests\Administration\Order\LegionellaControl\UpdateLegionellaControlRequest;
use App\Models\Module\DesinfectionMethod;
use App\Models\Module\InspectionType;
use App\Models\Module\LegionellaControl;
use App\Models\Module\Location;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->leftJoin('desinfection_methods', 'control_of_legionella.desinfection_method_id', 'desinfection_methods.id')
            ->select('control_of_legionella.*', 'locations.name as location_name', 'desinfection_methods.name as desinfection_method_name')
            ->where('orders.id', $request->order_id);

        if ($request->search) {
            $search_value        = $request->search;
            $legionella_controls = $legionella_controls->whereRaw("LOWER(control_of_legionella.last_treatment_date) || LOWER(control_of_legionella.next_treatment_date) || LOWER(locations.name) || LOWER(desinfection_methods.name) ILIKE '%{$search_value}%'");

        }

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'desinfection_method_name':
                    $sortOrder           = ($request->sort == "ASC") ? "DESC" : "ASC";
                    $legionella_controls = $legionella_controls->orderBy("desinfection_methods.name", $sortOrder);
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
            $legionella_controls = $legionella_controls->orderBy("control_of_legionella.created_at", "desc");

        }

        $legionella_controls = $legionella_controls->paginate($this->paginate_size);
        $legionella_controls = parsePaginator($legionella_controls);

        return response()->json($legionella_controls);
    }

    public function store(CreateLegionellaControlRequest $request)
    {

        $data = DB::transaction(function () use ($request) {

            $location_id            = null;
            $desinfection_method_id = null;

            if (is_string($request->location_id)) {
                $location_id = $this->addLocation($request->location_id);
            } else {
                $location_id = $request->location_id;
            }

            if (is_string($request->desinfection_method_id)) {
                $desinfection_method_id = $this->addDesinfectionMethod($request->desinfection_method_id);
            } else {
                $desinfection_method_id = $request->desinfection_method_id;
            }

            if ($request->code == "") {
                $code = $this->generarCodigoLegionela($request->order_id);
            } else {
                $code = $request->code;
            }

            $legionella_control = LegionellaControl::create(
                [
                    "location_id"             => $location_id,
                    "desinfection_method_id"  => $desinfection_method_id,
                    "inspection_result"       => $request->inspection_result,
                    "last_treatment_date"     => $request->last_treatment_date,
                    "next_treatment_date"     => $request->next_treatment_date,
                    "code"                    => $code,
                    "sample_required"         => $request->sample_required,
                    "water_temperature"       => $request->water_temperature,
                    "residual_chlorine_level" => $request->residual_chlorine_level,
                    "observation"             => $request->observation,
                    "order_id"                => $request->order_id,
                ]
            );

        });

        return response()->json(
            ["success" => true,
                "data"     => [],
                "message"  => "Exito!",
            ]
        );

    }

    public function update(UpdateLegionellaControlRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $location_id            = null;
            $desinfection_method_id = null;

            if (is_string($request->location_id)) {
                $location_id = $this->addLocation($request->location_id);
            } else {
                $location_id = $request->location_id;
            }

            if (is_string($request->desinfection_method_id)) {
                $desinfection_method_id = $this->addDesinfectionMethod($request->desinfection_method_id);
            } else {
                $desinfection_method_id = $request->desinfection_method_id;
            }

            if ($request->code == "") {
                $code = $this->generarCodigoLegionela($request->order_id);
            } else {
                $code = $request->code;
            }

            $legionella_control = LegionellaControl::where('id', $id)->update(
                [
                    "location_id"             => $location_id,
                    "desinfection_method_id"  => $desinfection_method_id,
                    "inspection_result"       => $request->inspection_result,
                    "last_treatment_date"     => $request->last_treatment_date,
                    "next_treatment_date"     => $request->next_treatment_date,
                    "code"                    => $code,
                    "sample_required"         => $request->sample_required,
                    "water_temperature"       => $request->water_temperature,
                    "residual_chlorine_level" => $request->residual_chlorine_level,
                    "observation"             => $request->observation,
                    "order_id"                => $request->order_id,
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
        return response()->json(['success' => true, 'message' => 'Exito']);

    }

    private function addLocation($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = Location::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }

    private function addDesinfectionMethod($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = DesinfectionMethod::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }

    private function addInspectionType($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = InspectionType::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

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
