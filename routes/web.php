<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::middleware('auth', 'verified')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/roles', \App\Livewire\Role\RoleTable::class)->name('role.index');
    Route::get('/users', \App\Livewire\User\UserTable::class)->name('user.index');
    Route::get('/profile', \App\Livewire\Profile\ProfilePage::class)->name('profile.index')->middleware('password.confirm');
});

Route::get('/files', fn() => response()->file(Storage::disk('private')->path(request()->query('path'))))->middleware('signed')->name('storage.local');

require __DIR__ . '/auth.php';
