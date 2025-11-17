<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Grupo de rotas de autenticação
Route::prefix('auth')->group(function () {

    // Login (público)
    Route::post('/login', [AuthController::class, 'login']);

    // Rotas protegidas
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('users', UserController::class)->except(['create', 'edit']);
});