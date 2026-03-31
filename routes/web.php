<?php

declare(strict_types=1);

use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
Route::put('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
