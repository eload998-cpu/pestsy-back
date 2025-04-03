<?php

namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Administration\Contact\{CreateContactRequest};
use App\Models\Administration\{Contact};
use Illuminate\Support\Facades\Auth;

use App\Models\Status;
use App\Models\StatusType;

use DB;


class ContactController extends Controller
{

    private $contact;
    private $paginate_size=6;

    public function __construct(Contact $contact)
    {
        $this->contact=$contact;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
     
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
    public function store(CreateContactRequest $request)
    {
        DB::transaction(function () use ($request) {
        
        $status_type = StatusType::where('name', 'ticket')->first();
        $status = Status::where('status_type_id', $status_type->id)->where('name', 'pending')->first();
        
        $data= [
            "user_id"=>Auth::user()->id,
            "data"=>$request->data,
            "status_id"=>$status->id
        ];

        $contact=Contact::create($data);

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

 
  
}
