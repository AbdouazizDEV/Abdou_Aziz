<?php

namespace App\Services;

use App\Repositories\Contracts\ReferentielRepositoryInterface;
use App\Repositories\FirebaseReferentielRepository;

class ReferentielService
{
    protected $referentielRepository;
    protected $firebaseReferentielRepository;

    public function __construct(ReferentielRepositoryInterface $referentielRepository, FirebaseReferentielRepository $firebaseReferentielRepository)
    {
        $this->referentielRepository = $referentielRepository;
        $this->firebaseReferentielRepository = $firebaseReferentielRepository;
    }

    // Méthode pour décider où enregistrer (MySQL ou Firebase)
    protected function getDataSource()
    {
        return env('REFERENTIEL_DATA_SOURCE', 'mysql');  // Par défaut sur MySQL
    }

    public function createReferentiel(array $data)
    {
        // Enregistrer dans MySQL ou Firebase en fonction du .env
        if ($this->getDataSource() === 'firebase') {
            return $this->firebaseReferentielRepository->create($data);
        }

        return $this->referentielRepository->create($data);
    }

    public function getReferentiels($statut = null)
    {
        if ($this->getDataSource() === 'firebase') {
            return $this->firebaseReferentielRepository->all($statut);
        }

        return $this->referentielRepository->all($statut);
    }

    public function getReferentielById($id)
    {
        if ($this->getDataSource() === 'firebase') {
            return $this->firebaseReferentielRepository->find($id);
        }

        return $this->referentielRepository->find($id);
    }

    public function updateReferentiel($id, array $data)
    {
        if ($this->getDataSource() === 'firebase') {
            return $this->firebaseReferentielRepository->update($id, $data);
        }

        return $this->referentielRepository->update($id, $data);
    }

    public function deleteReferentiel($id)
    {
        if ($this->getDataSource() === 'firebase') {
            return $this->firebaseReferentielRepository->delete($id);
        }

        return $this->referentielRepository->delete($id);
    }
}
