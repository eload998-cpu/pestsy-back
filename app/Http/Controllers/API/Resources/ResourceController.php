<?php
namespace App\Http\Controllers\API\Resources;

use App\Http\Controllers\Controller;
use App\Models\Administration\Plan;
use App\Models\Module\AffectedElement;
use App\Models\Module\Aplication;
use App\Models\Module\AplicationPlace;
use App\Models\Module\AppliedTreatment;
use App\Models\Module\ConstructionType;
use App\Models\Module\CorrectiveAction;
use App\Models\Module\DesinfectionMethod;
use App\Models\Module\Device;
use App\Models\Module\Location;
use App\Models\Module\Pest;
use App\Models\Module\Product;
use App\Models\Module\Worker;
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
        $corrective_actions   = [];
        $pests                = [];
        $applied_treatments   = [];
        $construction_types   = [];
        $affected_elements    = [];
        $desinfection_methods = [];
        $workers              = [];

        if ($request->workers) {
            $workers = Worker::where(function ($query) use ($user) {
                $query->where('company_id', $user->company->id);
            })
                ->get();

        }

        if ($request->corrective_action) {
            $corrective_actions = CorrectiveAction::where(function ($query) use ($user) {
                $query->where('company_id', $user->company->id)
                    ->orWhere('is_general', true);
            })
                ->get();

        }

        if ($request->aplications) {
            $aplications = Aplication::where(function ($query) use ($user) {
                $query->where('company_id', $user->company->id)
                    ->orWhere('is_general', true);
            })
                ->get();
        }

        if ($request->aplication_places) {
            $aplication_places = AplicationPlace::where(function ($query) use ($user) {
                $query->where('company_id', $user->company->id)
                    ->orWhere('is_general', true);
            })
                ->get();

        }

        if ($request->products) {
            $products = Product::where(function ($query) use ($user) {
                $query->where('company_id', $user->company->id)
                    ->orWhere('is_general', true);
            })
                ->get();

        }

        if ($request->devices) {
            $devices = Device::where(function ($query) use ($user) {
                $query->where('company_id', $user->company->id)
                    ->orWhere('is_general', true);
            })
                ->get();

        }

        if ($request->locations) {
            $locations = Location::where(function ($query) use ($user) {
                $query->where('company_id', $user->company->id)
                    ->orWhere('is_general', true);
            })
                ->get();

        }

        if ($request->desinfection_methods) {
            $desinfection_methods = DesinfectionMethod::where(function ($query) use ($user) {
                $query->where('company_id', $user->company->id)
                    ->orWhere('is_general', true);
            })
                ->get();

        }

        if ($request->xylophages) {
            $pests = Pest::where('is_xylophagus', true)
                ->where(function ($q) use ($user) {
                    $q->where('company_id', $user->company->id)
                        ->orWhere('is_general', true);
                })
                ->get();
        }

        if ($request->pests) {
            $pests = Pest::where(function ($query) use ($user) {
                $query->where('company_id', $user->company->id)
                    ->orWhere('is_general', true);
            })
                ->get();

        }

        if ($request->applied_treatments) {
            $applied_treatments = AppliedTreatment::where(function ($query) use ($user) {
                $query->where('company_id', $user->company->id)
                    ->orWhere('is_general', true);
            })
                ->get();

        }

        if ($request->construction_types) {
            $construction_types = ConstructionType::where(function ($query) use ($user) {
                $query->where('company_id', $user->company->id)
                    ->orWhere('is_general', true);
            })
                ->get();

        }

        if ($request->affected_elements) {
            $affected_elements = AffectedElement::where(function ($query) use ($user) {
                $query->where('company_id', $user->company->id)
                    ->orWhere('is_general', true);
            })
                ->get();

        }

        return response()->json(compact('workers', 'corrective_actions', 'aplications', 'aplication_places', 'products', 'devices', 'locations', 'pests', 'applied_treatments', 'construction_types', 'affected_elements', 'desinfection_methods'), 200);

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
            $response         = Http::get("https://pydolarve.org/api/v1/dollar")->json();
            $response["rate"] = "BCV";
            return response()->json($response["monitors"]["bcv"]);
        } catch (\Exception $e) {

            \Log::info($e);
            return response()->json([]);

        }

    }
}
