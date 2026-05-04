<?php

use App\Http\Controllers\Web\Account\AccountSettingController;
use App\Http\Controllers\Web\Account\ProfileController;
use App\Http\Controllers\Web\Identity\RoleController;
use App\Http\Controllers\Web\Identity\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('/users', UserController::class)->except(['create', 'edit']);
    Route::resource('/roles', RoleController::class)->except(['create', 'edit']);

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/settings', [AccountSettingController::class, 'index'])->name('profile.settings.index');
    Route::patch('/profile/settings', [AccountSettingController::class, 'update'])->name('profile.settings.update');
});

require __DIR__.'/auth.php';
