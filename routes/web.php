<?php

use App\Attributes\Seo;
use App\Http\Controllers\Web\Identity\RoleController;
use App\Http\Controllers\Web\Identity\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['web', 'auth', 'verified', 'seo'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/users', UserController::class)->name('users.index');
    Route::get('/roles', RoleController::class)->name('roles.index');

    Route::middleware('password.confirm')->group(function () {
        Route::livewire('/system/settings', 'pages::system.settings')->name('system-setting.index');

        Route::view('/profile', 'pages.account.profile.index')
            ->defaults('seo', new Seo(
                title: 'domains/account.seo.profile.title',
                description: 'domains/account.seo.profile.description',
                keywords: 'domains/account.seo.profile.keywords'
            ))->name('profile.index');
    });
});

require __DIR__.'/auth.php';
