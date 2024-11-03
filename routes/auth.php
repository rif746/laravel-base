<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\Actions\Logout;
use App\Livewire\Auth\ConfirmPasswordPage;
use App\Livewire\Auth\VerifyEmailPage;
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
    Route::get("verify-email", VerifyEmailPage::class)->name(
        "verification.notice"
    );

    Route::get('password/confirm', ConfirmPasswordPage::class)->name('password.confirm');

    Route::get("verify-email/{id}/{hash}", VerifyEmailController::class)
        ->middleware(["signed", "throttle:6,1"])
        ->name("verification.verify");

    Route::post("logout",
        Logout::class,
    )->name("logout");
});
