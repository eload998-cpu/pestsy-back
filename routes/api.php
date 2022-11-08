<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group([
    'middleware'=>['auth:api','public-schema']
], function(){

    Route::post('register','API\V1\AuthController@register')->withoutMiddleware(['auth:api']);
    Route::post('login','API\V1\AuthController@login')->withoutMiddleware(['auth:api']);
    Route::get('auth-user','API\V1\AuthController@authUser');
    
    Route::post('administracion/modificar-perfil','API\V1\AuthController@updateProfile');

});



Route::group([
    'middleware'=>['auth:api','module-schema'],
    'namespace'     => 'API\Administration',
    'prefix' => 'administracion'
], function(){




    Route::resource('/clientes','ClientController');
    Route::resource('/obreros','WorkerController');
    Route::resource('/dispositivos','DeviceController');
    Route::resource('/ubicaciones','LocationController');

});