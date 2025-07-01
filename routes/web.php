<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PriceController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['api', 'auth.apikey'])
    ->prefix('api')
    ->group(function () {
        Route::get('/prices', [PriceController::class, 'index']);
        Route::get('/prices/{id}', [PriceController::class, 'show']);
    });
