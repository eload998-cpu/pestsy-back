<?php

namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Administration\Client\{CreateClientRequest,UpdateClientRequest};

use App\Models\Module\{Client};
use DB;


class ClientController extends Controller
{

    private $client;

    public function __construct(Client $client)
    {
        $this->client=$client;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients = $this->client;
        
        if( $request->search )
        {
            $search_value=$request->search;
            $clients=$clients->whereRaw("LOWER(CONCAT(clients.first_name,' ',clients.last_name)) ILIKE '%{$search_value}%'");

        }
    
        if( $request->sort )
        {
            switch ($request->sortBy) {
         
                case 'first_name':
                    $clients= $clients->orderBy("first_name",$request->sort);
                break;

                case 'last_name':
                    
                    $clients= $clients->orderBy("last_name",$request->sort);
    
                break;
                
                default:
                $clients= $clients->orderBy("clients.companies.{$request->sortBy}",$request->sort);

                break;
            }


        }else
        {
            $clients= $clients->orderBy("clients.created_at","desc");

        }
        
        $clients=$clients->paginate(15);

        return response()->json($clients);
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
    public function store(CreateClientRequest $request)
    {
        DB::transaction(function () use ($request) {

        $client=Client::create($request->all());

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
        $client=Client::find($id);

        return response()->json(['success'=>true,'data'=>$client]);

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
    public function update(UpdateClientRequest $request, $id)
    {

        DB::transaction(function () use ($request,$id) {

        $data=$request->all();
        unset($data["_method"]); 


        $client=Client::where('id',$id)->update($data);

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
        $client=Client::destroy($id);
        return response()->json(['success'=>true,'message'=>'Exito']);

    }
}
