<?php

namespace App\Models;

class FirebaseUser extends FirebaseModel
{
    protected $fillable = [
        'nom', 'prenom', 'adresse', 'telephone', 'role_id', 'email', 'photo', 'statut', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    protected function getCollectionName(): string
    {
        return 'users'; // Spécifiez ici le nom de la collection pour ce modèle
    }
}
