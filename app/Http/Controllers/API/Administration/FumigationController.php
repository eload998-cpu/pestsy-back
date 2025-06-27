<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Order\Fumigation\CreateFumigationRequest;
use App\Http\Requests\Administration\Order\Fumigation\UpdateFumigationRequest;
use App\Models\Module\Aplication;
use App\Models\Module\AplicationPlace;
use App\Models\Module\Fumigation;
use App\Models\Module\Product;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FumigationController extends Controller
{
    private $fumigation;
    private $paginate_size = 6;

    public function __construct(Fumigation $fumigation)
    {
        $this->fumigation = $fumigation;

    }

    //
    public function index(Request $request)
    {
        $fumigations = $this->fumigation
            ->select('fumigations.*')
            ->leftJoin('orders', 'fumigations.order_id', 'orders.id')
            ->where('orders.id', $request->order_id);

        $fumigations = $fumigations
            ->leftJoin('products', 'fumigations.product_id', 'products.id')
            ->leftJoin('aplication_places', 'fumigations.aplication_place_id', 'aplication_places.id')
            ->leftJoin('aplications', 'fumigations.aplication_id', 'aplications.id')
            ->select('fumigations.id', 'aplications.name as aplication_name', 'aplication_places.name as aplication_place_name', 'products.name as product_name', 'fumigations.dose as fumigation_dose');

        if ($request->search) {
            $search_value = $request->search;
            $fumigations  = $fumigations->whereRaw("LOWER(fumigations.dose) || LOWER(products.name) || LOWER(aplication_places.name) || LOWER(aplications.name) ILIKE '%{$search_value}%'");

        }

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'fumigations':

                    $sort        = ($request->sort == "ASC") ? "DESC" : "ASC";
                    $fumigations = $fumigations->orderBy("fumigations.dose", $sort);
                    break;

                case 'aplications':
                    $fumigations = $fumigations->orderBy("aplications.name", $request->sort);
                    break;

                case 'aplication_places':
                    $fumigations = $fumigations->orderBy("aplication_places.name", $request->sort);
                    break;

                case 'products':
                    $fumigations = $fumigations->orderBy("products.name", $request->sort);
                    break;
            }

        } else {
            $fumigations = $fumigations->orderBy("aplications.created_at", "desc");

        }

        $fumigations = $fumigations->paginate($this->paginate_size);
        $fumigations = parsePaginator($fumigations);

        return response()->json($fumigations);
    }

    public function store(CreateFumigationRequest $request)
    {
        $data = DB::transaction(function () use ($request) {

            $aplication_id       = null;
            $aplication_place_id = null;
            $product_id          = null;

            if (is_string($request->aplication_id)) {
                $aplication_id = $this->addApplication($request->aplication_id);
            } else {
                $aplication_id = $request->aplication_id;
            }

            if (is_string($request->aplication_place_id)) {
                $aplication_place_id = $this->addApplicationPlace($request->aplication_place_id);
            } else {
                $aplication_place_id = $request->aplication_place_id;
            }

            if (is_string($request->product_id)) {
                $product_id = $this->addProduct($request->product_id);
            } else {
                $product_id = $request->product_id;
            }

            Fumigation::create(
                [
                    "aplication_id"       => $aplication_id,
                    "aplication_place_id" => $aplication_place_id,
                    "product_id"          => $product_id,
                    "dose"                => $request->dose,
                    "order_id"            => $request->order_id,
                ]);
        });

        return response()->json(
            ["success" => true,
                "data"     => [],
                "message"  => "Exito!",
            ]
        );

    }

    public function update(UpdateFumigationRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->all();

            $aplication_id       = null;
            $aplication_place_id = null;
            $product_id          = null;

            if (is_string($request->aplication_id)) {
                $aplication_id = $this->addApplication($request->aplication_id);
            } else {
                $aplication_id = $request->aplication_id;
            }

            if (is_string($request->aplication_place_id)) {
                $aplication_place_id = $this->addApplicationPlace($request->aplication_place_id);
            } else {
                $aplication_place_id = $request->aplication_place_id;
            }

            if (is_string($request->product_id)) {
                $product_id = $this->addProduct($request->product_id);
            } else {
                $product_id = $request->product_id;
            }

            $data["aplication_id"]       = $aplication_id;
            $data["aplication_place_id"] = $aplication_place_id;
            $data["product_id"]          = $product_id;

            unset($data["_method"]);
            unset($data["company_id"]);

            $fumigation = Fumigation::where('id', $id)->update($data);

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

        $model = Fumigation::find($id);
        $model->load('aplication');
        $model->load('aplicationPlace');
        $model->load('product');

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
        $fumigation = Fumigation::destroy($id);
        return response()->json(['success' => true, 'message' => 'Exito']);

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
}
