<?php

use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return http_response_code(403);
});


