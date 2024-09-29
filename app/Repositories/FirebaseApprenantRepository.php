<?php
namespace App\Repositories;

use App\Models\FirebaseApprenant;
use App\Repositories\Contracts\ApprenantRepositoryInterface;

class FirebaseApprenantRepository implements ApprenantRepositoryInterface
{
    protected $database;

    public function __construct()
    {
        $this->database = app('firebase.database');
    }

    public function create(array $data)
    {
        $reference = $this->database->getReference('apprenants');
        $newApprenant = $reference->push($data);
        $data['id'] = $newApprenant->getKey();

        return $data;
    }

    public function import($file)
    {
        // Handle file import and store apprenants in Firebase
    }

    public function all(array $filters)
    {
        $reference = $this->database->getReference('apprenants');
        $apprenants = $reference->getSnapshot()->getValue() ?? [];

        // Apply filters if needed

        return $apprenants;
    }

    public function find($id)
    {
        $reference = $this->database->getReference('apprenants/' . $id);
        $data = $reference->getSnapshot()->getValue();

        if ($data) {
            $data['id'] = $id;
            return $data;
        }

        return null;
    }

    public function getInactive()
    {
        $reference = $this->database->getReference('apprenants');
        $apprenants = $reference->orderByChild('is_active')->equalTo(false)->getSnapshot()->getValue() ?? [];

        return $apprenants;
    }
}
