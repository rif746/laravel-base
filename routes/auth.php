<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware("guest")->group(function () {
    Route::get("register", \App\Livewire\Auth\RegisterPage::class)->name(
        "register"
    );

    Route::get("login", \App\Livewire\Auth\LoginPage::class)->name("login");

    Route::get(
        "forgot-password",
        \App\Livewire\Auth\ForgotPasswordPage::class,
    )->name("password.request");

    Route::get("reset-password/{token}", \App\Livewire\Auth\ResetPasswordPage::class)->name("password.reset");

});

Route::middleware("auth")->group(function () {
    Route::get("verify-email", EmailVerificationPromptController::class)->name(
        "verification.notice"
    );

    Route::get("verify-email/{id}/{hash}", VerifyEmailController::class)
        ->middleware(["signed", "throttle:6,1"])
        ->name("verification.verify");

    Route::post("email/verification-notification", [
        EmailVerificationNotificationController::class,
        "store",
    ])
        ->middleware("throttle:6,1")
        ->name("verification.send");

    Route::get("confirm-password", [
        ConfirmablePasswordController::class,
        "show",
    ])->name("password.confirm");

    Route::post("confirm-password", [
        ConfirmablePasswordController::class,
        "store",
    ]);

    Route::post("logout", [
        AuthenticatedSessionController::class,
        "destroy",
    ])->name("logout");
});
