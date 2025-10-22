<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\DesinfectionMethod\CreateDesinfectionMethodRequest;
use App\Http\Requests\Administration\DesinfectionMethod\UpdateDesinfectionMethodRequest;
use App\Models\Module\DesinfectionMethod;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesinfectionMethodController extends Controller
{

    private $desinfectionMethod;
    private $paginate_size = 6;

    public function __construct(DesinfectionMethod $desinfectionMethod)
    {
        $this->desinfectionMethod = $desinfectionMethod;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $desinfectionMethods = $this->desinfectionMethod;
        $user                = Auth::user();

        if ($request->search) {
            $search_value        = $request->search;
            $desinfectionMethods = $desinfectionMethods->whereRaw("LOWER(desinfection_methods.name) ILIKE '%{$search_value}%'");

        }
        $desinfectionMethods = $desinfectionMethods->whereNull('company_id')
            ->orWhere('company_id', $user->company_id);

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'name':
                    $desinfectionMethods = $desinfectionMethods->orderBy("name", $request->sort);
                    break;
            }

        } else {
            $desinfectionMethods = $desinfectionMethods->orderBy("desinfection_methods.created_at", "desc");

        }

        $desinfectionMethods = $desinfectionMethods->paginate($this->paginate_size);
        $desinfectionMethods = parsePaginator($desinfectionMethods);

        return response()->json($desinfectionMethods);
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
    public function store(CreateDesinfectionMethodRequest $request)
    {
        DB::transaction(function () use ($request) {

            $desinfectionMethod = DesinfectionMethod::create($request->all());

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
        $desinfectionMethod = DesinfectionMethod::where('id', $id)->where('company_id', $user->company->id)->first();

        if (empty($desinfectionMethod)) {
            abort(401);

        }

        return response()->json(['success' => true, 'data' => $desinfectionMethod]);

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
    public function update(UpdateDesinfectionMethodRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->all();
            unset($data["_method"]);

            $desinfectionMethod = DesinfectionMethod::where('id', $id)->update($data);

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

        $desinfectionMethod = DesinfectionMethod::where('id', $id)->where('is_general', false);
        if ($user_role == "super_administrator") {
            $desinfectionMethod = $desinfectionMethod->orWhere('is_general', true);
        }
        $desinfectionMethod = $desinfectionMethod->delete();
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
