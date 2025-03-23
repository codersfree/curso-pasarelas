<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaidController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/paid/izipay', [PaidController::class, 'izipay'])->name('paid.izipay');
    Route::post('/paid/niubiz', [PaidController::class, 'niubiz'])->name('paid.niubiz');

    Route::post('/paid/createPaypalOrder', [PaidController::class, 'createPaypalOrder'])->name('paid.createPaypalOrder');
    Route::post('/paid/capturePaypalOrder', [PaidController::class, 'capturePaypalOrder'])->name('paid.capturePaypalOrder');

    Route::get('/gracias', function () {
        return view('gracias');
    })->name('gracias');

});

Route::post('/webhooks/mercadopago', [WebhookController::class, 'mercadopago'])->name('webhooks.mercadopago');

Route::get('prueba', function(){
    MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

    $client = new PaymentClient();
    $payment = json_encode($client->get('105659829585'));

    return json_decode($payment);
    
});