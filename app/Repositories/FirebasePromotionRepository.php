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
        // Assurez-vous que la référence pointe bien vers 'promotions'
    $firebase = app('firebase.database');
    $reference = $firebase->getReference('promotions');  // Ceci doit pointer vers la collection 'promotions'
    
    // Ajoutez la promotion dans Firebase
    $newPromotion = $reference->push($data);  // push() ajoute un nouvel élément
    
    return $newPromotion->getValue();
    }

    public function all($etat = null): array
    {
        return FirebasePromotion::getAll($etat);
    }

    public function find($id)
    {
        return FirebasePromotion::findById($id);
    }

    public function update($id, array $data): bool|FirebasePromotion
    {
        $promotion = FirebasePromotion::findById($id);
    
        if (!$promotion) {
            throw new \Exception('Promotion non trouvée');
        }
    
        $promotion->update($data);
    
        return $promotion;
    }

    public function delete($id)
    {
        $promotion = FirebasePromotion::findById($id);

        if (!$promotion) {
            throw new \Exception('Promotion non trouvée');
        }

        $promotion->delete();

        return true;
    }
}
