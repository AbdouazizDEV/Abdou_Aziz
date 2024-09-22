<?php
namespace App\Repositories;

use App\Models\Referentiel;
use App\Models\Competence;
use App\Models\Module;
use App\Repositories\Contracts\ReferentielRepositoryInterface;
class ReferentielRepository implements ReferentielRepositoryInterface
{
    public function create(array $data)
    {
        return Referentiel::create($data);
    }

    public function update($id, array $data)
    {
        $referentiel = Referentiel::find($id);
        $referentiel->update($data);
        return $referentiel;
    }

    public function find($id)
    {
        return Referentiel::with('competences.modules')->find($id);
    }

    /* public function all($statut = null)
    {
        return $statut ? Referentiel::where('statut', $statut)->get() : Referentiel::all();
    } */
    public function all($statut = null)
    {
        // Filtrer par statut si fourni, sinon retourner tous les référentiels
        if ($statut) {
            return Referentiel::where('statut', $statut)->get();
        }

        return Referentiel::all();
    }
    public function addCompetence($referentielId, array $data)
    {
        $data['referentiel_id'] = $referentielId;
        return Competence::create($data);
    }

    public function updateCompetence($referentielId, array $data)
    {
        $competence = Competence::where('referentiel_id', $referentielId)->find($data['id']);
        $competence->update($data);
        return $competence;
    }

    public function addModule($competenceId, array $data)
    {
        $data['competence_id'] = $competenceId;
        return Module::create($data);
    }

    public function updateModule($competenceId, array $data)
    {
        $module = Module::where('competence_id', $competenceId)->find($data['id']);
        $module->update($data);
        return $module;
    }
    public function softDelete($id)
    {
        $referentiel = Referentiel::find($id);
        if ($referentiel) {
            $referentiel->delete(); // Soft delete
            return true;
        }
        return false;
    }
}
