<?php

namespace App\Services;

use App\Repositories\Contracts\PromotionRepositoryInterface;
use App\Repositories\FirebasePromotionRepository;

class PromotionService
{
    protected $promotionRepository;
    protected $firebasePromotionRepository;

    public function __construct(PromotionRepositoryInterface $promotionRepository, FirebasePromotionRepository $firebasePromotionRepository)
    {
        $this->promotionRepository = $promotionRepository;
        $this->firebasePromotionRepository = $firebasePromotionRepository;
    }

    protected function getDataSource()
    {
        return env('PROMOTION_DATA_SOURCE', 'mysql'); // Par défaut, MySQL
    }

    public function createPromotion(array $data)
    {
        if ($this->getDataSource() === 'firebase') {
            return $this->firebasePromotionRepository->create($data);
        }

        return $this->promotionRepository->create($data);
    }

    public function getPromotions($etat = null)
    {
        if ($this->getDataSource() === 'firebase') {
            return $this->firebasePromotionRepository->all($etat);
        }

        return $this->promotionRepository->all($etat);
    }

    public function getPromotionById($id)
    {
        if ($this->getDataSource() === 'firebase') {
            return $this->firebasePromotionRepository->find($id);
        }

        return $this->promotionRepository->find($id);
    }

    public function updatePromotion($id, array $data)
    {
        if ($this->getDataSource() === 'firebase') {
            return $this->firebasePromotionRepository->update($id, $data);
        }

        return $this->promotionRepository->update($id, $data);
    }

    public function deletePromotion($id)
    {
        if ($this->getDataSource() === 'firebase') {
            return $this->firebasePromotionRepository->delete($id);
        }

        return $this->promotionRepository->delete($id);
    }
}
