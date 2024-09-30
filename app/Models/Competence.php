<?php
// Competence.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competence extends Model
{
    use SoftDeletes;

    protected $fillable = ['nom', 'description', 'duree_acquisition', 'type', 'referentiel_id'];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function referentiel()
    {
        return $this->belongsTo(Referentiel::class);
    }
}
