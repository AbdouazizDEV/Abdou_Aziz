<?php

namespace App\Repositories\Contracts;

interface PromotionRepositoryInterface
{
    public function create(array $data): array;
    public function all($etat = null): array;
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
}
