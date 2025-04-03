<?php

namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Administration\Aplication\{CreateAplicationRequest,UpdateAplicationRequest};

use App\Models\Module\{Aplication};
use DB;


class AplicationController extends Controller
{

    private $aplication;
    private $paginate_size=6;

    public function __construct(Aplication $aplication)
    {
        $this->aplication=$aplication;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $aplications = $this->aplication;
        
        if( $request->search )
        {
            $search_value=$request->search;
            $aplications=$aplications->whereRaw("LOWER(aplications.name) ILIKE '%{$search_value}%'");

        }
    
        if( $request->sort )
        {
            switch ($request->sortBy) {
         
                case 'name':
                    $aplications= $aplications->orderBy("name",$request->sort);
                break;
            }


        }else
        {
            $aplications= $aplications->orderBy("aplications.created_at","desc");

        }
        
        $aplications=$aplications->paginate($this->paginate_size);
        $aplications=parsePaginator($aplications);

        return response()->json($aplications);
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
    public function store(CreateAplicationRequest $request)
    {
        DB::transaction(function () use ($request) {

        $aplication=aplication::create($request->all());

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
        $aplication=aplication::find($id);

        return response()->json(['success'=>true,'data'=>$aplication]);

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
    public function update(UpdateAplicationRequest $request, $id)
    {

        DB::transaction(function () use ($request,$id) {

        $data=$request->all();
        unset($data["_method"]); 


        $aplication=aplication::where('id',$id)->update($data);

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
        $aplication=aplication::destroy($id);
        return response()->json(['success'=>true,'message'=>'Exito']);

    }
}
