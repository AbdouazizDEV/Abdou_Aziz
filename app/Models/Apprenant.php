<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apprenant extends Model
{
    protected $fillable = [
        'nom', 'prenom', 'date_naissance', 'sexe', 'referentiel_id', 'photo', 'matricule', 'qr_code', 'is_active'
    ];

    // Relation avec le référentiel
    public function referentiel()
    {
        return $this->belongsTo(Referentiel::class);
    }

    // Relations avec d'autres entités (par exemple, présences, notes)
}
