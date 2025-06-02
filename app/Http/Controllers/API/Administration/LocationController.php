<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Location\CreateLocationRequest;
use App\Http\Requests\Administration\Location\UpdateLocationRequest;
use App\Models\Module\Location;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{

    private $location;
    private $paginate_size = 6;

    public function __construct(Location $location)
    {
        $this->location = $location;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locations = $this->location;
        $user      = Auth::user();

        if ($request->search) {
            $search_value = $request->search;
            $locations    = $locations->whereRaw("LOWER(locations.name) ILIKE '%{$search_value}%'");

        }
        $locations = $locations->whereNull('company_id')
            ->orWhere('company_id', $user->company_id);

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'name':
                    $locations = $locations->orderBy("name", $request->sort);
                    break;
            }

        } else {
            $locations = $locations->orderBy("created_at", "desc");

        }

        $locations = $locations->paginate($this->paginate_size);
        $locations = parsePaginator($locations);

        return response()->json($locations);
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
    public function store(CreateLocationRequest $request)
    {

        DB::transaction(function () use ($request) {

            $location = Location::create($request->all());

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
        $location = Location::find($id);

        return response()->json(['success' => true, 'data' => $location]);

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
    public function update(UpdateLocationRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->all();
            unset($data["_method"]);

            $location = Location::where('id', $id)->update($data);

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

        $location = Location::where('id', $id)->where('is_general', false);
        if ($user_role == "super_administrator") {
            $location = $location->orWhere('is_general', true);
        }
        $location = $location->delete();
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
