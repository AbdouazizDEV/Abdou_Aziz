<?php

namespace App\Repositories;

use App\Models\FirebasePromotion;
use Kreait\Firebase\Database;

class FirebasePromotionRepository
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function create(array $data)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('promotions');
        
        // Ajoute la promotion dans Firebase avec un état par défaut
        $data['etat'] = 'Inactif';  // L'état par défaut est Inactif
        $newPromotion = $reference->push($data);
        
        // Récupère et retourne la nouvelle promotion avec son ID généré
        $newData = $newPromotion->getValue();
        $newData['id'] = $newPromotion->getKey();

        return $newData;
    }

    public function all($etat = null): array
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('promotions');

        if ($etat) {
            // Filtre les promotions par état si un état est fourni
            $query = $reference->orderByChild('etat')->equalTo($etat);
        } else {
            $query = $reference;
        }

        $snapshot = $query->getSnapshot()->getValue() ?? [];
        $promotions = [];

        foreach ($snapshot as $key => $data) {
            $data['id'] = $key;  // Ajoute l'ID Firebase comme clé
            $promotions[] = (new FirebasePromotion())->fromArray($data);
        }

        return $promotions;
    }

    public function find($id)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('promotions/' . $id);
        $data = $reference->getSnapshot()->getValue();

        if ($data) {
            $data['id'] = $id;  // Ajoute l'ID à la promotion récupérée
            return (new FirebasePromotion())->fromArray($data);
        }

        return null;
    }

    public function update($id, array $data)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('promotions/' . $id);
        
        // Met à jour les données de la promotion dans Firebase
        $reference->update($data);
        
        // Récupère les données mises à jour pour vérification
        $updatedData = $reference->getSnapshot()->getValue();
        $updatedData['id'] = $id;  // Ajoute l'ID Firebase

        return (new FirebasePromotion())->fromArray($updatedData);
    }

    public function delete($id)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('promotions/' . $id);

        // Supprime la promotion dans Firebase
        $reference->remove();

        return true;
    }

    public function deactivateOtherPromotions($currentPromotionId)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('promotions');

        // Recherche toutes les promotions actives
        $promotions = $reference->orderByChild('etat')->equalTo('Actif')->getSnapshot()->getValue() ?? [];

        // Désactive les autres promotions
        foreach ($promotions as $key => $promotion) {
            if ($key !== $currentPromotionId) {
                $firebase->getReference('promotions/' . $key)->update(['etat' => 'Inactif']);
            }
        }
    }
}
