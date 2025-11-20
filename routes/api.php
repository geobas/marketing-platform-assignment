<?php

use App\Http\Controllers\ListLeadsController;
use App\Http\Controllers\StoreLeadController;
use App\Http\Controllers\UpdateLeadController;
use Illuminate\Support\Facades\Route;

Route::get('/leads', ListLeadsController::class);
Route::post('/leads', StoreLeadController::class);
Route::put('/leads/{lead}', UpdateLeadController::class);
