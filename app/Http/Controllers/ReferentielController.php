<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReferentielService;
use App\Imports\ReferentielsImport;
use Maatwebsite\Excel\Facades\Excel;

class ReferentielController extends Controller
{
    protected $referentielService;

    public function __construct(ReferentielService $referentielService)
    {
        $this->referentielService = $referentielService;
    }
    public function import(Request $request)
    {
        // Valider le fichier Excel
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        // Importer les référentiels
        Excel::import(new ReferentielsImport, $request->file('file'));

        return response()->json(['message' => 'Référentiels importés avec succès'], 200);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:referentiels,code|max:255',
            'libelle' => 'required|unique:referentiels,libelle|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|string',
        ]);

        $referentiel = $this->referentielService->createReferentiel($data);
        return response()->json($referentiel, 201);
    }

    public function index(Request $request)
    {
        $statut = $request->get('statut');
        $referentiels = $this->referentielService->getReferentiels($statut);
        return response()->json($referentiels);
    }

    public function show($id)
    {
        $referentiel = $this->referentielService->getReferentielById($id);
        return response()->json($referentiel);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'code' => 'sometimes|unique:referentiels,code,' . $id . '|max:255',
            'libelle' => 'sometimes|unique:referentiels,libelle,' . $id . '|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|string',
        ]);

        $referentiel = $this->referentielService->updateReferentiel($id, $data);
        return response()->json($referentiel);
    }

    public function destroy($id)
    {
        $this->referentielService->deleteReferentiel($id);
        return response()->json(['message' => 'Référentiel supprimé avec succès']);
    }
}
