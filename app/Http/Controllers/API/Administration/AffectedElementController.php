<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\AffectedElement\CreateAffectedElementRequest;
use App\Http\Requests\Administration\AffectedElement\UpdateAffectedElementRequest;
use App\Models\Module\AffectedElement;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffectedElementController extends Controller
{

    private $affectedElement;
    private $paginate_size = 6;

    public function __construct(AffectedElement $affectedElement)
    {
        $this->affectedElement = $affectedElement;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $affectedElement = $this->affectedElement;
        $user            = Auth::user();

        if ($request->search) {
            $search_value    = $request->search;
            $affectedElement = $affectedElement->whereRaw("LOWER(affected_elements.name) ILIKE '%{$search_value}%'");

        }
        $affectedElement = $affectedElement->whereNull('company_id')
            ->orWhere('company_id', $user->company_id);

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'name':
                    $affectedElement = $affectedElement->orderBy("name", $request->sort);
                    break;
            }

        } else {
            $affectedElement = $affectedElement->orderBy("affected_elements.created_at", "desc");

        }

        $affectedElement = $affectedElement->paginate($this->paginate_size);
        $affectedElement = parsePaginator($affectedElement);

        return response()->json($affectedElement);
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
    public function store(CreateAffectedElementRequest $request)
    {
        DB::transaction(function () use ($request) {

            $affectedElement = AffectedElement::create($request->all());

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
        $affectedElement = AffectedElement::find($id);

        return response()->json(['success' => true, 'data' => $affectedElement]);

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
    public function update(UpdateAffectedElementRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->all();
            unset($data["_method"]);

            $affectedElement = AffectedElement::where('id', $id)->update($data);

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

        $affectedElement = AffectedElement::where('id', $id)->where('is_general', false);
        if ($user_role == "super_administrator") {
            $affectedElement = $affectedElement->orWhere('is_general', true);
        }
        $affectedElement = $affectedElement->delete();
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
