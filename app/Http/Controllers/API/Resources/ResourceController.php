<?php
namespace App\Http\Controllers\API\Resources;

use App\Http\Controllers\Controller;
use App\Models\Administration\Plan;
use App\Models\Module\AffectedElement;
use App\Models\Module\Aplication;
use App\Models\Module\AplicationPlace;
use App\Models\Module\AppliedTreatment;
use App\Models\Module\ConstructionType;
use App\Models\Module\DesinfectionMethod;
use App\Models\Module\Device;
use App\Models\Module\Location;
use App\Models\Module\Pest;
use App\Models\Module\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ResourceController extends Controller
{
    //

    public function getSelectData(Request $request)
    {

        $user = Auth::user();

        $user_role = $user->roles()->first()->name;
        updateConnectionSchema("modules");

        $aplications          = [];
        $aplication_places    = [];
        $products             = [];
        $devices              = [];
        $locations            = [];
        $pests                = [];
        $applied_treatments   = [];
        $construction_types   = [];
        $affected_elements    = [];
        $desinfection_methods = [];

        if ($request->aplications) {
            $aplications = Aplication::all();

        }

        if ($request->aplication_places) {
            $aplication_places = AplicationPlace::all();

        }

        if ($request->products) {
            $products = Product::all();

        }

        if ($request->devices) {
            $devices = Device::all();

        }

        if ($request->locations) {
            $locations = Location::all();

        }

        if ($request->desinfection_methods) {
            $desinfection_methods = DesinfectionMethod::get();

        }

        if ($request->xylophages) {
            $pests = Pest::where('is_xylophagus', true)->get();

        }

        if ($request->pests) {
            $pests = Pest::all();

        }

        if ($request->applied_treatments) {
            $applied_treatments = AppliedTreatment::all();

        }

        if ($request->construction_types) {
            $construction_types = ConstructionType::all();

        }

        if ($request->affected_elements) {
            $affected_elements = AffectedElement::all();

        }

        return response()->json(compact('aplications', 'aplication_places', 'products', 'devices', 'locations', 'pests', 'applied_treatments', 'construction_types', 'affected_elements', 'desinfection_methods'), 200);

    }

    public function getPlans(Request $request)
    {

        $fumigator_plan = Plan::select('id', 'name', 'price', 'period')->where('name', 'Fumigador')->first();
        $premium_plan   = Plan::select('id', 'name', 'price', 'period')->where('name', 'Premium')->first();

        return compact('fumigator_plan', 'premium_plan');
    }

    public function getPlanDetail(Request $request)
    {
        $plan = Plan::find($request->id);

        return response()->json($plan);
    }

    public function getDolarPrice(Request $request)
    {
        try {
            $response         = Http::get("https://pydolarve.org/api/v1/dollar?page=criptodolar")->json();
            $response["rate"] = "BCV";
            return response()->json($response["monitors"]["bcv"]);
        } catch (\Exception $e) {

            \Log::info($e);
            return response()->json([]);

        }

    }
}
