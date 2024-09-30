<?php

namespace App\Repositories\Contracts;

interface NotesRepositoryInterface
{
    public function addNoteToModule($apprenantId, $moduleId, $note);
    public function updateNote($noteId, $note);
    public function getNotesForReferentiel($referentielId);
}
