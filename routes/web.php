<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FlowController;
use App\Http\Controllers\NumberController;
use App\Http\Controllers\NumberPreferenceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');;

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return Inertia\Inertia::render('Dashboard');
// })->name('dashboard');

Route::resource('customer', CustomerController::class);
Route::resource('number', NumberController::class);
Route::resource('number_preference', NumberPreferenceController::class);

Route::get('flow/customer/create', [FlowController::class, 'createCustomer']);
