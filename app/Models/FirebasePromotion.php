<?php

namespace App\Models;
use App\Models\FirebaseModel;
class FirebasePromotion extends FirebaseModel
{
    protected $path = 'promotions'; // La collection dans Firebase
    protected $fillable = [
        'libelle', 'date_debut', 'date_fin', 'duree', 'etat', 'photo'
    ];
    public function getCollectionName(): string
    {
        return 'promotions'; // Retourner ici le nom correct de la collection
    }
    public function save(array $options = [])
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference($this->path);  // Utilise le chemin défini pour 'promotions'

        $data = $this->attributesToArray();  // Convertir l'objet en tableau
        $reference->push($data);  // Ajoute la promotion à Firebase
    }
    // Méthode pour récupérer toutes les promotions
    public static function getAll($etat = null): array
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('promotions');

        if ($etat) {
            // Filtrer par l'état
            $query = $reference->orderByChild('etat')->equalTo($etat);
        } else {
            $query = $reference;
        }

        $snapshot = $query->getSnapshot()->getValue() ?: [];

        $promotions = [];
        foreach ($snapshot as $key => $data) {
            $data['id'] = $key;
            $promotions[] = (new self())->fromArray($data);
        }

        return $promotions;
    }

    public static function findById($id): ?self
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('promotions/' . $id);
        $data = $reference->getSnapshot()->getValue();

        if ($data) {
            $data['id'] = $id;
            return (new self())->fromArray($data);
        }

        return null;
    }

    public function update(array $attributes = [], array $options = []): self
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('promotions/' . $this->id);

        $reference->update($attributes);

        $updatedData = $reference->getSnapshot()->getValue();
        $this->fromArray($updatedData);

        return $this;
    }

    public function delete(): bool
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('promotions/' . $this->id);

        $reference->remove();

        return true;
    }
    public function fromArray(array $data): self
    {
        // Utilisez les attributs existants pour peupler les propriétés de l'objet
        $this->libelle = $data['libelle'] ?? null;
        $this->date_debut = $data['date_debut'] ?? null;
        $this->date_fin = $data['date_fin'] ?? null;
        $this->duree = $data['duree'] ?? null;
        $this->etat = $data['etat'] ?? null;
        $this->photo = $data['photo'] ?? null;
        $this->referentiels = $data['referentiels'] ?? [];

        return $this;
    }

    // Méthode pour convertir l'objet en tableau
    public function toArray(): array
    {
        return [
            'libelle' => $this->libelle,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'duree' => $this->duree,
            'etat' => $this->etat,
            'photo' => $this->photo,
            'referentiels' => $this->referentiels,
        ];
    }
}
