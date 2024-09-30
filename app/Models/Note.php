<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'apprenant_id', 'module_id', 'note',
    ];

   /*  protected $with = [
        'module',
    ]; */

    public function apprenant()
    {
        return $this->belongsTo(Apprenant::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
