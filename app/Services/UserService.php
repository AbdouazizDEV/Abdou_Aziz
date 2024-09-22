<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\FirebaseUserRepository;
use Illuminate\Support\Facades\Hash; // Import du Hash

class UserService
{
    protected $userRepository;
    protected $firebaseUserRepository;

    public function __construct(UserRepositoryInterface $userRepository, FirebaseUserRepository $firebaseUserRepository)
    {
        $this->userRepository = $userRepository;
        $this->firebaseUserRepository = $firebaseUserRepository;
    }

    public function createUser(array $data)
    {
        // Hachage du mot de passe avant d'ajouter dans MySQL et Firebase
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Ajout dans MySQL
        $user = $this->userRepository->create($data);

        // Ajout dans Firebase
        $firebaseUser = $this->firebaseUserRepository->create($data);

        // Retourner les deux utilisateurs si nécessaire (ou juste MySQL selon votre besoin)
        return ['mysql' => $user, 'firebase' => $firebaseUser];
    }
    
    // Nouvelle méthode pour récupérer tous les utilisateurspublic function getAllUsers($role = null)
    public function getAllUsers($role = null)
    {
        $dataSource = env('USER_DATA_SOURCE', 'firebase'); // Par défaut mysql
        // dd($dataSource); // Cela devrait afficher 'firebase' ou 'mysql' selon le .env
    
        if ($dataSource === 'firebase') {
            return $this->firebaseUserRepository->all($role);
        } else {
            return $this->userRepository->all($role);
        }
    }
    

public function getUserById($id)
{
    $dataSource = env('USER_DATA_SOURCE', 'mysql'); // Utiliser 'mysql' par défaut
    //dd($dataSource); // Vérifiez la sortie ici
    
    if ($dataSource === 'firebase') {
        return $this->firebaseUserRepository->find($id);
    } else {
        return $this->userRepository->find($id);
    }
}

public function updateUser($id, array $data)
{
    // Hachage du mot de passe si présent
    if (isset($data['password'])) {
        $data['password'] = Hash::make($data['password']);
    }

    // Mettre à jour l'utilisateur dans MySQL
    $mysqlUser = $this->userRepository->update($id, $data);

    // Mettre à jour dans Firebase également si nécessaire
    $firebaseUser = $this->firebaseUserRepository->update($id, $data);
    //dd($firebaseUser); // Vérifiez la sortie ici

    return ['mysql' => $mysqlUser, 'firebase' => $firebaseUser];
}



}
