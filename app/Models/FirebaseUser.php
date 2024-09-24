<?php

namespace App\Models;
use App\Models\FirebaseModel;
class FirebaseUser extends FirebaseModel
{
    protected $path = 'users';  // Collection 'users' dans Firebase
    protected $fillable = [
        'nom', 'prenom', 'adresse', 'telephone', 'role_id', 'email', 'photo', 'statut', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function save(array $options = [])
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference($this->path);  // Pointe vers la collection 'users'
        $data = $this->attributesToArray();  // Convertit les attributs en tableau

        $reference->push($data);  // Ajoute l'utilisateur à Firebase
    }
    protected function getCollectionName(): string
    {
        return 'users'; // Spécifiez ici le nom de la collection pour ce modèle
    }
}
