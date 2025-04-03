<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

// GOOGLE AUTHENTICATION
Route::get('login/auth/google', 'API\V1\AuthController@redirectToGoogle');
Route::get('auth/google/callback', 'API\V1\AuthController@handleGoogleCallback');

//FACEBOOK AUTHENTICATION
Route::get('login/auth/facebook', 'API\V1\AuthController@redirectToFacebook');
Route::get('auth/facebook/callback', 'API\V1\AuthController@handleFacebookCallback');



