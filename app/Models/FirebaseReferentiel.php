<?php

namespace App\Models;

class FirebaseReferentiel extends FirebaseModel
{
    protected $path = 'referentiels';
    protected $fillable = ['code', 'libelle', 'description', 'photo', 'statut'];

    protected function getCollectionName(): string
    {
        return 'referentiels'; // Spécifiez ici le nom de la collection pour ce modèle
    }
     // Méthode pour récupérer tous les référentiels
     // Utilisation dans votre modèle FirebaseReferentiel pour filtrer par statut
public static function getAll($statut = null): array
{
    $firebase = app('firebase.database');
    $reference = $firebase->getReference('referentiels');

    if ($statut) {
        // Filtrer par le statut "actif" ou autre valeur
        $query = $reference->orderByChild('statut')->equalTo($statut);
    } else {
        $query = $reference;
    }

    $snapshot = $query->getSnapshot()->getValue() ?: [];

    $referentiels = [];
    foreach ($snapshot as $key => $data) {
        $data['id'] = $key;
        $referentiels[] = (new self())->fromArray($data);
    }

    return $referentiels;
}

 
     // Méthode pour trouver un référentiel par ID
     public static function findById($id): ?self
     {
         $firebase = app('firebase.database');
         $reference = $firebase->getReference('referentiels/' . $id);
         $data = $reference->getSnapshot()->getValue();
 
         if ($data) {
             $data['id'] = $id;
             return (new self())->fromArray($data);
         }
 
         return null;
     }
 
     // Méthode pour mettre à jour un référentiel
     public function update(array $attributes = [], array $options = []): self
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('referentiels/' . $this->id);
        
        // Mettre à jour les données dans Firebase
        $reference->update($attributes);
        
        // Mettre à jour l'objet actuel avec les nouvelles données
        $updatedData = $reference->getSnapshot()->getValue();
        $this->fromArray($updatedData);

        return $this;
    }

 
     // Méthode pour supprimer un référentiel
     public function delete(): bool
     {
         $firebase = app('firebase.database');
         $reference = $firebase->getReference('referentiels/' . $this->id);

         // Supprimer le référentiel dans Firebase 
         $reference->remove();
 
         return true;
     }
 
     // Méthode pour convertir un tableau en objet
     public function fromArray(array $data): self
     {
         $this->id = $data['id'] ?? null;
         $this->code = $data['code'] ?? null;
         $this->libelle = $data['libelle'] ?? null;
         $this->description = $data['description'] ?? null;
         $this->photo = $data['photo'] ?? null;
         $this->statut = $data['statut'] ?? null;
 
         return $this;
     }
 
     // Méthode pour convertir l'objet en tableau
     public function toArray(): array
     {
         return [
             'code' => $this->code,
             'libelle' => $this->libelle,
             'description' => $this->description,
             'photo' => $this->photo,
             'statut' => $this->statut,
         ];
     }
}
