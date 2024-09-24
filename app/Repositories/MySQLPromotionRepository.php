<?php

namespace App\Repositories;

use App\Models\Promotion;
use App\Repositories\Contracts\PromotionRepositoryInterface;

class MySQLPromotionRepository implements PromotionRepositoryInterface
{
    public function create(array $data): array
    {
        $promotion = Promotion::create($data);
        return $promotion->toArray();
    }

    public function all($etat = null): array
    {
        $query = Promotion::query();
        if ($etat) {
            $query->where('etat', $etat);
        }
        return $query->get()->toArray();
    }

    public function find($id)
    {
        return Promotion::find($id);
    }

    public function update($id, array $data)
    {
        $promotion = Promotion::find($id);
        if ($promotion) {
            $promotion->update($data);
            return $promotion;
        }
        return null;
    }

    public function delete($id)
    {
        $promotion = Promotion::find($id);
        if ($promotion) {
            $promotion->delete();
            return true;
        }
        return false;
    }
}
