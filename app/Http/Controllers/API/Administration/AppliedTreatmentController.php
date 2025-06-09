<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\AppliedTreatment\CreateAppliedTreatmentRequest;
use App\Http\Requests\Administration\AppliedTreatment\UpdateAppliedTreatmentRequest;
use App\Models\Module\AppliedTreatment;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppliedTreatmentController extends Controller
{

    private $appliedTreatment;
    private $paginate_size = 6;

    public function __construct(AppliedTreatment $appliedTreatment)
    {
        $this->appliedTreatment = $appliedTreatment;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $appliedTreatment = $this->appliedTreatment;
        $user             = Auth::user();

        if ($request->search) {
            $search_value     = $request->search;
            $appliedTreatment = $appliedTreatment->whereRaw("LOWER(applied_treatments.name) ILIKE '%{$search_value}%'");

        }
        $appliedTreatment = $appliedTreatment->whereNull('company_id')
            ->orWhere('company_id', $user->company_id);

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'name':
                    $appliedTreatment = $appliedTreatment->orderBy("name", $request->sort);
                    break;
            }

        } else {
            $appliedTreatment = $appliedTreatment->orderBy("applied_treatments.created_at", "desc");

        }

        $appliedTreatment = $appliedTreatment->paginate($this->paginate_size);
        $appliedTreatment = parsePaginator($appliedTreatment);

        return response()->json($appliedTreatment);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAppliedTreatmentRequest $request)
    {
        DB::transaction(function () use ($request) {

            $appliedTreatment = AppliedTreatment::create($request->all());

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

        $user             = Auth::user();
        $appliedTreatment = AppliedTreatment::where('id', $id)->where('company_id', $user->company->id)->first();

         if(empty($appliedTreatment))
        {
            abort(401);

        }

        return response()->json(['success' => true, 'data' => $appliedTreatment]);

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
    public function update(UpdateAppliedTreatmentRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->all();
            unset($data["_method"]);

            $appliedTreatment = AppliedTreatment::where('id', $id)->update($data);

        });

        return response()->json(['success' => true, 'message' => 'Exito']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user      = Auth::user();
        $user_role = $user->roles()->first()->name;

        $appliedTreatment = AppliedTreatment::where('id', $id)->where('is_general', false);
        if ($user_role == "super_administrator") {
            $appliedTreatment = $appliedTreatment->orWhere('is_general', true);
        }
        $appliedTreatment = $appliedTreatment->delete();
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
