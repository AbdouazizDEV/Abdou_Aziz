<?php

namespace App\Repositories;

use App\Models\Note;
use App\Repositories\Contracts\NotesRepositoryInterface;

class MySQLNotesRepository implements NotesRepositoryInterface
{
    public function addNoteToModule($apprenantId, $moduleId, $note)
    {
        return Note::create([
            'apprenant_id' => $apprenantId,
            'module_id' => $moduleId,
            'note' => $note,
        ]);
    }

    /* public function updateNote($noteId, $note)
    {
        $note = Note::findOrFail($noteId);
        $note->update(['note' => $note]);
        return $note;
    } */
    public function updateNote($noteId, $newNoteValue)
    {
        $note = Note::findOrFail($noteId);
        $note->update(['note' => $newNoteValue]);
    
        // Renvoyer sans relations pour éviter la récursion
        return $note;
    }
    
    
    public function getNotesForReferentiel($referentielId)
{
    return Note::whereHas('module', function ($query) use ($referentielId) {
        $query->where('referentiel_id', $referentielId);
    })->with('module') // Charger la relation module
      ->get();
}

}
