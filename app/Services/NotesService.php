<?php

namespace App\Services;

use App\Repositories\Contracts\NotesRepositoryInterface;

class NotesService
{
    protected $notesRepository;

    public function __construct(NotesRepositoryInterface $mysqlNotesRepository, NotesRepositoryInterface $firebaseNotesRepository)
    {
        $this->notesRepository = env('NOTE_DATA_SOURCE', 'mysql') === 'firebase'
            ? $firebaseNotesRepository
            : $mysqlNotesRepository;
    }

    // Ajouter des notes pour un module à un groupe d'apprenants
    public function addNotesToModule($moduleId, array $notes)
    {
        foreach ($notes as $noteData) {
            $this->notesRepository->addNoteToModule($noteData['apprenanteID'], $moduleId, $noteData['note']);
        }
    }

    // Ajouter des notes pour un apprenant sur plusieurs modules
    public function addNotesToApprenant($apprenantId, array $notes)
    {
        foreach ($notes as $noteData) {
            $this->notesRepository->addNoteToModule($apprenantId, $noteData['moduleId'], $noteData['note']);
        }
    }

    // Modifier des notes d'un apprenant
    public function updateNotesForApprenant($apprenantId, array $notes)
{
    $updatedNotes = [];
    foreach ($notes as $noteData) {
        $updatedNote = $this->notesRepository->updateNote($noteData['noteId'], $noteData['note']);
        $updatedNotes[] = $updatedNote; // Ajouter les notes mises à jour au tableau
    }

    return collect($updatedNotes); // Retourner une collection d'objets
}


    // Récupérer les notes pour un référentiel
    public function getNotesForReferentiel($referentielId)
    {
        return $this->notesRepository->getNotesForReferentiel($referentielId);
    }

    // Générer le relevé de notes en PDF pour un référentiel
    public function exportNotesForReferentiel($referentielId)
    {
        // Génération du PDF (logique d'export via un package PDF comme dompdf)
    }

    // Générer le relevé de notes en PDF pour un apprenant
    public function exportNotesForApprenant($apprenantId)
    {
        // Génération du PDF
    }
}
