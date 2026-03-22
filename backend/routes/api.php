<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
    Route::middleware('auth:sanctum')->get('me', [AuthController::class, 'me']);
});

Route::get('/doctors', [DoctorController::class, 'index']);

Route::middleware('auth:sanctum')->post('/appointments', [AppointmentController::class, 'store']);
Route::middleware('auth:sanctum')->get('/appointments', [AppointmentController::class, 'index']);
