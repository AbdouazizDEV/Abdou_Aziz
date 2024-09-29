<?php
namespace App\Repositories;

use App\Models\Apprenant;
use App\Repositories\Contracts\ApprenantRepositoryInterface;

class MySQLApprenantRepository implements ApprenantRepositoryInterface
{
    public function create(array $data)
    {
        return Apprenant::create($data);
    }

    public function import($file)
    {
        // Logique d'importation depuis le fichier Excel
    }

    public function all(array $filters)
    {
        $query = Apprenant::query();

        // Appliquer les filtres ici

        return $query->get();
    }

    public function find($id)
    {
        return Apprenant::find($id);
    }

    public function getInactive()
    {
        return Apprenant::where('is_active', false)->get();
    }
}
