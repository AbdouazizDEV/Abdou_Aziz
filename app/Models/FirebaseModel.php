<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kreait\Firebase\Database;

abstract class FirebaseModel extends Model implements ModelFirebase
{
    protected $firebase;
    public $collection;
    public function __construct(array $attributes = [],$collection = null)
    {
        parent::__construct($attributes);
        $this->firebase = app('firebase.database');

        $this->collection = $collection ?? $this->getCollectionName();
    }

    

    public function save(array $attributes = [])
    {
        $data = $this->attributesToArray();
        $this->firebase->getReference('users')->push($data);
    }
}
