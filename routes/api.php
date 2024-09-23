<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReferentielController;
;

Route::prefix('v1')->group(function () {

    // Auth Routes
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout']);

    // Protected Routes
    Route::middleware(['auth:api', 'check.role:admin,manager'])->group(function () {
        // Referentiel Routes
        Route::post('/referentiels', [ReferentielController::class, 'store'])->name('referentiels.store');
        Route::get('/referentiels', [ReferentielController::class, 'index'])->name('referentiels.index');
        Route::get('/referentiels/{id}', [ReferentielController::class, 'show'])->name('referentiels.show');
        Route::patch('/referentiels/{id}', [ReferentielController::class, 'update'])->name('referentiels.update');
        Route::delete('/referentiels/{id}', [ReferentielController::class, 'destroy'])->name('referentiels.destroy');
        Route::get('/archive/referentiels', [ReferentielController::class, 'archived'])->name('referentiels.archived');
    });

    Route::middleware(['auth:api', 'check.role:cm'])->post('/users/forCm', [UserController::class, 'storeCm']);
    Route::middleware(['auth:api', 'check.role:manager'])->post('/users/forManager', [UserController::class, 'storeManager']);
});
