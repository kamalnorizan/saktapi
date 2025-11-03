<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\MohonTinggalKenderaanController;

// Public authentication routes
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Authentication routes
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/profile', [AuthenticationController::class, 'profile']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Permohonan Tinggal Kenderaan routes
    Route::prefix('permohonan')->group(function () {
        Route::get('/', [MohonTinggalKenderaanController::class, 'index']);
        Route::post('/bydate', [MohonTinggalKenderaanController::class, 'getByDate']);
    });
});
