<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotesService;
use App\Http\Resources\NoteResource;
class NotesController extends Controller
{
    protected $notesService;

    public function __construct(NotesService $notesService)
    {
        $this->notesService = $notesService;
    }

    // Ajouter une note à un groupe d'apprenants pour un module donné
    public function addNotesToModule($moduleId, Request $request)
    {
        $data = $request->validate([
            'notes' => 'required|array',
            'notes.*.apprenanteID' => 'required|integer',
            'notes.*.note' => 'required|numeric|min:0|max:20',
        ]);

        $this->notesService->addNotesToModule($moduleId, $data['notes']);
        
        return response()->json(['message' => 'Notes ajoutées avec succès.'], 201);
    }

    // Ajouter des notes de plusieurs modules à un apprenant
    public function addNotesToApprenant(Request $request)
    {
        $data = $request->validate([
            'apprenanteID' => 'required|integer',
            'notes' => 'required|array',
            'notes.*.moduleId' => 'required|integer',
            'notes.*.note' => 'required|numeric|min:0|max:20',
        ]);

        $this->notesService->addNotesToApprenant($data['apprenanteID'], $data['notes']);
        return response()->json(['message' => 'Notes ajoutées avec succès.'], 201);
    }

    // Modifier des notes d'un apprenant
    public function updateNotesForApprenant($apprenantId, Request $request)
{
    $data = $request->validate([
        'notes' => 'required|array',
        'notes.*.noteId' => 'required|integer',
        'notes.*.note' => 'required|numeric|min:0|max:20',
    ]);

    // Obtenir les notes mises à jour en tant que collection
    $updatedNotes = $this->notesService->updateNotesForApprenant($apprenantId, $data['notes']);

    // Utiliser la Resource pour renvoyer une collection JSON
    return NoteResource::collection($updatedNotes); // S'assurer que c'est une collection
}

    // Lister les notes d'un référentiel pour la promotion en cours
    public function listNotesForReferentiel($referentielId)
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('notes');
        $notes = $reference->orderByChild('referentiel_id')->equalTo($referentielId)->getValue();
    
        return response()->json($notes);
    }
    

    // Générer un relevé de notes pour un référentiel en PDF
    public function exportNotesForReferentiel($referentielId)
    {
        $pdf = $this->notesService->exportNotesForReferentiel($referentielId);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'releve_notes_referentiel.pdf');
    }

    // Générer un relevé de notes pour un apprenant en PDF
    public function exportNotesForApprenant($apprenantId)
    {
        $pdf = $this->notesService->exportNotesForApprenant($apprenantId);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'releve_notes_apprenant.pdf');
    }
}
