<?php

namespace App\Http\Controllers\API\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administration\{Country,City,State};

class CountryLocationController extends Controller
{

    private $paginate=5;
    
    public function countries(Request $request)
    {

        $countries=Country::where('name','ilike',"%{$request->term}%")->paginate($this->paginate);
        return response()->json($countries);
    }

    public function states($id,Request $request)
    {

        $state=State::where('country_id',$id)->where('name','ilike',"%{$request->term}%")->paginate($this->paginate);
        return response()->json($state);
    }

    
    public function cities($id,Request $request)
    {
        $city=City::where('state_id',$id)->where('name','ilike',"%{$request->term}%")->paginate($this->paginate);
        return response()->json($city);
    }

}
