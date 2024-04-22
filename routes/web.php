<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// add controllers
// admin manager
//Route::get('/', [ClientController::class, 'index'])->name('clients.index');

Route::get('/visits', function () {
    return view('visits');
});

// admin
Route::get('/managers', function () {
    return view('managers');
});


//Route::resource('client', ClientController::class);
// Client Controller Routes
Route::prefix('/client')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/create', [ClientController::class, 'create']);
    Route::delete('/{client}', [ClientController::class, 'destroy']);
    Route::put('/{client}', [ClientController::class, 'update']);
});


