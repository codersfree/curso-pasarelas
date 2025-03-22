<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaidController;
use Illuminate\Support\Facades\Route;

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

});
