<?php

namespace App\Repositories;

use Kreait\Firebase\Database;
use App\Repositories\Contracts\ReferentielRepositoryInterface;

class FirebaseReferentielRepository implements ReferentielRepositoryInterface
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function create(array $data)
    {
        // Créer une référence Firebase pour les référentiels
        $reference = $this->database->getReference('referentiels')->push($data); // Pas de .json ici
        return $reference->getValue();
    }

    public function update($id, array $data)
    {
        $reference = $this->database->getReference('referentiels/' . $id); // Pas de .json ici
        $reference->update($data);
        return $reference->getValue();
    }

    public function find($id)
    {
        $reference = $this->database->getReference('referentiels/' . $id); // Pas de .json ici
        return $reference->getValue();
    }

    public function all($statut = null)
    {
        $reference = $this->database->getReference('referentiels'); // Pas de .json ici
        $snapshots = $reference->getSnapshot();
        $referentiels = $snapshots->getValue();

        if ($statut) {
            return array_filter($referentiels, function ($referentiel) use ($statut) {
                return isset($referentiel['statut']) && $referentiel['statut'] === $statut;
            });
        }

        return $referentiels ?: [];
    }

    public function addCompetence($referentielId, array $data)
    {
        $reference = $this->database->getReference('referentiels/' . $referentielId . '/competences')->push($data); // Pas de .json ici
        return $reference->getValue();
    }

    public function updateCompetence($referentielId, array $data)
    {
        $competenceId = $data['id'];
        unset($data['id']);
        $reference = $this->database->getReference('referentiels/' . $referentielId . '/competences/' . $competenceId); // Pas de .json ici
        $reference->update($data);
        return $reference->getValue();
    }

    public function addModule($competenceId, array $data)
    {
        $reference = $this->database->getReference('competences/' . $competenceId . '/modules')->push($data); // Pas de .json ici
        return $reference->getValue();
    }

    public function updateModule($competenceId, array $data)
    {
        $moduleId = $data['id'];
        unset($data['id']);
        $reference = $this->database->getReference('competences/' . $competenceId . '/modules/' . $moduleId); // Pas de .json ici
        $reference->update($data);
        return $reference->getValue();
    }

    public function softDelete($id)
    {
        $reference = $this->database->getReference('referentiels/' . $id);
        $reference->update(['statut' => 'archiver']);
        return true;
    }
}
