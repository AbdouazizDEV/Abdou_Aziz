<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReferentielController;
use App\Http\Controllers\NotificationController;
use App\Services\FirebaseService;

Route::prefix('v1')->group(function () {

    // Routes publiques (authentification)
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout']);

    // Routes protégées par auth (nécessite une authentification)
    Route::middleware(['auth:api'])->group(function () {
        // Routes protégées par rôles : Admin
        Route::middleware('check.role:admin')->group(function () {
            Route::post('/users/forAdmin', [UserController::class, 'storeAdmin']);
            Route::post('/users/import', [UserController::class, 'import']);
            Route::get('/users', [UserController::class, 'index']); // Lister les utilisateurs
            Route::get('/users/{id}', [UserController::class, 'show']); // Afficher un utilisateur
            Route::put('/users/{id}', [UserController::class, 'update']); // Mettre à jour un utilisateur
        });

        // Routes protégées par rôles : Admin et Manager
        Route::middleware('check.role:admin,manager')->group(function () {
            // Routes pour les référentiels
            Route::post('/referentiels', [ReferentielController::class, 'store']);
            Route::post('/referentiels/import', [ReferentielController::class, 'import']); // Importer des référentiels
            Route::get('/referentiels', [ReferentielController::class, 'index']);
            Route::get('/referentiels/{id}', [ReferentielController::class, 'show']);
            Route::patch('/referentiels/{id}', [ReferentielController::class, 'update']);
            Route::delete('/referentiels/{id}', [ReferentielController::class, 'destroy']);
            Route::get('/archive/referentiels', [ReferentielController::class, 'getarchive']); // Récupérer les référentiels archivés

            // Routes pour les promotions
            Route::post('/promotions', [PromotionController::class, 'store']); // Créer une promotion
            Route::get('/promotions', [PromotionController::class, 'index']); // Lister les promotions
            Route::get('/promotions/{id}', [PromotionController::class, 'show']); // Afficher une promotion
            Route::patch('/promotions/{id}', [PromotionController::class, 'update']); // Mettre à jour une promotion
            Route::patch('/promotions/{id}/referentiels', [PromotionController::class, 'updateReferentiels']); // Ajouter/retirer des référentiels d'une promotion
            Route::patch('/promotions/{id}/etat', [PromotionController::class, 'changeEtat']); // Changer l'état d'une promotion
            Route::patch('/promotions/{id}/cloturer', [PromotionController::class, 'cloturer']); // Clôturer une promotion
        });
        

        // Routes spécifiques au rôle CM
        Route::middleware('check.role:cm')->group(function () {
            Route::post('/users/forCm', [UserController::class, 'storeCm']);
        });

        // Routes spécifiques au rôle Manager
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
