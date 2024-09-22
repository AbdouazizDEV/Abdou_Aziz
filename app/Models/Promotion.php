<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = ['libelle', 'date_debut', 'date_fin', 'duree', 'etat', 'photo_de_couverture'];
}
