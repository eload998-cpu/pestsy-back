<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Order\XylophageControl\CreateXylophageControlRequest;
use App\Http\Requests\Administration\Order\XylophageControl\UpdateXylophageControlRequest;
use App\Models\Module\AffectedElement;
use App\Models\Module\Aplication;
use App\Models\Module\ConstructionType;
use App\Models\Module\Location;
use App\Models\Module\Pest;
use App\Models\Module\Product;
use App\Models\Module\Worker;
use App\Models\Module\XylophageControl;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class XylophageControlController extends Controller
{
    private $xylophage_control;
    private $paginate_size = 6;

    public function __construct(XylophageControl $xylophage_control)
    {
        $this->xylophage_control = $xylophage_control;

    }

    //
    public function index(Request $request)
    {

        $xylophage_controls = $this->xylophage_control
            ->leftJoin('orders', 'control_of_xylophages.order_id', 'orders.id')
            ->leftJoin('pests', 'control_of_xylophages.pest_id', 'pests.id')
            ->select('control_of_xylophages.*', 'pests.common_name as pest_name')
            ->where('orders.id', $request->order_id);

        if ($request->search) {
            $search_value       = $request->search;
            $xylophage_controls = $xylophage_controls->whereRaw("LOWER(control_of_xylophages.infestation_level) || LOWER(pests.common_name) || LOWER(pests.scientific_name) ILIKE '%{$search_value}%'");

        }

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'infestation_level':
                    $sortOrder          = ($request->sort == "ASC") ? "DESC" : "ASC";
                    $xylophage_controls = $xylophage_controls->orderBy("infestation_level", $sortOrder);
                    break;

                case 'pest_name':
                    $sortOrder = ($request->sort == "ASC") ? "DESC" : "ASC";

                    $xylophage_controls = $xylophage_controls->orderBy("pests.common_name", $sortOrder);
                    break;

                case 'next_treatment_date':
                    $sortOrder = ($request->sort == "ASC") ? "DESC" : "ASC";

                    $xylophage_controls = $xylophage_controls->orderBy("next_treatment_date", $sortOrder);
                    break;
            }

        } else {
            $xylophage_controls = $xylophage_controls->orderBy("control_of_xylophages.created_at", "desc");

        }

        $xylophage_controls = $xylophage_controls->paginate($this->paginate_size);
        $xylophage_controls = parsePaginator($xylophage_controls);

        return response()->json($xylophage_controls);
    }

    public function store(CreateXylophageControlRequest $request)
    {

        $data = DB::transaction(function () use ($request) {

            $product_id           = null;
            $pest_id              = null;
            $aplication_id        = null;
            $construction_type_id = null;
            $affected_element_id  = null;
            $worker_id            = null;
            $location_id          = null;

            if (is_string($request->product_id)) {
                $product_id = $this->addProduct($request->product_id);
            } else {
                $product_id = $request->product_id;
            }

            if (is_string($request->pest_id)) {
                $pest_id = $this->addPest($request->pest_id);
            } else {
                $pest_id = $request->pest_id;
            }

            if (is_string($request->aplication_id)) {
                $aplication_id = $this->addApplication($request->aplication_id);
            } else {
                $aplication_id = $request->aplication_id;
            }

            if (is_string($request->construction_type_id)) {
                $construction_type_id = $this->addConstructionType($request->construction_type_id);
            } else {
                $construction_type_id = $request->construction_type_id;
            }

            if (is_string($request->affected_element_id)) {
                $affected_element_id = $this->addAffectedElement($request->affected_element_id);
            } else {
                $affected_element_id = $request->affected_element_id;
            }

            if (is_string($request->location_id)) {
                $location_id = $this->addLocation($request->location_id);
            } else {
                $location_id = $request->location_id;
            }

            if (is_string($request->worker_id)) {
                $worker_id = $this->addWorker($request->worker_id);
            } else {
                $worker_id = $request->worker_id;
            }

            $xylophage_control = XylophageControl::create(
                [
                    "pest_id"                 => $pest_id,
                    "product_id"              => $product_id,
                    "construction_type_id"    => $construction_type_id,
                    "affected_element_id"     => $affected_element_id,
                    "treatment_date"          => $request->treatment_date,
                    "next_treatment_date"     => $request->next_treatment_date,
                    "infestation_level"       => $request->infestation_level,
                    "observation"             => $request->observation,
                    "order_id"                => $request->order_id,

                    "aplication_id"           => $aplication_id,
                    "worker_id"               => $worker_id,
                    "location_id"             => $location_id,
                    "treated_area_value"      => $request->treated_area_value,
                    "treated_area_unit"       => $request->treated_area_unit,
                    "calculated_total_amount" => $request->calculated_total_amount,
                    "calculated_total_unit"   => $request->calculated_total_unit,
                    "pre_humidity"            => $request->pre_humidity,
                    "pre_ventilation"         => $request->pre_ventilation,
                    "pre_access"              => $request->pre_access,
                    "pre_notes"               => $request->pre_notes,
                    "post_humidity"           => $request->post_humidity,
                    "post_ventilation"        => $request->post_ventilation,
                    "post_access"             => $request->post_access,
                    "post_notes"              => $request->post_notes,
                    "dose"                    => $request->dose,

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

    public function update(UpdateXylophageControlRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $product_id           = null;
            $pest_id              = null;
            $aplication_id        = null;
            $construction_type_id = null;
            $affected_element_id  = null;

            if (is_string($request->product_id)) {
                $product_id = $this->addProduct($request->product_id);
            } else {
                $product_id = $request->product_id;
            }

            if (is_string($request->pest_id)) {
                $pest_id = $this->addPest($request->pest_id);
            } else {
                $pest_id = $request->pest_id;
            }

            if (is_string($request->aplication_id)) {
                $aplication_id = $this->addApplication($request->aplication_id);
            } else {
                $aplication_id = $request->aplication_id;
            }

            if (is_string($request->construction_type_id)) {
                $construction_type_id = $this->addConstructionType($request->construction_type_id);
            } else {
                $construction_type_id = $request->construction_type_id;
            }

            if (is_string($request->affected_element_id)) {
                $affected_element_id = $this->addAffectedElement($request->affected_element_id);
            } else {
                $affected_element_id = $request->affected_element_id;
            }

            if (is_string($request->location_id)) {
                $location_id = $this->addLocation($request->location_id);
            } else {
                $location_id = $request->location_id;
            }

            if (is_string($request->worker_id)) {
                $worker_id = $this->addWorker($request->worker_id);
            } else {
                $worker_id = $request->worker_id;
            }

            $xylophage_control = XylophageControl::where('id', $id)->update(
                [
                    "pest_id"                 => $pest_id,
                    "product_id"              => $product_id,
                    "construction_type_id"    => $construction_type_id,
                    "affected_element_id"     => $affected_element_id,
                    "treatment_date"          => $request->treatment_date,
                    "next_treatment_date"     => $request->next_treatment_date,
                    "infestation_level"       => $request->infestation_level,
                    "observation"             => $request->observation,
                    "order_id"                => $request->order_id,

                    "aplication_id"           => $aplication_id,
                    "worker_id"               => $worker_id,
                    "location_id"             => $location_id,
                    "treated_area_value"      => $request->treated_area_value,
                    "treated_area_unit"       => $request->treated_area_unit,
                    "calculated_total_amount" => $request->calculated_total_amount,
                    "calculated_total_unit"   => $request->calculated_total_unit,
                    "pre_humidity"            => $request->pre_humidity,
                    "pre_ventilation"         => $request->pre_ventilation,
                    "pre_access"              => $request->pre_access,
                    "pre_notes"               => $request->pre_notes,
                    "post_humidity"           => $request->post_humidity,
                    "post_ventilation"        => $request->post_ventilation,
                    "post_access"             => $request->post_access,
                    "post_notes"              => $request->post_notes,
                    "dose"                    => $request->dose,
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
        $model = XylophageControl::find($id);
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
        $xylophage_control = XylophageControl::destroy($id);
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

    private function addApplication($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = Aplication::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }

    private function addPest($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = Pest::create(
            [
                "common_name"     => $name,
                "scientific_name" => $name,
                "is_xylophagus"   => true,
                "company_id"      => $user->company_id,

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

    private function addConstructionType($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = ConstructionType::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }

    private function addAffectedElement($id)
    {
        $name = explode("-", $id);
        $name = $name[1];
        $user = Auth::user();

        return $data = AffectedElement::create(
            [
                "name"       => $name,
                "company_id" => $user->company_id,

            ]
        )->id;

    }
}
