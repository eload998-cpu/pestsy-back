<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Pest\CreatePestRequest;
use App\Http\Requests\Administration\Pest\UpdatePestRequest;
use App\Models\Module\Pest;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PestController extends Controller
{

    private $pest;
    private $paginate_size = 6;

    public function __construct(Pest $pest)
    {
        $this->pest = $pest;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pests = $this->pest;
        $user  = Auth::user();

        if ($request->search) {
            $search_value = $request->search;
            $pests        = $pests->whereRaw("LOWER(pests.common_name) || LOWER(pests.scientific_name)  ILIKE '%{$search_value}%'");

        }
        $pests = $pests->whereNull('company_id')
            ->orWhere('company_id', $user->company_id);

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'common_name':
                    $pests = $pests->orderBy("common_name", $request->sort);
                    break;

                case 'scientific_name':
                    $pests = $pests->orderBy("scientific_name", $request->sort);
                    break;
            }

        } else {
            $pests = $pests->orderBy("pests.created_at", "desc");

        }

        $pests = $pests->paginate($this->paginate_size);
        $pests = parsePaginator($pests);

        return response()->json($pests);
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
    public function store(CreatePestRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->all();
            unset($data["_method"]);

            \Log::info($data);
            $pest = Pest::create($data);

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
        $pest = Pest::find($id);

        return response()->json(['success' => true, 'data' => $pest]);

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
    public function update(UpdatePestRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->all();
            unset($data["_method"]);

            $pest = Pest::where('id', $id)->update($data);

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
        $pest = Pest::destroy($id);
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
