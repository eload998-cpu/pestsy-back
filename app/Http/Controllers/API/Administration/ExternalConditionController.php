<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\ExternalCondition\CreateExternalConditionRequest;
use App\Http\Requests\Administration\ExternalCondition\UpdateExternalConditionRequest;
use App\Models\Module\ExternalCondition;
use App\Models\Module\OrderExternalCondition;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExternalConditionController extends Controller
{

    private $externalCondition;
    private $paginate_size = 6;

    public function __construct(ExternalCondition $externalCondition)
    {
        $this->externalCondition = $externalCondition;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $externalCondition = $this->externalCondition->newQuery();
        $user              = Auth::user();

        $externalCondition = $externalCondition->where(function ($q) use ($user) {
            $q->whereNull('external_conditions.company_id')
                ->orWhere('external_conditions.company_id', $user->company_id);
        });

        if ($request->search) {
            $search_value      = $request->search;
            $externalCondition = $externalCondition->whereRaw("LOWER(external_conditions.name) ILIKE '%{$search_value}%'");

        }

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'name':
                    $externalCondition = $externalCondition->orderBy("name", $request->sort);
                    break;
            }

        } else {
            $externalCondition = $externalCondition->orderBy("external_conditions.created_at", "desc");

        }

        $externalCondition = $externalCondition->paginate($this->paginate_size);
        $externalCondition = parsePaginator($externalCondition);

        return response()->json($externalCondition);
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
    public function store(CreateExternalConditionRequest $request)
    {
        $externalCondition = ExternalCondition::create($request->all());

        return response()->json(['success' => true, 'message' => 'Exito']);
    }

    public function Orderstore(Request $request)
    {
        $rows = $request->all();
        unset($rows["company_id"]);

        OrderExternalCondition::where('order_id', $rows[0]['order_id'])->delete();

        foreach ($rows as $row) {
            OrderExternalCondition::create($row);
        }
        return response()->json(['success' => true, 'message' => 'Exito']);
    }

    public function changeStatus(Request $request)
    {

        $externalCondition             = ExternalCondition::find($request->id);
        $externalCondition->is_visible = ! $externalCondition->is_visible;
        $externalCondition->save();

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

        $user              = Auth::user();
        $externalCondition = ExternalCondition::where('id', $id)->where('company_id', $user->company->id)->first();

        if (empty($externalCondition)) {
            abort(401);

        }

        return response()->json(['success' => true, 'data' => $externalCondition]);

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
    public function update(UpdateExternalConditionRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->all();
            unset($data["_method"]);

            $externalCondition = ExternalCondition::where('id', $id)->update($data);

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

        $externalCondition = ExternalCondition::where('id', $id)->where('is_general', false);
        if ($user_role == "super_administrator") {
            $externalCondition = $externalCondition->orWhere('is_general', true);
        }
        $externalCondition = $externalCondition->delete();
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
