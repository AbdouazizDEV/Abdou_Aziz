<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApprenantService;

class ApprenantController extends Controller
{
    protected $apprenantService;

    public function __construct(ApprenantService $apprenantService)
    {
        $this->apprenantService = $apprenantService;
    }

    // Inscrire un apprenant
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'referentiel_id' => 'required|string', // ID du référentiel
            'photo' => 'nullable|string',
        ]);

        $apprenant = $this->apprenantService->createApprenant($data);

        return response()->json($apprenant, 201);
    }

    // Importer des apprenants via un fichier Excel
    public function import(Request $request)
    {
        // Validation et traitement de l'importation
        $failedImports = $this->apprenantService->importApprenants($request->file('file'));

        if ($failedImports->isNotEmpty()) {
            return response()->json([
                'message' => 'Some entries failed to import.',
                'failures' => $failedImports
            ], 422);
        }

        return response()->json(['message' => 'All entries imported successfully.'], 201);
    }

    // Lister les apprenants
    public function index(Request $request)
    {
        $apprenants = $this->apprenantService->getApprenants($request->all());
        return response()->json($apprenants);
    }

    // Afficher un apprenant
    public function show($id)
    {
        $apprenant = $this->apprenantService->getApprenantById($id);
        return response()->json($apprenant);
    }

    // Lister les apprenants inactifs
    public function inactiveList()
    {
        $inactiveApprenants = $this->apprenantService->getInactiveApprenants();
        return response()->json($inactiveApprenants);
    }

    // Relancer un groupe d'apprenants
    public function relance(Request $request)
    {
        $this->apprenantService->relanceApprenants($request->apprenant_ids);
        return response()->json(['message' => 'Relance envoyée avec succès.']);
    }

    // Relancer un apprenant individuel
    public function relanceIndividuel($id)
    {
        $this->apprenantService->relanceApprenant($id);
        return response()->json(['message' => 'Relance envoyée avec succès.']);
    }
}
