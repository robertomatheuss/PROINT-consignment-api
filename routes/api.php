<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Grupo de rotas de autenticação
Route::prefix('auth')->group(function () {

    // Login (público)
    Route::post('/login', [AuthController::class, 'login']);

    // Rotas protegidas
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });

});
