<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitController;
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
Route::prefix('/visits')->middleware('auth')->group(function () {
    Route::get('/', [VisitController::class, 'index']);
    Route::post('/create', [VisitController::class, 'store']);

    // Route::get('/find-by-phone', [ManagerController::class, 'findByPhoneNumber'])->name('manager.findByPhone');
    Route::put('/{visit}', [VisitController::class, 'update']);
    Route::delete('/{visit}', [VisitController::class, 'destroy']);
});

// Managers Route
Route::prefix('/managers')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [ManagerController::class, 'index']);
    Route::post('/create', [ManagerController::class, 'store']);
    Route::get('/find-by-phone', [ManagerController::class, 'findByPhoneNumber'])->name('manager.findByPhone');
    Route::put('/{manager}', [ManagerController::class, 'update']);
    Route::delete('/{manager}', [ManagerController::class, 'destroy']);
});

// Payments Route
Route::prefix('/payments')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [PaymentController::class, 'index']);
    Route::post('/create', [PaymentController::class, 'store']);
    Route::put('/{payment}', [PaymentController::class, 'update']);
    Route::delete('/{payment}', [PaymentController::class, 'destroy']);
});

// Client Routes
Route::prefix('/clients')->middleware('auth')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/create', [ClientController::class, 'store']);
    Route::get('/find-by-phone', [ClientController::class, 'findByPhoneNumber'])->name('client.findByPhone');
    Route::delete('/{client}', [ClientController::class, 'destroy']);
    Route::put('/{client}', [ClientController::class, 'update']);
});


