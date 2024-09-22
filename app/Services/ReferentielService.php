<?php
namespace App\Services;

use App\Repositories\Contracts\ReferentielRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Repositories\FirebaseReferentielRepository;

class ReferentielService
{
    protected $referentielRepository;
    protected $firebaseReferentielRepository;

    public function __construct(
        ReferentielRepositoryInterface $referentielRepository,
        FirebaseReferentielRepository $firebaseReferentielRepository
    ) {
        $this->referentielRepository = $referentielRepository;
        $this->firebaseReferentielRepository = $firebaseReferentielRepository;
    }

    public function createReferentiel(array $data)
    {
        return DB::transaction(function () use ($data) {
            $dataSource = env('USER_DATA_SOURCE', 'mysql');
            //dd($dataSource);
            if ($dataSource === 'firebase') {
                $referentiel = $this->firebaseReferentielRepository->create($data);
            } else {
                $referentiel = $this->referentielRepository->create($data);
            }

            if (isset($data['competences'])) {
                foreach ($data['competences'] as $competenceData) {
                    $competence = $this->addCompetenceToReferentiel($referentiel, $competenceData);

                    if (isset($competenceData['modules'])) {
                        foreach ($competenceData['modules'] as $moduleData) {
                            $this->addModuleToCompetence($competence, $moduleData);
                        }
                    }
                }
            }

            return $referentiel;
        });
    }

    private function addCompetenceToReferentiel($referentiel, array $competenceData)
    {
        return $this->referentielRepository->addCompetence($referentiel->id, $competenceData);
    }

    private function addModuleToCompetence($competence, array $moduleData)
    {
        return $this->referentielRepository->addModule($competence->id, $moduleData);
    }

    public function updateReferentiel($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $referentiel = $this->referentielRepository->update($id, $data);

            if (isset($data['competences'])) {
                foreach ($data['competences'] as $competenceData) {
                    $competence = $this->referentielRepository->updateCompetence($referentiel->id, $competenceData);

                    if (isset($competenceData['modules'])) {
                        foreach ($competenceData['modules'] as $moduleData) {
                            $this->referentielRepository->updateModule($competence->id, $moduleData);
                        }
                    }
                }
            }

            return $referentiel;
        });
    }

    public function all($statut = null)
    {
        return $this->referentielRepository->all($statut);
    }

    public function find($id)
    {
        return $this->referentielRepository->find($id);
    }

    public function softDelete($id)
    {
        return $this->referentielRepository->softDelete($id);
    }
}
