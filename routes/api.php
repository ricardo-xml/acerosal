<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\InventarioManualController;

Route::get('/productos-por-familia/{id}', [InventarioManualController::class, 'productosPorFamilia']);
Route::get('/lotes/ultimo', [InventarioManualController::class, 'ultimoLote']);
