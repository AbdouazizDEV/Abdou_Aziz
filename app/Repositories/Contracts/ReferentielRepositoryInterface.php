<?php

namespace App\Repositories\Contracts;

interface ReferentielRepositoryInterface
{
    public function create(array $data): array;
    public function all($statut = null): array;
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
}
