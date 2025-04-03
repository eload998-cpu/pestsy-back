<?php

namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Order\RodentControl\CreateRodentControlRequest;
use App\Http\Requests\Administration\Order\RodentControl\UpdateRodentControlRequest;
use App\Models\Module\Device;
use App\Models\Module\Location;
use App\Models\Module\PestBitacore;
use App\Models\Module\Product;
use App\Models\Module\RodentControl;use DB;
use Illuminate\Http\Request;

class RodentControlController extends Controller
{
    private $rodent_control;
    private $paginate_size = 6;

    public function __construct(RodentControl $rodent_control)
    {
        $this->rodent_control = $rodent_control;

    }

    //
    public function index(Request $request)
    {

        $rodent_controls = $this->rodent_control
            ->leftJoin('orders', 'control_of_rodents.order_id', 'orders.id')
            ->leftJoin('devices', 'control_of_rodents.device_id', 'devices.id')
            ->leftJoin('products', 'control_of_rodents.product_id', 'products.id')
            ->leftJoin('locations', 'control_of_rodents.location_id', 'locations.id')
            ->select('control_of_rodents.*', 'devices.name as device_name', 'products.name as product_name', 'locations.name as location_name')
            ->where('orders.id', $request->order_id);

        if ($request->search) {
            $search_value = $request->search;
            $rodent_controls = $rodent_controls->whereRaw("LOWER(control_of_rodents.device_number) || LOWER(devices.name) || LOWER(control_of_rodents.location) || LOWER(control_of_rodents.cleaning) || LOWER(control_of_rodents.bait_status) || LOWER(control_of_rodents.dose) ILIKE '%{$search_value}%'");

        }

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'device_number':
                    $rodent_controls = $rodent_controls->orderBy("control_of_rodents.device_number", $request->sort);
                    break;

                case 'device_name':
                    $sortOrder = ($request->sort=="ASC")?"DESC":"ASC";

                    $rodent_controls = $rodent_controls->orderBy("devices.name", $sortOrder);
                    break;

                case 'location':
                    $rodent_controls = $rodent_controls->orderBy("locations.name", $request->sort);
                    break;

                case 'cleaning':
                    $rodent_controls = $rodent_controls->orderBy("control_of_rodents.cleaning", $request->sort);
                    break;

                case 'bait_status':
                    $rodent_controls = $rodent_controls->orderBy("control_of_rodents.bait_status", $request->sort);
                    break;

                case 'dose':
                    $rodent_controls = $rodent_controls->orderBy("control_of_rodents.dose", $request->sort);
                    break;
            }

        } else {
            $rodent_controls = $rodent_controls->orderBy("control_of_rodents.created_at", "desc");

        }

        $rodent_controls = $rodent_controls->paginate($this->paginate_size);
        $rodent_controls = parsePaginator($rodent_controls);

        return response()->json($rodent_controls);
    }

    public function store(CreateRodentControlRequest $request)
    {

        $data = DB::transaction(function () use ($request) {

            $product_id = null;
            $device_id = null;
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

            if (is_string($request->location_id)) {
                $location_id = $this->addLocation($request->location_id);
            } else {
                $location_id = $request->location_id;
            }

            $rodent_control = RodentControl::create(
                [
                    "device_id" => $device_id,
                    "product_id" => $product_id,
                    "order_id" => $request->order_id,
                    "device_number" => $request->device_number,
                    "location_id" => $location_id,
                    "aceptable_cleaning" => $request->aceptable_cleaning,
                    "finished_cleaning" => $request->finished_cleaning,
                    "bait_status" => $request->bait_status,
                    "dose" => $request->dose,
                    "activity" => $request->activity,
                    "cleaning" => $request->cleaning,
                    "bait_change" => $request->bait_change,
                    "observation" => $request->observation,
                ]
            );

            foreach ($request->bitacores as $b) {
                PestBitacore::create(
                    [
                        "pest_id" => $b["pest_id"],
                        "quantity" => $b["quantity"],
                        "control_of_rodent_id" => $rodent_control->id,
                    ]);
            }
        });

        return response()->json(
            ["success" => true,
                "data" => [],
                "message" => "Exito!",
            ]
        );

    }



    public function update(UpdateRodentControlRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {


            $product_id = null;
            $device_id = null;
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

            if (is_string($request->location_id)) {
                $location_id = $this->addLocation($request->location_id);
            } else {
                $location_id = $request->location_id;
            }



            $rodent_control = RodentControl::where('id', $id)->update(
                [
                    "device_id" => $device_id,
                    "product_id" => $product_id,
                    "order_id" => $request->order_id,
                    "device_number" => $request->device_number,
                    "location_id" => $location_id,
                    "aceptable_cleaning" => $request->aceptable_cleaning,
                    "finished_cleaning" => $request->finished_cleaning,
                    "bait_status" => $request->bait_status,
                    "dose" => $request->dose,
                    "activity" => $request->activity,
                    "cleaning" => $request->cleaning,
                    "bait_change" => $request->bait_change,
                    "observation" => $request->observation,
                ]
            );

            PestBitacore::where('control_of_rodent_id', $id)->delete();
            foreach ($request->bitacores as $b) {
                PestBitacore::create(
                    [
                        "pest_id" => $b["pest_id"],
                        "quantity" => $b["quantity"],
                        "control_of_rodent_id" => $id,
                    ]);
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
        $model = RodentControl::find($id);
        $model->load('pestBitacores');
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
        $rodent_control = RodentControl::destroy($id);
        return response()->json(['success' => true, 'message' => 'Exito']);

    }


    private function addProduct($id)
    {
        $name = explode("-", $id);
        $name = $name[1];

        return $data = Product::create(
            [
                "name" => $name,
            ]
        )->id;

    }

    private function addDevice($id)
    {
        $name = explode("-", $id);
        $name = $name[1];

        return $data = Device::create(
            [
                "name" => $name,
            ]
        )->id;

    }

    private function addLocation($id)
    {
        $name = explode("-", $id);
        $name = $name[1];

        return $data = Location::create(
            [
                "name" => $name,
            ]
        )->id;

    }
}
