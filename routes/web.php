<?php

use App\Http\Controllers\CouriourCostController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controllers\Middleware;

Route::get('/', function () {
    return view('welcome');
});

Route::post('app/courier-cost', [CouriourCostController::class, 'courierCostCalculater']);
