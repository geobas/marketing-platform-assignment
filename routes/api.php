<?php

use App\Http\Controllers\Api\ListLeadsController;
use App\Http\Controllers\Api\StoreLeadController;
use App\Http\Controllers\Api\UpdateLeadController;
use Illuminate\Support\Facades\Route;

Route::get('/leads', ListLeadsController::class);
Route::post('/leads', StoreLeadController::class);
Route::put('/leads/{lead}', UpdateLeadController::class);
