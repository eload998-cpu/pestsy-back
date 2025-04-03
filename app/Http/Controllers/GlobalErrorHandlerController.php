<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GlobalError;
use Illuminate\Http\Request;

class GlobalErrorHandlerController extends Controller
{

    public function __construct()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        GlobalError::create([
            "error" => json_encode($request->all()),
        ]);

        return response()->json(true);
    }

}
