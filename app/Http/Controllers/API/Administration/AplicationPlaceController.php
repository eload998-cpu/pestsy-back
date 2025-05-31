<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\AplicationPlace\CreateAplicationPlaceRequest;
use App\Http\Requests\Administration\AplicationPlace\UpdateAplicationPlaceRequest;
use App\Models\Module\AplicationPlace;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AplicationPlaceController extends Controller
{

    private $aplicacion_place;
    private $paginate_size = 6;

    public function __construct(AplicationPlace $aplicacion_place)
    {
        $this->aplicacion_place = $aplicacion_place;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $aplicacion_places = $this->aplicacion_place;
        $user              = Auth::user();

        if ($request->search) {
            $search_value      = $request->search;
            $aplicacion_places = $aplicacion_places->whereRaw("LOWER(aplication_places.name) ILIKE '%{$search_value}%'");

        }
        $aplicacion_places = $aplicacion_places->whereNull('company_id')
            ->orWhere('company_id', $user->company_id);

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'name':
                    $aplicacion_places = $aplicacion_places->orderBy("name", $request->sort);
                    break;
            }

        } else {
            $aplicacion_places = $aplicacion_places->orderBy("aplication_places.created_at", "desc");

        }

        $aplicacion_places = $aplicacion_places->paginate($this->paginate_size);
        $aplicacion_places = parsePaginator($aplicacion_places);

        return response()->json($aplicacion_places);
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
    public function store(CreateAplicationPlaceRequest $request)
    {
        DB::transaction(function () use ($request) {

            $aplicacion_place = AplicationPlace::create($request->all());

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
        $aplicacion_place = AplicationPlace::find($id);

        return response()->json(['success' => true, 'data' => $aplicacion_place]);

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
    public function update(UpdateAplicationPlaceRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->all();
            unset($data["_method"]);

            $aplicacion_place = AplicationPlace::where('id', $id)->update($data);

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
        $aplicacion_place = AplicationPlace::destroy($id);
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
