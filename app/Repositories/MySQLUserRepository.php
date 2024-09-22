<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // Import du Hash

class MySQLUserRepository implements UserRepositoryInterface
{
    public function create(array $data): array
    {
        // Hachage du mot de passe avant de créer l'utilisateur
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']); // Utilisation de Hash::make() qui utilise Bcrypt par défaut
        }

        // Création de l'utilisateur dans MySQL
        $user = User::create($data);
        return $user->toArray();
    }

    public function all(): array
    {
        return User::all()->toArray();
    }
    public function find($id){

        return User::find($id);
    }
    public function update($id, array $data){

        $user = User::find($id);
        if($user){
            $user->update($data);
            return $user;
        }
        return null;
    }

}
