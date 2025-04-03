<?php

namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Administration\Order\Lamp\{CreateLampRequest,UpdateLampRequest};

use App\Models\Module\{Lamp};
use DB;

class LampController extends Controller
{
    private $lamp;
    private $paginate_size=6;

    public function __construct(Lamp $lamp)
    {
        $this->lamp=$lamp;

    }

    //
    public function index(Request $request)
    {
        $lamps = $this->lamp
        ->select('lamps.*')
        ->leftJoin('orders','lamps.order_id','orders.id')
        ->where('orders.id',$request->order_id);
        
     
        if( $request->search )
        {
            $search_value=$request->search;
            $lamps=$lamps->whereRaw("LOWER(lamps.station_number) || LOWER(lamps.rubbery_iron_changed) || LOWER(lamps.lamp_cleaning) || LOWER(lamps.fluorescent_change) ILIKE '%{$search_value}%'");

        }
    
        if( $request->sort )
        {
            switch ($request->sortBy) {

                case 'station_number':
                    $lamps= $lamps->orderBy("lamps.station_number",$request->sort);
                break;
         
                case 'fluorescent_change':
                    $lamps= $lamps->orderBy("lamps.fluorescent_change",$request->sort);
                break;
            }


        }else
        {
            $lamps= $lamps->orderBy("lamps.created_at","desc");

        }
        
        $lamps=$lamps->paginate($this->paginate_size);
        $lamps=parsePaginator($lamps);

        return response()->json($lamps);
    }


    public function store(CreateLampRequest $request)
    {
        $data=DB::transaction(function () use ($request) {

            $data=$request->all();
            unset($data["_method"]); 
    
    
            $lamp=Lamp::create($data);
        });

        return response()->json(
            ["success"=>true,
             "data"=>[],
             "message"=>"Exito!"
            ]
        );
        

    }


    public function update(UpdateLampRequest $request, $id)
    {

        DB::transaction(function () use ($request,$id) {

        $data=$request->all();
        unset($data["_method"]); 


        $lamp=Lamp::where('id',$id)->update($data);

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
        $model=Lamp::find($id);
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
        $lamp=Lamp::destroy($id);
        return response()->json(['success'=>true,'message'=>'Exito']);

    }
}
