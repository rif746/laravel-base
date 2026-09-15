<?php

use App\Attributes\Ui\LayoutData;
use App\Attributes\Ui\Seo;
use App\Http\Controllers\Web\Account\ProfileController;
use App\Http\Controllers\Web\Identity\RoleController;
use App\Http\Controllers\Web\Identity\UserController;
use App\Http\Controllers\Web\System\AuditController;
use Dedoc\Scramble\Scramble;
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
                'ui/menu.dashboard' => 'dashboard',
            ],
        ))->can('dashboard.index')
        ->name('dashboard');

    Route::livewire('/user/{user_id}', 'pages::identity.users.detail-view')
        ->can('user.view')
        ->name('users.view');
    Route::get('/users', UserController::class)
        ->can('user.viewAny')
        ->name('users.index');
    Route::get('/roles', RoleController::class)
        ->can('role.viewAny')
        ->name('roles.index');

    Route::middleware('password.confirm')->group(function () {
        Route::get('/system-logs', AuditController::class)
            ->can('system-log.manage')
            ->name('system-log.index');
        Route::livewire('/system/settings', 'pages::system.settings.setting-list')
            ->can('system-setting.manage')
            ->name('system-setting.index');
        Route::livewire('/system/backups', 'pages::system.backups.backup-list')
            ->can('system-backup.manage')
            ->name('system-backup.index');

        Route::get('/profile', ProfileController::class)->name('profile.index');
    });
});

Route::domain('docs.'.config('app.url'))->group(function() {
    Scramble::registerUiRoute('/');
    Scramble::registerJsonSpecificationRoute('api.json');
});

require __DIR__.'/auth.php';
