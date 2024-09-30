<?php

namespace App\Repositories;

use App\Repositories\Contracts\NotesRepositoryInterface;

class FirebaseNotesRepository implements NotesRepositoryInterface
{
    protected $database;

    public function __construct()
    {
        $this->database = app('firebase.database');
    }

    public function addNoteToModule($apprenantId, $moduleId, $note)
    {
        $reference = $this->database->getReference('notes');
        return $reference->push([
            'apprenant_id' => $apprenantId,
            'module_id' => $moduleId,
            'note' => $note,
        ]);
    }

    public function updateNote($noteId, $note)
    {
        $reference = $this->database->getReference('notes/' . $noteId);
        $reference->update(['note' => $note]);
    }

    /* public function getNotesForReferentiel($referentielId)
    {
        $reference = $this->database->getReference('notes');
        return $reference->orderByChild('referentiel_id')->equalTo($referentielId)->getValue();
    } */
    public function getNotesForReferentiel($referentielId)
    {
        $reference = $this->database->getReference('notes');
        $notes = $reference->orderByChild('referentiel_id')->equalTo($referentielId)->getValue();
    
        if ($notes === null) {
            return []; // Retourner un tableau vide si aucune note n'est trouvée
        }
    
        // Structurer correctement les données récupérées
        return collect($notes)->map(function ($note, $key) {
            return array_merge($note, ['id' => $key]); // Ajouter l'ID Firebase comme champ 'id'
        });
    }
    
}
