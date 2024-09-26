<?php
namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class FirebaseService
{
    protected $database;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials_file'))
            ->withDatabaseUri(config('firebase.database_url'));

        $this->database = $firebase->createDatabase();
    }

    public function testConnection()
    {
        // Test simple : écriture d'un message dans Firebase pour vérifier la connexion
        $ref = $this->database->getReference('test-connection')->set([
            'message' => 'Connexion réussie avec Firebase!',
            'timestamp' => now(),
        ]);

        return $ref->getValue(); // Retourne la valeur écrite dans Firebase
    }
}
