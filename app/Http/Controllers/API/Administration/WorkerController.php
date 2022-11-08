<?php

namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Administration\Worker\{CreateWorkerRequest,UpdateWorkerRequest};

use App\Models\Module\{Worker};
use DB;


class WorkerController extends Controller
{

    private $worker;

    public function __construct(Worker $worker)
    {
        $this->worker=$worker;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $workers = $this->worker;
        
        if( $request->search )
        {
            $search_value=$request->search;
            $workers=$workers->whereRaw("LOWER(CONCAT(workers.first_name,' ',workers.last_name)) ILIKE '%{$search_value}%'");

        }
    
        if( $request->sort )
        {
            switch ($request->sortBy) {
         
                case 'first_name':
                    $workers= $workers->orderBy("first_name",$request->sort);
                break;

                case 'last_name':
                    
                    $workers= $workers->orderBy("last_name",$request->sort);
    
                break;
                
                default:
                $workers= $workers->orderBy("workers.companies.{$request->sortBy}",$request->sort);

                break;
            }


        }else
        {
            $workers= $workers->orderBy("workers.created_at","desc");

        }
        
        $workers=$workers->paginate(15);

        return response()->json($workers);
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
    public function store(CreateWorkerRequest $request)
    {
        DB::transaction(function () use ($request) {

        $worker=Worker::create($request->all());

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
        $worker=Worker::find($id);

        return response()->json(['success'=>true,'data'=>$worker]);

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
    public function update(UpdateWorkerRequest $request, $id)
    {

        DB::transaction(function () use ($request,$id) {

        $data=$request->all();
        unset($data["_method"]); 


        $worker=Worker::where('id',$id)->update($data);

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
        $worker=Worker::destroy($id);
        return response()->json(['success'=>true,'message'=>'Exito']);

    }
}
