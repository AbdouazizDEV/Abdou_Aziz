<?php

namespace App\Repositories;

use App\Models\FirebaseReferentiel;
use Kreait\Firebase\Database;

class FirebaseReferentielRepository
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function create(array $data)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('referentiels');
        
        // Ajout du nouveau référentiel dans Firebase
        $newReferentiel = $reference->push($data);
        
        // Retourne les données du référentiel avec son ID Firebase
        $newData = $newReferentiel->getValue();
        $newData['id'] = $newReferentiel->getKey();

        return $newData;
    }

    public function all($statut = null): array
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('referentiels');

        if ($statut) {
            // Filtrer par statut si fourni
            $query = $reference->orderByChild('statut')->equalTo($statut);
        } else {
            $query = $reference;
        }

        $snapshot = $query->getSnapshot()->getValue() ?? [];
        $referentiels = [];

        foreach ($snapshot as $key => $data) {
            $data['id'] = $key;  // Ajoute l'ID Firebase
            $referentiels[] = (new FirebaseReferentiel())->fromArray($data);
        }

        return $referentiels;
    }

    public function find($id)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('referentiels/' . $id);
        $data = $reference->getSnapshot()->getValue();

        if ($data) {
            $data['id'] = $id;
            return (new FirebaseReferentiel())->fromArray($data);
        }

        return null;
    }

    public function update($id, array $data)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('referentiels/' . $id);

        // Mettre à jour les données du référentiel
        $reference->update($data);

        $updatedData = $reference->getSnapshot()->getValue();
        $updatedData['id'] = $id;  // Ajoute l'ID Firebase

        return (new FirebaseReferentiel())->fromArray($updatedData);
    }

    public function delete($id)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('referentiels/' . $id);

        // Supprime le référentiel dans Firebase
        $reference->remove();

        return true;
    }
}
