<?php

use App\Attributes\LayoutData;
use App\Attributes\Seo;
use App\Http\Controllers\Web\Account\ProfileController;
use App\Http\Controllers\Web\Identity\RoleController;
use App\Http\Controllers\Web\Identity\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['web', 'auth', 'verified', 'seo', 'layouts'])->group(function () {
    Route::view('/dashboard', 'dashboard')
        ->defaults('seo', new Seo(
            title: 'seo.dashboard.title',
            description: 'seo.dashboard.description',
            keywords: 'seo.dashboard.keywords'
        ))->defaults('layout_data', new LayoutData(
            header: 'seo.dashboard.title',
            breadcrumbs: [
                'ui.menu.dashboard' => 'dashboard',
            ],
        ))->name('dashboard');

    Route::livewire('/user/{user_id}', 'pages::identity.users.detail-view')->name('users.view');
    Route::get('/users', UserController::class)->name('users.index');
    Route::get('/roles', RoleController::class)->name('roles.index');

    Route::middleware('password.confirm')->group(function () {
        Route::livewire('/system/settings', 'pages::system.settings.setting-list')->name('system-setting.index');
        Route::livewire('/system/backups', 'pages::system.backups.backup-list')->name('system-backup.index');

        Route::get('/profile', ProfileController::class)->name('profile.index');
    });
});

require __DIR__.'/auth.php';
