<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ManagerController;
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
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('clients.index');
    } else {
        return redirect()->route('login');
    }
});

Route::get('/login', function () {
    if (auth()->check()) {
        return redirect()->route('clients.index');
    } else {
        return view('login');
    }
})->name('login');

Route::post('/auth', [UserController::class, 'authenticate'])->name('auth.authenticate');

Route::get('/logout', [UserController::class, 'logout'])->name('auth.logout');

// Visits Route
Route::middleware('auth')->group(function () {
    Route::get('/visits', function () {
        return view('visits');
    });
});

// Managers Route
Route::prefix('/managers')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [ManagerController::class, 'index']);
    Route::post('/create', [ManagerController::class, 'store']);
});

// Client Routes
Route::prefix('/clients')->middleware('auth')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/create', [ClientController::class, 'store']);
    Route::get('/find-by-phone', [ClientController::class, 'findByPhoneNumber'])->name('client.findByPhone');
    Route::delete('/{client}', [ClientController::class, 'destroy']);
    Route::put('/{client}', [ClientController::class, 'update']);
});


