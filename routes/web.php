<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('app/courier-cost', [CouriourCostCalculaterHelper::class, 'courierCostCalculater']);
