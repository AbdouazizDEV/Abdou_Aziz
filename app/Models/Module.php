<?php
// Module.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    protected $fillable = ['nom', 'description', 'duree_acquisition', 'competence_id'];

    public function competence()
    {
        return $this->belongsTo(Competence::class);
    }
}
