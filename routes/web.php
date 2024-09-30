<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('customers');
});


Route::apiResource('/customers', CustomerController::class);
Route::post('/customers/update/{id}', [CustomerController::class, 'update'])->name('customers.update');
