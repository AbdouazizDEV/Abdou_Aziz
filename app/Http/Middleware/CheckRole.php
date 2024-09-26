<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
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

        // Permissions associées aux rôles
        $rolePermissions = [
            1 => [1, 2, 3, 4, 5],  // Admin peut créer tous les rôles
            2 => [2, 3, 4, 5],     // Manager peut créer manager, coach, cm, apprenant
            4 => [5],              // CM peut créer uniquement des apprenants
        ];

        // Vérifie si l'utilisateur a la permission nécessaire
        if (!$user || !isset($rolePermissions[$user->role_id]) || !in_array($roleMapping[$role], $rolePermissions[$user->role_id])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
