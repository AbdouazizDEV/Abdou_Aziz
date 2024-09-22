<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    // Authentifier un utilisateur et obtenir un token
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password'); // Récupère email et mot de passe
        //dd($credentials);
        $result = $this->authService->login($credentials); // Tente de connecter l'utilisateur
        //dd($result);
        if ($result) {
            return response()->json($result); // Renvoie le token et les infos de l'utilisateur
        }

        return response()->json(['error' => 'Unauthorized'], 401); // Échec de la connexion
    }

    // Déconnecter un utilisateur
    public function logout(Request $request)
    {
        $request->user()->token()->revoke(); // Révoque le token
        return response()->json(['message' => 'Successfully logged out']);
    }
}
