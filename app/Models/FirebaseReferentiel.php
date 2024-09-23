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
}
