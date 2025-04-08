<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourierCostController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

Route::post('courier-cost', [CourierCostController::class, 'courierCostCalculater'])->middleware('auth:sanctum');
