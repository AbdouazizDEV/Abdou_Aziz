<?php
namespace App\Http\Controllers;

use App\Services\ReferentielService;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
class ReferentielController extends Controller
{
    protected $referentielService;

    public function __construct(ReferentielService $referentielService)
    {
        $this->referentielService = $referentielService;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:referentiels',
            'libelle' => 'required|unique:referentiels',
            'description' => 'nullable|string',
            'photo_couverture' => 'nullable|string',
            'competences' => 'array',
        ]);

        $referentiel = $this->referentielService->createReferentiel($data);

        return response()->json($referentiel, 201);
    }

    public function index(Request $request)
    {
        $statut = $request->query('statut', 'actif'); // Par défaut on filtre les actifs
        $referentiels = $this->referentielService->all($statut); // Utilisation de la méthode all() du service
    
        return response()->json($referentiels);
    }
    

    public function show($id)
    {
        $referentiel = $this->referentielService->find($id);

        if (!$referentiel) {
            return response()->json(['message' => 'Référentiel non trouvé'], 404);
        }

        return response()->json($referentiel);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'code' => 'sometimes|unique:referentiels,code,' . $id,
            'libelle' => 'sometimes|unique:referentiels,libelle,' . $id,
            'description' => 'nullable|string',
            'competences' => 'array',
        ]);

        $referentiel = $this->referentielService->updateReferentiel($id, $data);

        return response()->json($referentiel);
    }

    public function destroy($id)
    {
        $referentiel = $this->referentielService->softDelete($id);

        return response()->json(['message' => 'Référentiel supprimé (soft delete)']);
    }
    
}
