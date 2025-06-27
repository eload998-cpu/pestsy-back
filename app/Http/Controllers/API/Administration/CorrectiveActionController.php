<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\CorrectiveAction\CreateCorrectiveActionRequest;
use App\Http\Requests\Administration\CorrectiveAction\UpdateCorrectiveActionRequest;
use App\Models\Module\CorrectiveAction;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CorrectiveActionController extends Controller
{

    private $correctiveAction;
    private $paginate_size = 6;

    public function __construct(CorrectiveAction $correctiveAction)
    {
        $this->correctiveAction = $correctiveAction;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $correctiveActions = $this->correctiveAction;
        $user                = Auth::user();

        if ($request->search) {
            $search_value        = $request->search;
            $correctiveActions = $correctiveActions->whereRaw("LOWER(corrective_actions.name) ILIKE '%{$search_value}%'");

        }
        $correctiveActions = $correctiveActions->whereNull('company_id')
            ->orWhere('company_id', $user->company_id);

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'name':
                    $correctiveActions = $correctiveActions->orderBy("name", $request->sort);
                    break;
            }

        } else {
            $correctiveActions = $correctiveActions->orderBy("corrective_actions.created_at", "desc");

        }

        $correctiveActions = $correctiveActions->paginate($this->paginate_size);
        $correctiveActions = parsePaginator($correctiveActions);

        return response()->json($correctiveActions);
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
    public function store(CreatecorrectiveActionRequest $request)
    {
        DB::transaction(function () use ($request) {

            $correctiveAction = correctiveAction::create($request->all());

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

        $user               = Auth::user();
        $correctiveAction = correctiveAction::where('id', $id)->where('company_id', $user->company->id)->first();

        if (empty($correctiveAction)) {
            abort(401);

        }

        return response()->json(['success' => true, 'data' => $correctiveAction]);

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
    public function update(UpdatecorrectiveActionRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->all();
            unset($data["_method"]);

            $correctiveAction = correctiveAction::where('id', $id)->update($data);

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

        $correctiveAction = correctiveAction::where('id', $id)->where('is_general', false);
        if ($user_role == "super_administrator") {
            $correctiveAction = $correctiveAction->orWhere('is_general', true);
        }
        $correctiveAction = $correctiveAction->delete();
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
