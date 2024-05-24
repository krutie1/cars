<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\TransactionController;
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

    Route::get('/filter', [VisitController::class, 'filter'])->name('visit.filter');
    Route::get('/find', [VisitController::class, 'find'])->name('visit.find');
    Route::put('/{visit}', [VisitController::class, 'update']);
    Route::put('/payment/{visit}', [VisitController::class, 'setPayment']);
    Route::delete('/{visit}', [VisitController::class, 'destroy']);
    Route::get('/{visit}/payment-data', [VisitController::class, 'getPaymentData']);

});

// Managers Route
Route::prefix('/managers')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [ManagerController::class, 'index']);
    Route::post('/create', [ManagerController::class, 'store']);
    Route::get('/find', [ManagerController::class, 'find'])->name('manager.find');
    Route::put('/{manager}', [ManagerController::class, 'update']);
    Route::delete('/{manager}', [ManagerController::class, 'destroy']);
});

// Payments Route
Route::prefix('/payments')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [PaymentController::class, 'index']);
    Route::post('/create', [PaymentController::class, 'store']);
    Route::put('/{payment}', [PaymentController::class, 'update']);
    Route::delete('/{payment}', [PaymentController::class, 'destroy']);
    Route::post('/{id}/restore', [PaymentController::class, 'restore'])->name('payments.restore');
});

// Client Routes
Route::prefix('/clients')->middleware('auth')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/create', [ClientController::class, 'store']);
    Route::get('/find', [ClientController::class, 'find'])->name('client.find');
    Route::get('/search-client', [ClientController::class, 'searchClient'])->name('client.searchClient');
    Route::delete('/{client}', [ClientController::class, 'destroy']);
    Route::put('/{client}', [ClientController::class, 'update']);
});

// Prices Routes
Route::prefix('/prices')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [PriceController::class, 'index'])->name('prices.page-import');
    Route::post('/', [PriceController::class, 'index'])->name('prices.import');
});

// Prices Routes
Route::prefix('/cars')->middleware(['auth'])->group(function () {
    Route::get('/', [CarController::class, 'index']);
    Route::put('/{visit}', [CarController::class, 'update']);
});

// Cash
Route::prefix('/transactions')->middleware('auth')->group(function () {
    Route::post('/', [TransactionController::class, 'insert'])->name('transactions.insert');
});

