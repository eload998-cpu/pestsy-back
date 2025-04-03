<?php

namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Administration\Location\{CreateLocationRequest,UpdateLocationRequest};

use App\Models\Module\{Location};
use DB;


class LocationController extends Controller
{

    private $location;
    private $paginate_size=6;

    public function __construct(Location $location)
    {
        $this->location=$location;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locations = $this->location;
        
        if( $request->search )
        {
            $search_value=$request->search;
            $locations=$locations->whereRaw("LOWER(locations.name) ILIKE '%{$search_value}%'");

        }
    
        if( $request->sort )
        {
            switch ($request->sortBy) {
         
                case 'name':
                    $locations= $locations->orderBy("name",$request->sort);
                break;
            }


        }else
        {
            $locations= $locations->orderBy("created_at","desc");

        }
        
        $locations=$locations->paginate($this->paginate_size);
        $locations=parsePaginator($locations);

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

        $location=Location::create($request->all());

        });

        return response()->json(['success'=>true,'message'=>'Exito']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $location=Location::find($id);

        return response()->json(['success'=>true,'data'=>$location]);

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

        DB::transaction(function () use ($request,$id) {

        $data=$request->all();
        unset($data["_method"]); 


        $location=Location::where('id',$id)->update($data);

        });

        return response()->json(['success'=>true,'message'=>'Exito']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $location=Location::destroy($id);
        return response()->json(['success'=>true,'message'=>'Exito']);

    }
}
