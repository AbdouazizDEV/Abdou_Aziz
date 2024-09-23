<?php

namespace App\Repositories;

use App\Models\Referentiel;
use App\Repositories\Contracts\ReferentielRepositoryInterface;

class MySQLReferentielRepository implements ReferentielRepositoryInterface
{
    public function create(array $data): array
    {
        $referentiel = Referentiel::create($data);
        return $referentiel->toArray();
    }

    public function all($statut = null): array
    {
        $query = Referentiel::query();
        if ($statut) {
            $query->where('statut', $statut);
        }
        return $query->get()->toArray();
    }

    public function find($id)
    {
        return Referentiel::find($id);
    }

    public function update($id, array $data)
    {
        $referentiel = Referentiel::find($id);
        if ($referentiel) {
            $referentiel->update($data);
            return $referentiel;
        }
        return null;
    }

    public function delete($id)
    {
        $referentiel = Referentiel::find($id);
        if ($referentiel) {
            $referentiel->delete();
            return true;
        }
        return false;
    }
}
