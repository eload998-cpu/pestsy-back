<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\InternalCondition\CreateInternalConditionRequest;
use App\Http\Requests\Administration\InternalCondition\UpdateInternalConditionRequest;
use App\Models\Module\InternalCondition;
use App\Models\Module\OrderInternalCondition;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternalConditionController extends Controller
{

    private $internalCondition;
    private $paginate_size = 6;

    public function __construct(InternalCondition $internalCondition)
    {
        $this->internalCondition = $internalCondition;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $internalCondition = $this->internalCondition->newQuery();
        $user              = Auth::user();

        $internalCondition = $internalCondition->where(function ($q) use ($user) {
            $q->whereNull('internal_conditions.company_id')
                ->orWhere('internal_conditions.company_id', $user->company_id);
        });

        if ($request->search) {
            $search_value      = $request->search;
            $internalCondition = $internalCondition->whereRaw("LOWER(internal_conditions.name) ILIKE '%{$search_value}%'");

        }

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'name':
                    $internalCondition = $internalCondition->orderBy("name", $request->sort);
                    break;
            }

        } else {
            $internalCondition = $internalCondition->orderBy("internal_conditions.created_at", "desc");

        }

        $internalCondition = $internalCondition->paginate($this->paginate_size);
        $internalCondition = parsePaginator($internalCondition);

        return response()->json($internalCondition);
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
    public function store(CreateInternalConditionRequest $request)
    {
        DB::transaction(function () use ($request) {

            $internalCondition = InternalCondition::create($request->all());

        });

        return response()->json(['success' => true, 'message' => 'Exito']);
    }

    public function Orderstore(Request $request)
    {
        $rows = $request->all();
        unset($rows["company_id"]);

        OrderInternalCondition::where('order_id', $rows[0]['order_id'])->delete();

        foreach ($rows as $row) {
            OrderInternalCondition::create($row);
        }
        return response()->json(['success' => true, 'message' => 'Exito']);
    }

    public function changeStatus(Request $request)
    {

        $internalCondition             = InternalCondition::find($request->id);
        $internalCondition->is_visible = ! $internalCondition->is_visible;
        $internalCondition->save();

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
        $internalCondition = InternalCondition::where('id', $id)->where('company_id', $user->company->id)->first();

        if (empty($internalCondition)) {
            abort(401);

        }

        return response()->json(['success' => true, 'data' => $internalCondition]);

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
    public function update(UpdateInternalConditionRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->all();
            unset($data["_method"]);

            $internalCondition = InternalCondition::where('id', $id)->update($data);

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

        $internalCondition = InternalCondition::where('id', $id)->where('is_general', false);
        if ($user_role == "super_administrator") {
            $internalCondition = $internalCondition->orWhere('is_general', true);
        }
        $internalCondition = $internalCondition->delete();
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
