<?php

use App\Http\Controllers\Api\V1\Lookup\RoleLookupController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->as('api.v1.')->middleware('auth:sanctum')->group(function () {
    Route::prefix('lookups')->as('lookups.')->group(function () {
        Route::get('/roles', RoleLookupController::class)->name('roles');
    });
});
