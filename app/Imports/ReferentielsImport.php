<?php

namespace App\Imports;

use App\Models\Referentiel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class ReferentielsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Referentiel([
            'code' => $row['code'],
            'libelle' => $row['libelle'],
            'description' => $row['description'],
            'photo' => $row['photo'],
            'statut' => $row['statut'],
        ]);
    }
}
