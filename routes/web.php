<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
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
//Route::get('/', function () {
//
//    // middleware
//    // return view('login');
//});


Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/auth', [UserController::class, 'authenticate'])->name('auth.authenticate');

Route::get('/logout', [UserController::class, 'logout'])->name('auth.logout');

Route::get('/visits', function () {
    return view('visits');
})->middleware('auth');

// admin
Route::get('/managers', function () {
    return view('managers');
})->middleware(['auth', 'admin']);


//Route::resource('client', ClientController::class);
// Client Controller Routes
Route::prefix('/client')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('clients.index')->middleware('auth');
    Route::post('/create', [ClientController::class, 'store'])->middleware('auth');
    Route::get('/find-by-phone', [ClientController::class, 'findByPhoneNumber'])->name('client.findByPhone')->middleware('auth');
    Route::delete('/{client}', [ClientController::class, 'destroy'])->middleware('auth');
    Route::put('/{client}', [ClientController::class, 'update'])->middleware('auth');
});


