<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TipoContratoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\VendaController;


// Grupo de rotas de autenticação
Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::apiResource('vendas', VendaController::class)->except(['create', 'edit']);

    Route::get('vendas/{venda}/documentos', [VendaDocumentoController::class, 'index']);
    Route::post('vendas/{venda}/documentos', [VendaDocumentoController::class, 'store']);
    Route::delete('vendas/{venda}/documentos/{documento}', [VendaDocumentoController::class, 'destroy']);


    Route::apiResource('clientes', ClienteController::class)->except(['create', 'edit']);

    Route::get('clientes/{cliente}/documentos', [DocumentoController::class, 'index']);
    Route::post('clientes/{cliente}/documentos', [DocumentoController::class, 'store']);
    Route::delete('documentos/{documento}', [DocumentoController::class, 'destroy']);
    Route::put('documentos/{documento}/arquivo', [DocumentoController::class, 'updateFile']);

});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('users', UserController::class)
        ->except(['create', 'edit']);

    Route::apiResource('tipos-contrato', TipoContratoController::class)
        ->except(['create', 'edit']);
});