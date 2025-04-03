<?php

namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Administration\Order\Observation\{CreateObservationRequest,UpdateObservationRequest};

use App\Models\Module\{Observation};
use DB;

class ObservationController extends Controller
{
    private $observation;
    private $paginate_size=6;

    public function __construct(Observation $observation)
    {
        $this->observation=$observation;

    }

    //
    public function index(Request $request)
    {
        $observations = $this->observation
        ->select('observations.*')
        ->leftJoin('orders','observations.order_id','orders.id')
        ->where('orders.id',$request->order_id);

        
     
        if( $request->search )
        {
            $search_value=$request->search;
            $observations=$observations->whereRaw("LOWER(observations.observation) ILIKE '%{$search_value}%'");

        }
        $observations= $observations->orderBy("observations.created_at","desc");

        
        $observations=$observations->paginate($this->paginate_size);
        $observations=parsePaginator($observations);

        return response()->json($observations);
    }


    public function store(CreateObservationRequest $request)
    {
        $data=DB::transaction(function () use ($request) {

            $data=$request->all();
            unset($data["_method"]); 
    
    
            $observation=Observation::create($data);
        });

        return response()->json(
            ["success"=>true,
             "data"=>[],
             "message"=>"Exito!"
            ]
        );
        

    }


    public function update(UpdateObservationRequest $request, $id)
    {

        DB::transaction(function () use ($request,$id) {

        $data=$request->all();
        unset($data["_method"]); 


        $observation=Observation::where('id',$id)->update($data);

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
        $model=Observation::find($id);
        return response()->json(['success'=>true,'data'=>$model]);

    }


       /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $observation=Observation::destroy($id);
        return response()->json(['success'=>true,'message'=>'Exito']);

    }
}
