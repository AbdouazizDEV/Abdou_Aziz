<?php

namespace App\Repositories;

use App\Models\FirebaseUser;
use Kreait\Firebase\Database;

class FirebaseUserRepository
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function create(array $data)
    {
        $user = new FirebaseUser($data);
        //dd($user);
        $user->save();
        return $user;
    }
    public function all($role = null)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('users');
        //dd($reference);http://localhost:8000/api/users?role=admin
        // Appliquer le filtre si le rôle est spécifié
        if ($role) {
            //dd($role);
            $usersSnapshot = $reference->orderByChild('role_id')->equalTo($role)->getSnapshot();
            dd($usersSnapshot);
        } else {
            $usersSnapshot = $reference->getSnapshot();
        }

        $users = $usersSnapshot->getValue();

        return $users ?: [];
    }
    public function find($id)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('users/' . $id);
        $snapshot = $reference->getSnapshot();
        $user = $snapshot->getValue();
    
        return $user ?: null;
    }
    public function update($id, array $data)
{
    //dd($data, $id);
    // Récupérer l'utilisateur dans Firebase
    $firebase = app('firebase.database');
    $reference = $firebase->getReference('users/' . $id);
    $snapshot = $reference->getSnapshot();
    //dd($snapshot);
    if (!$snapshot->exists()) {
        // Si l'utilisateur n'existe pas, renvoyer null ou lever une exception
        return null;
    }

    // Mettre à jour les données de l'utilisateur
    $reference->update($data);

    // Récupérer les données mises à jour et les retourner
    $updatedSnapshot = $reference->getSnapshot();
    return $updatedSnapshot->getValue();
}

}
