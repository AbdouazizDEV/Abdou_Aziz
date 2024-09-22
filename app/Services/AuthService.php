<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function login(array $credentials): ?array
    {
        // Rechercher l'utilisateur par son email
        $user = User::where('email', $credentials['email'])->first();
       // dd( $user);
        if ($user || Hash::check($credentials['password'], $user->password)) {
            // Si le mot de passe correspond, on continue

            // Récupérer le rôle de l'utilisateur via role_id
            $role = $user->role_id;
            $user->role = $role; // Ajouter le rôle à l'utilisateur
            //dd($user);

            // Générer un token JWT ou Passport (si nécessaire)
            $token = $user->createToken('auth_token')->accessToken;

            // Retourner les informations de l'utilisateur et le token
            return [
                'token' => $token,
                'user' => $user,
            ];
        }

        // Si l'authentification échoue, retournez null
        return null;
    }

    public function register(array $data): User
    {
        return User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'adresse' => $data['adresse'],
            'telephone' => $data['telephone'],
            'role_id' => $data['role_id'] ?? 1,
            'email' => $data['email'],
            'statut' => 'actif',
            'password' => bcrypt($data['password']),
        ]);
    }
}
