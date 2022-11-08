<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Administration\CreateUserRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;

use App\Classes\SchemaBuilder;

use App\Models\User;
use App\Models\Administration\Company;
use Illuminate\Support\Facades\Auth;
use DB;


class AuthController extends Controller
{
    private User $user;
    private Company $company;
    private SchemaBuilder $schema;

    public function __construct(User $user, Company $company,SchemaBuilder $schema)
    {
        $this->user=$user;
        $this->company=$company;
        $this->schema=$schema;
    }


    public function index()
    {
        return \App\Models\Module\Client::all();
    }

    public function login(LoginRequest $request)
    {

        $valid_credentials = Auth::guard('web')->attempt([
            "email"=>$request->email,
            "password"=>$request->password
            ]);

        if( ! $valid_credentials )
        {
            return response()->json(['error' => 'Credenciales invalidas'], 422);
        }

        $user = $this->user->firstWhere("email", "=", $request->email);
        $user->tokens->each(function($token) { $token->delete(); });
        unset($user->tokens);

        $token = $user->createToken('auth_token')->accessToken;



        return response()->json([
            'user' => $user,
            "authorization" => [
                'token' => $token,
                'type' => "Bearer"
            ]
        ]);



    }


    public function updateProfile(UpdateProfileRequest $request)
    {
        DB::transaction(function () use ($request) {
     
        $data= array_filter( $request->all());
        $user=$this->user->where('id',Auth::user()->id)->update($data);
       
      });

        return response()->json(['success'=>true,'data'=>"exito"]);


    }

    public function authUser(Request $request)
    {

        $user=Auth::User();

        return response()->json(['success'=>true,'data'=>$user]);
    }


    public function register(CreateUserRequest $request)
    {

        $user=$this->user->create([
            "first_name"=>$request->first_name,
            "last_name"=>$request->last_name,
            "cellphone"=>$request->cellphone,
            "email" =>$request->email,
            "password"=>$request->password
        ]);

        $company=$this->company->create([
            "name"=>$request->company_name,
            "user_id"=>$user->id
        ]);

        //create schemas

        $this->schema->createSchemas($company->id);

        return response()->json(["success"=>true],200);
    }
}
