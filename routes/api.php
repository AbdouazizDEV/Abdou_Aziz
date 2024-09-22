<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReferentielController;
use Laravel\Passport\Passport;
use Kreait\Firebase\Factory;


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
        Route::post('/referentiels', [ReferentielController::class, 'store']); // ajouter un Réferentiel 
        Route::get('/referentiels', [ReferentielController::class, 'index']); // Lister tous les Réferentiel
        Route::get('/referentiels/{id}', [ReferentielController::class, 'show']);// Afficher un référentiel avec ses compétences et modules
        Route::get('/test', [ReferentielController::class, 'test']);
        Route::patch('/referentiels/{id}', [ReferentielController::class, 'update']);
        Route::delete('/referentiels/{id}', [ReferentielController::class, 'destroy']);
        Route::get('/archive/referentiels', [ReferentielController::class, 'archived']);
    });
});

Route::middleware(['auth:api', 'check.role:cm'])->group(function () {
    Route::post('/users/forCm', [UserController::class, 'storeCm']);
});

Route::middleware(['auth:api', 'check.role:manager'])->group(function () {
    Route::post('/users/forManager', [UserController::class, 'storeManager']);
});

Route::get('/test-firebase', function() {
    $firebase = (new Factory)
    ->withServiceAccount(config('firebase.credentials_file'))
    ->withDatabaseUri(config('firebase.database_url'));

    $database = $firebase->createDatabase();
    $reference = $database->getReference('test_node');
    $reference->set(['message' => 'Hello Firebase!']);

    return response()->json(['message' => 'Firebase is connected and working!']);
});