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
        // Assurez-vous que la référence pointe vers la collection 'referentiels'
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('referentiels');  // Pointe vers la collection 'referentiels'

        // Ajout du référentiel dans Firebase
        $newReferentiel = $reference->push($data);  // push() ajoute un nouveau référentiel

        return $newReferentiel->getValue();  // Retourne les données du référentiel ajouté
    }

    public function all($statut = null): array
    {
        return FirebaseReferentiel::getAll($statut);  // Appel de la méthode du modèle
    }

    public function find($id)
    {
        // $reference = $this->database->getReference('referentiels/' . $id . '.json');  // Ajoutez .json ici
        // return $reference->getSnapshot()->getValue() ?: null;
        return FirebaseReferentiel::findById($id);  // Appel de la méthode du modèle
    }

    public function update($id, array $data): bool|FirebaseReferentiel
    {
        // Trouver le référentiel avec l'ID donné
        $referentiel = FirebaseReferentiel::findById($id);
    
        if (!$referentiel) {
            throw new \Exception('Référentiel non trouvé');
        }
    
        // Appeler la méthode d'instance update
        $referentiel->update($data);
    
        return $referentiel;
    }
    

    public function delete($id)
    {
        // Trouver le référentiel avec l'ID donné
        $referentiel = FirebaseReferentiel::findById($id);

        if (!$referentiel) {
            throw new \Exception('Référentiel non trouvé');
        }

        // Appeler la méthode d'instance delete() pour supprimer le référentiel
        $referentiel->delete();

        return true;
    }

}
