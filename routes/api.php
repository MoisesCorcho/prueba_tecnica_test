<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;

Route::prefix('v1')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::apiResource('tasks', TaskController::class)
        ->middleware('auth:sanctum');

    Route::post('/register', RegisterController::class)->middleware('guest:sanctum');
    Route::post('/login', LoginController::class)->middleware('guest:sanctum');
    Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');

    Route::get('/health', function () {
        try {
            // Intenta establecer una conexión con la base de datos
            DB::connection()->getPdo();

            return response()->json([
                'status' => 'online',
                'database' => 'connected'
            ]);
        } catch (\Exception $e) {
            // Si la conexión falla, devuelve un error 503
            return response()->json([
                'status' => 'offline',
                'database' => 'disconnected',
                'error' => 'Could not connect to the database.'
            ], 503); // HTTP 503 Service Unavailable
        }
    });
});
