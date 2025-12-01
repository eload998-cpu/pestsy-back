<?php

use Carbon\Carbon;

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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/failed-payment-preview', function () {
    $transactionId     = 'TXN123456789';
    $planName          = 'Plan Premium Mensual';
    $lastFailedPayment = Carbon::now()->format('d/m/Y');

    $data = [
        'shipping_amount' => [
            'value' => '9.99',
        ],
    ];

    return view('emails.subscriptions.failedPayment', compact('transactionId', 'planName', 'lastFailedPayment', 'data'));
});
// ...existing code...
