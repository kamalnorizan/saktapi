<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MobileLoginController;

Route::get('/mobilelogin', [MobileLoginController::class, 'loginform'])
    ->name('mobilelogin.login')
    ->middleware('guest');

Route::post('/mobilelogin', [MobileLoginController::class, 'login'])
    ->name('mobilelogin')
    ->middleware('guest');

require __DIR__.'/auth.php';
