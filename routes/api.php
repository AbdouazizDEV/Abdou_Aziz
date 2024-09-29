<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReferentielController;
use App\Http\Controllers\NotificationController;
use App\Services\FirebaseService;
use App\Http\Controllers\ApprenantController;

Route::prefix('v1')->group(function () {

    // Routes publiques (authentification)
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout']);

    // Routes protégées (authentification nécessaire)
    Route::middleware(['auth:api'])->group(function () {

        // Routes Admin uniquement
        Route::middleware('check.role:admin')->group(function () {
            Route::post('/users/forAdmin', [UserController::class, 'storeAdmin']);
            Route::post('/users/import', [UserController::class, 'import']);
            Route::get('/users', [UserController::class, 'index']);
            Route::get('/users/{id}', [UserController::class, 'show']);
            Route::put('/users/{id}', [UserController::class, 'update']);
        });

        // Routes Admin et Manager
        Route::middleware('check.role:admin,manager')->group(function () {
            // Référentiels
            Route::post('/referentiels', [ReferentielController::class, 'store']);
            Route::post('/referentiels/import', [ReferentielController::class, 'import']);
            Route::get('/referentiels', [ReferentielController::class, 'index']);
            Route::get('/referentiels/{id}', [ReferentielController::class, 'show']);
            Route::patch('/referentiels/{id}', [ReferentielController::class, 'update']);
            Route::delete('/referentiels/{id}', [ReferentielController::class, 'destroy']);
            Route::get('/archive/referentiels', [ReferentielController::class, 'getarchive']);

            // Promotions
            Route::post('/promotions', [PromotionController::class, 'store']);
            Route::get('/promotions', [PromotionController::class, 'index']);
            Route::get('/promotions/{id}', [PromotionController::class, 'show']);
            Route::patch('/promotions/{id}', [PromotionController::class, 'update']);
            Route::patch('/promotions/{id}/referentiels', [PromotionController::class, 'updateReferentiels']);
            Route::patch('/promotions/{id}/etat', [PromotionController::class, 'changeEtat']);
            Route::patch('/promotions/{id}/cloturer', [PromotionController::class, 'cloturer']);
        });

        // Routes Admin, Manager, et CM
        Route::middleware('check.role:admin,manager,cm')->group(function () {
            Route::post('/apprenants', [ApprenantController::class, 'store']);
            Route::post('/apprenants/import', [ApprenantController::class, 'import']);
            Route::get('/apprenants', [ApprenantController::class, 'index']);
            Route::get('/apprenants/{id}', [ApprenantController::class, 'show']);
            Route::post('/apprenants/inactive', [ApprenantController::class, 'inactiveList']);
            Route::post('/apprenants/relance', [ApprenantController::class, 'relance']);
            Route::post('/apprenants/{id}/relance', [ApprenantController::class, 'relanceIndividuel']);
        });

        // Routes spécifiques à CM
        Route::middleware('check.role:cm')->group(function () {
            Route::post('/users/forCm', [UserController::class, 'storeCm']);
        });

        // Routes spécifiques à Manager
        Route::middleware('check.role:manager')->group(function () {
            Route::post('/users/forManager', [UserController::class, 'storeManager']);
        });
    });

    // Test Firebase route
    Route::get('/test-firebase', function (FirebaseService $firebaseService) {
        $result = $firebaseService->testConnection();
        return response()->json($result);
    });
});
