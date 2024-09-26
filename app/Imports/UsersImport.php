<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'adresse' => $row['adresse'],
            'telephone' => $row['telephone'],
            'role_id' => $row['role_id'],
            'email' => $row['email'],
            'photo' => $row['photo'],
            'statut' => $row['statut'],
            'password' => bcrypt($row['password']),
        ]);
    }
}
