<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReferentielController;
use Laravel\Passport\Passport;
use Kreait\Firebase\Factory;
use App\Services\FirebaseService;


Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login'); // Login
    Route::post('/logout', [AuthController::class, 'logout']); // Logout
});

Route::middleware(['auth:api', 'check.role:admin'])->group(function () {
    Route::post('/users/forAdmin', [UserController::class, 'storeAdmin']);
    Route::get('/users', [UserController::class, 'index']); // Route pour lister les utilisateurs
    Route::get('/users/{id}', [UserController::class, 'show']); // Route pour afficher un utilisateur
    Route::put('/users/{id}', [UserController::class, 'update']); // Route pour mettre à jour un utilisateur

});
Route::prefix('v1')->group(function () {
    Route::middleware(['auth:api', 'check.role:admin,manager'])->group(function () {
        Route::post('/referentiels', [ReferentielController::class, 'store']);
        Route::get('/referentiels', [ReferentielController::class, 'index']);
        Route::get('/referentiels/{id}', [ReferentielController::class, 'show']);
        Route::patch('/referentiels/{id}', [ReferentielController::class, 'update']);
        Route::delete('/referentiels/{id}', [ReferentielController::class, 'destroy']);
        Route::get('/archive/referentiels', [ReferentielController::class, 'getarchive']);

    });
    
});
Route::prefix('v1')->middleware(['auth:api', 'check.role:admin,manager'])->group(function () {
    Route::post('/promotions', [PromotionController::class, 'store']); // Ajouter une promotion
    Route::get('/promotions', [PromotionController::class, 'index']); // Lister les promotions
    Route::get('/promotions/{id}', [PromotionController::class, 'show']); // Afficher une promotion
    Route::patch('/promotions/{id}', [PromotionController::class, 'update']); // Mettre à jour une promotion
    Route::delete('/promotions/{id}', [PromotionController::class, 'destroy']); // Supprimer une promotion
});

/* Route::middleware(['firebase.auth'])->group(function () {
    // Vos routes ici
}); */

Route::middleware(['auth:api', 'check.role:cm'])->group(function () {
    Route::post('/users/forCm', [UserController::class, 'storeCm']);
});

Route::middleware(['auth:api', 'check.role:manager'])->group(function () {
    Route::post('/users/forManager', [UserController::class, 'storeManager']);
});

use App\Http\Controllers\NotificationController;

Route::get('/test-firebase', function (FirebaseService $firebaseService) {
    $result = $firebaseService->testConnection();
    return response()->json($result);
});