<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kreait\Firebase\Database;

abstract class FirebaseModel extends Model implements ModelFirebase
{
    protected $firebase;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->firebase = app('firebase.database');
    }

    public function save(array $options = [])
    {
        $data = $this->attributesToArray();
        $this->firebase->getReference('users')->push($data);
    }
}
