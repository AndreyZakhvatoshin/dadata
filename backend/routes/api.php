<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CounterpartyController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('user.register');
Route::post('/login', [AuthController::class, 'login'])->name('user.login');

Route::middleware('auth:sanctum')
    ->prefix('counterparty')
    ->group(function () {
        Route::apiResource('/', CounterpartyController::class)->only(['index', 'store']);
    });
