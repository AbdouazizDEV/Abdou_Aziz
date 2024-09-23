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

   /*  public function create(array $data)
    {
        // Correction de la référence avec .json à la fin
        //dd($data);
        $reference = $this->database->getReference();  // Ajoutez .json ici
        $newReferentiel = $reference->push($data);

        return $newReferentiel->getValue();
    } */
    public function create(array $data)
    {
        $referentiel = new FirebaseReferentiel($data);
        $referentiel->save();

        return $referentiel;
    }

    public function all($statut = null): array
    {
        return FirebaseReferentiel::getAll($statut);  // Appel de la méthode du modèle
    }

    public function find($id)
    {
        $reference = $this->database->getReference('referentiels/' . $id . '.json');  // Ajoutez .json ici
        return $reference->getSnapshot()->getValue() ?: null;
    }

    public function update($id, array $data)
    {
        $reference = $this->database->getReference('referentiels/' . $id . '.json');  // Ajoutez .json ici
        $reference->update($data);
        return $reference->getSnapshot()->getValue();
    }

    public function delete($id)
    {
        $reference = $this->database->getReference('referentiels/' . $id . '.json');  // Ajoutez .json ici
        $reference->remove();
        return true;
    }
}
