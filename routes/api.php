<?php

use App\Http\Controllers\Api\V1\Authentication\ConfirmPasswordController;
use App\Http\Controllers\Api\V1\Authentication\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Authentication\LoginController;
use App\Http\Controllers\Api\V1\Authentication\LogoutController;
use App\Http\Controllers\Api\V1\Authentication\RegisterController;
use App\Http\Controllers\Api\V1\Authentication\ResetPasswordController;
use App\Http\Controllers\Api\V1\Authentication\VerifyEmailController;
use App\Http\Controllers\Api\V1\Lookup\RoleLookupController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->as('api.v1.')->group(function () {
    Route::prefix('auth')->as('auth.')->group(function () {
        Route::post('/login', LoginController::class)->name('login');
        Route::post('/register', RegisterController::class)->name('register');
        Route::post('/forgot-password', ForgotPasswordController::class)->name('password.request');
        Route::post('/reset-password/{token}', ResetPasswordController::class)->name('password.reset');
    });
    Route::middleware('auth:sanctum')->group(function() {
        Route::prefix('auth')->as('auth.')->group(function () {
            Route::post('/logout', LogoutController::class)->name('logout');
            Route::post('/confirm-password', ConfirmPasswordController::class)->name('password.confirm');
            Route::post('/verify-email', VerifyEmailController::class)->name('verification.notice');
        });
        Route::prefix('lookups')->as('lookups.')->group(function () {
            Route::get('/roles', RoleLookupController::class)->name('roles');
        });
    });
});
