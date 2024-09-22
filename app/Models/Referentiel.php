<?php

// Referentiel.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referentiel extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'libelle', 'description', 'photo_couverture', 'statut'];

    public function competences()
    {
        return $this->hasMany(Competence::class);
    }
}
