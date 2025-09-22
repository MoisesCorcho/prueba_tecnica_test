<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('tasks', TaskController::class)
    ->middleware('auth:sanctum');

Route::post('/register', RegisterController::class)->middleware('guest:sanctum');
Route::post('/login', LoginController::class)->middleware('guest:sanctum');
Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');

Route::get('/status', function () {
    return response()->json(['status' => 'ok']);
});
