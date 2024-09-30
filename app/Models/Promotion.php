<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'libelle', 'date_debut', 'date_fin', 'duree', 'etat', 'photo'
    ];

    public function referentiels()
    {
        return $this->belongsToMany(Referentiel::class, 'promotion_referentiels'); // Relation Many-to-Many avec les référentiels
    }

    // public function apprenants()
    // {
    //     return $this->hasMany(Apprenant::class);
    // }
}
