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
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('users');
        
        // Ajout du nouvel utilisateur dans Firebase
        $newUser = $reference->push($data);
        
        // Retourne les données avec l'ID généré
        $newData = $newUser->getValue();
        $newData['id'] = $newUser->getKey();

        return $newData;
    }

    public function all($role = null)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('users');

        if ($role) {
            // Filtre par rôle si fourni
            $query = $reference->orderByChild('role_id')->equalTo($role);
        } else {
            $query = $reference;
        }

        $snapshot = $query->getSnapshot()->getValue() ?? [];
        $users = [];

        foreach ($snapshot as $key => $data) {
            $data['id'] = $key;  // Ajoute l'ID Firebase
            $users[] = (new FirebaseUser())->fromArray($data);
        }

        return $users;
    }

    public function find($id)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('users/' . $id);
        $data = $reference->getSnapshot()->getValue();

        if ($data) {
            $data['id'] = $id;
            return (new FirebaseUser())->fromArray($data);
        }

        return null;
    }

    public function update($id, array $data)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('users/' . $id);

        // Mettre à jour l'utilisateur dans Firebase
        $reference->update($data);

        $updatedData = $reference->getSnapshot()->getValue();
        $updatedData['id'] = $id;  // Ajoute l'ID Firebase

        return (new FirebaseUser())->fromArray($updatedData);
    }
}
