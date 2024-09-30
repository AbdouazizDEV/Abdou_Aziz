<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user(); // Récupère l'utilisateur connecté

        // Mapping des rôles
        $roleMapping = [
            'admin' => 1,
            'manager' => 2,
            'coach' => 3,
            'cm' => 4,
            'apprenant' => 5,
        ];

        // Si l'utilisateur n'est pas connecté ou ne possède pas de rôle valide
        if (!$user || !in_array($user->role_id, array_values($roleMapping))) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Vérification des rôles
        $allowedRoles = array_map(fn($role) => $roleMapping[$role], $roles);
        if (!in_array($user->role_id, $allowedRoles)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
