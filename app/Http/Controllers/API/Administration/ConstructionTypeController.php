<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\ConstructionType\CreateConstructionTypeRequest;
use App\Http\Requests\Administration\ConstructionType\UpdateConstructionTypeRequest;
use App\Models\Module\ConstructionType;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConstructionTypeController extends Controller
{

    private $constructionType;
    private $paginate_size = 6;

    public function __construct(ConstructionType $constructionType)
    {
        $this->constructionType = $constructionType;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $constructionType = $this->constructionType;
        $user             = Auth::user();

        if ($request->search) {
            $search_value     = $request->search;
            $constructionType = $constructionType->whereRaw("LOWER(construction_types.name) ILIKE '%{$search_value}%'");

        }
        $constructionType = $constructionType->whereNull('company_id')
            ->orWhere('company_id', $user->company_id);

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'name':
                    $constructionType = $constructionType->orderBy("name", $request->sort);
                    break;
            }

        } else {
            $constructionType = $constructionType->orderBy("construction_types.created_at", "desc");

        }

        $constructionType = $constructionType->paginate($this->paginate_size);
        $constructionType = parsePaginator($constructionType);

        return response()->json($constructionType);
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
    public function store(CreateConstructionTypeRequest $request)
    {
        DB::transaction(function () use ($request) {

            $constructionType = ConstructionType::create($request->all());

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
        $constructionType = ConstructionType::where('id', $id)->where('company_id', $user->company->id)->first();

        if (empty($constructionType)) {
            abort(401);

        }
        return response()->json(['success' => true, 'data' => $constructionType]);

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
    public function update(UpdateConstructionTypeRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->all();
            unset($data["_method"]);

            $constructionType = ConstructionType::where('id', $id)->update($data);

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

        $constructionType = ConstructionType::where('id', $id)->where('is_general', false);
        if ($user_role == "super_administrator") {
            $constructionType = $constructionType->orWhere('is_general', true);
        }
        $constructionType = $constructionType->delete();
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
