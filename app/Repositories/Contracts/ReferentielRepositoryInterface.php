<?php

namespace App\Repositories\Contracts;

interface ReferentielRepositoryInterface
{
    public function create(array $data);
    public function all($statut = null);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
}
