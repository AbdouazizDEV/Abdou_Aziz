<?php

namespace App\Services;

use App\Repositories\Contracts\PromotionRepositoryInterface;
use App\Repositories\FirebasePromotionRepository;
use Carbon\Carbon;

class PromotionService
{
    protected $promotionRepository;
    protected $firebasePromotionRepository;

    public function __construct(
        PromotionRepositoryInterface $promotionRepository,
        FirebasePromotionRepository $firebasePromotionRepository
    ) {
        $this->promotionRepository = $promotionRepository;
        $this->firebasePromotionRepository = $firebasePromotionRepository;
    }

    // Sélectionner le bon repository (MySQL ou Firebase)
    protected function getRepository()
    {
        return env('PROMOTION_DATA_SOURCE', 'mysql') === 'firebase'
            ? $this->firebasePromotionRepository
            : $this->promotionRepository;
    }

    public function createPromotion(array $data)
    {
        return $this->getRepository()->create($data);
    }

    public function getPromotions($etat = null)
    {
        return $this->getRepository()->all($etat);
    }

    public function getPromotionById($id)
    {
        return $this->getRepository()->find($id);
    }

    public function updatePromotion($id, array $data)
    {
        return $this->getRepository()->update($id, $data);
    }

    public function deletePromotion($id)
    {
        return $this->getRepository()->delete($id);
    }

    public function changeEtat($id, $etat)
    {
        //dd($etat,$id);
        // Désactiver les autres promotions si l'état est Actif
        if ($etat === 'Actif') {
            $this->deactivateOtherPromotions($id);
        }
        return $this->getRepository()->update($id, ['etat' => $etat]);
    }

    public function cloturerPromotion($id)
    {
        $promotion = $this->getRepository()->find($id);
        if (!$promotion || Carbon::parse($promotion['date_fin'])->isFuture()) {
            throw new \Exception("Impossible de clôturer la promotion avant la date de fin.");
        }

        // Mettre à jour l'état de la promotion à "Cloturer"
        $this->getRepository()->update($id, ['etat' => 'Cloturer']);

        // Déclencher un job pour envoyer les relevés de notes
        // Job::dispatch(new EnvoiRelevesNotesJob($promotion));

        return $promotion;
    }

    public function updateReferentiels($id, array $referentiels)
    {
        // return $this->getRepository()->update($id, ['referentiels' => $referentiels]);
        $promotion = $this->getPromotionById($id);
        if (!$promotion) {
            throw new \Exception('Promotion non trouvée');
        }

        // Ajouter les nouveaux référentiels sans doublons
        $currentReferentiels = $promotion['referentiels'] ?? [];
        $updatedReferentiels = array_unique(array_merge($currentReferentiels, $referentiels));

        // Mise à jour des référentiels dans Firebase
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('promotions/' . $id);
        $reference->update(['referentiels' => $updatedReferentiels]);

        return $this->getPromotionById($id); // Retourner la promotion mise à jour
    }

    protected function deactivateOtherPromotions($currentPromotionId)
    {
        return $this->getRepository()->deactivateOtherPromotions($currentPromotionId);
    }
}
