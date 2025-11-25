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
    'prefix'=>'resources',
    'namespace'=>'API\Resources'
], function(){

    Route::get('countries','CountryLocationController@countries');    
    Route::get('country/{id}/states','CountryLocationController@states');    
    Route::get('state/{id}/cities','CountryLocationController@cities');    
    Route::post('/get-select-data','ResourceController@getSelectData');
    Route::post('/get-plans','ResourceController@getPlans');
    Route::post('/get-plan/{id}','ResourceController@getPlanDetail');
    Route::post('/get-dollar-price','ResourceController@getDolarPrice');
    Route::post('/generate-station-number','ResourceController@generateStationNumber');

});


