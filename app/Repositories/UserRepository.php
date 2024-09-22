<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data)
    {
        return User::create($data);
    }
    // Méthode pour récupérer tous les utilisateurs
    public function all($role = null)
    {
        // Si un rôle est spécifié, on applique un filtre
        if ($role) {
            return User::where('role_id', $role)->get();
        }
    
        // Sinon, on retourne tous les utilisateurs
        return User::all();
    }
    public function find($id)
    {
        return User::find($id);
    }

    public function update($id, array $data)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }

        return null;
    }

    
}
